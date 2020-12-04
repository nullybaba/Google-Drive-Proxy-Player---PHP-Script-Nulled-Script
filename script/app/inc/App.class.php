<?php
class App {
    protected $db, $config, $http;
    protected $actions = ["dashboard", "video", "ajax", "links", "file", "users", "settings", "ads", "api", "login", "logout"];
    public $link = NULL;
    protected $logged = FALSE;
    protected $hasAccess = TRUE;
    protected $isAdmin = FALSE, $userId = "0", $userAccess = [];
    public function __construct($db, $config) {
        $this->config = $config;
        $this->db = $db;
        // Clean Request
        if (isset($_GET)) $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        // if (isset($_POST)) $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $this->http = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http");
    }
    /**
     * Run Script

     * @return  [type] [description]
     */
    public function run() {
        // initial main objects
        $this->link = new Link($this->db, $this->config);
        $this->user = new User($this->db, $this->config);
        $this->setup();
        if (isset($_GET["a"]) && !empty($_GET["a"])) {
            // Validate Request
            $var = explode("/", $_GET["a"]);
            // Removes dots
            $var[0] = str_replace(".", "", $var[0]);
            $this->action = Main::clean($var[0]);
            //Run Methods
            if (isset($var[1]) && !empty($var[1])) $this->pf = Main::clean($var[1]);
            if (isset($var[2]) && !empty($var[2])) $this->ps = Main::clean($var[2]);
            if (in_array($var[0], $this->actions)) {
                $this->check();
                return $this->{$var[0]}();
            } else {
                $this->_404();
            }
        }
        //Run Homepage
        return $this->home();
    }
    /**
     * Home page action

     */
    public function home() {
        include (TEMPLATE . "/index.php");
    }
    /**
     * Dashboard page action

     */
    public function dashboard() {
        $aData = $this->_analytics();
        $this->header();
        include (TEMPLATE . "/dashboard.php");
        $this->footer();
    }
    public function settings() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_FILES['player_logo']) && $_FILES['player_logo']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = strtolower(str_replace(' ', '_', $_FILES['player_logo']['name']));
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                if (in_array($ext, $allowed)) {
                    move_uploaded_file($_FILES['player_logo']['tmp_name'], ROOT . "/uploads/" . $filename);
                }
            } elseif (isset($_POST['plogo']) && !empty($_POST['plogo'])) {
                $filename = $_POST['plogo'];
            } else {
                $filename = '';
            }
            $firewall = (isset($_POST['firewall']) && $_POST['firewall'] == 'on') ? 1 : 0;
            if (!empty($_POST['allowed_domains'])) {
                $allowed_domains = str_replace(' ', '', $_POST['allowed_domains']);
                $allowed_domains = explode(',', $allowed_domains);
            } else {
                $allowed_domains = [];
            }
            if (!empty($_POST['sublist'])) {
                $subs = str_replace(' ', '', $_POST['sublist']);
                $subs = explode(',', $subs);
            } else {
                $subs = [];
            }
            $allowed_domains = json_encode($allowed_domains);
            $subs = json_encode($subs);
            $player_logo = $filename;
            $dark_theme = (isset($_POST['dark_theme']) && $_POST['dark_theme'] == 'on') ? 1 : 0;
            $netflix_skin = (isset($_POST['netflix_skin']) && $_POST['netflix_skin'] == 'on') ? 1 : 0;
            $timezone = (isset($_POST['timezone']) && !empty($_POST['timezone'])) ? $_POST['timezone'] : 'Asia/Colombo';
            $apikey = (isset($_POST['apikey']) && !empty($_POST['apikey'])) ? $_POST['apikey'] : '';
            $this->_updateSettings(['firewall' => $firewall, 'apikey' => $apikey, 'player_logo' => $player_logo, 'netflix_skin' => $netflix_skin, 'sublist' => $subs, 'allowed_domains' => $allowed_domains, 'dark_theme' => $dark_theme, 'timezone' => $timezone]);
            Main::redirect('settings');
        }
        $this->header();
        include (TEMPLATE . "/settings.php");
        $this->footer();
    }
    public function api() {
        $err = '';
        if (isset($_GET['apikey']) && $_GET['apikey'] == $this->config['apikey']) {
            $gurl = isset($_GET['url']) && !empty($_GET['url']) ? Main::cleanStr($_GET['url']) : '';
            $title = isset($_GET['title']) && !empty($_GET['title']) ? Main::cleanStr($_GET['title']) : '';
            if (!empty($gurl)) {
                $response = $this->link->add(['id' => '', 'gurl' => $gurl, 'title' => $title, 'subtitles' => 'NULL', 'user_id' => $this->config['adminId']]);
                if ($response->status == 'success') {
                    //attempt to save object data
                    if ($id = $this->link->save()) {
                        $link = $this->link->findById($id);
                        if (empty($link)) {
                            $err = 'Something Went wrong !';
                        }
                    } else {
                        $err = 'Link Save Failed !';
                    }
                } else {
                    $err = $response->msg;
                }
            } else {
                $err = 'Drive URL is Required !';
            }
        } else {
            $err = 'Invalid API Key !';
        }
        if (empty($err)) {
            $res = ['status' => 'success', 'data' => ['title' => $link['title'], 'player' => Main::getDomain() . '/video/' . $link['slug'], 'type' => $link['type']]];
        } else {
            $res = ['status' => 'failed', 'error' => $err];
        }
        echo json_encode($res);
    }
    public function ads() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $vastAds = [];
            $popAds = '';
            if (isset($_POST['vast'])) {
                if (is_array($_POST['vast'])) {
                    foreach ($_POST['vast'] as $vast) {
                        if (!empty($vast['tag']) && !empty($vast['offset'])) {
                            $vastAds[] = $vast;
                        }
                    }
                }
            }
            $vastAds = json_encode($vastAds);
            $popAds = Main::cleanStr($_POST['popads']);
            $this->_updateSettings(['vastAds' => $vastAds, 'popAds' => $popAds]);
            Main::redirect('ads');
        }
        $this->header();
        include (TEMPLATE . "/advertisement.php");
        $this->footer();
    }
    /**
     * Application pre setup action

     */
    protected function setup() {
        if (isset($_SESSION['user'])) {
            $user = $this->user->get('username', $_SESSION['user']);
            if (!empty($user)) {
                if ($user['role'] == 'admin') $this->isAdmin = TRUE;
                $this->userId = $user['id'];
                $this->logged = TRUE;
                $this->userAccess = json_decode($user['permission'], true);
            }
        }
    }
    /**
     * Check Application permissions

     */
    protected function check() {
        $public = ['video', 'file', 'login', 'api'];
        $permission = ['new' => 1, 'edit' => 2, 'delete' => 3];
        $ha = 1;
        if ($this->logged) {
            if (!$this->isAdmin) {
                if (isset($this->pf) && $this->pf != 'all' && $this->action != 'links') {
                    //no permission
                    $ha = 0;
                    // die('Access denied !');

                }
                if ($this->action == 'links' && isset($this->pf)) {
                    switch ($this->pf) {
                        case 'new':
                            if (!in_array(1, $this->userAccess)) $ha = 0;
                            break;
                        case 'edit':
                            if (!in_array(2, $this->userAccess)) $ha = 0;
                            break;
                        }
                    }
                    if ($this->action == 'ajax') {
                        if (isset($_GET['type'])) {
                            $requestType = $_GET['type'];
                            switch ($requestType) {
                                case 'add_link':
                                    $pn = (isset($_GET['id']) && !empty($_GET['id'])) ? 2 : 1;
                                    if (!in_array($pn, $this->userAccess)) $ha = 0;
                                    break;
                                case 'delete_link':
                                    if (!in_array(3, $this->userAccess)) $ha = 0;
                                    break;
                                case 'delete_user':
                                case 'save_user':
                                    $ha = 0;
                                    break;
                                }
                            }
                    }
                }
            } else {
                if (!in_array($this->action, $public)) {
                    $ha = 0;
                }
            }
            if (in_array($this->action, $public) && $ha == 0) $ha = 1;
            if ($ha == 0) {
                $this->hasAccess = FALSE;
                if ($this->action != 'ajax') {
                    die('no access !');
                }
            }
        }
        /**
         * Application logout

         */
        public function logout() {
            // Destroy Cookie
            // if(isset($_COOKIE["login"])) setcookie('login','',time()-3600,'/');
            // Destroy Session
            if (isset($_SESSION["logged"])) unset($_SESSION["logged"]);
            if (isset($_SESSION["user"])) unset($_SESSION["user"]);
            Main::redirect('login');
        }
        /**
         * Application login

         */
        public function login() {
            $err = '';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (!empty($_POST['username']) && !empty($_POST['password'])) {
                    //check user is valid
                    $user = $this->user->get('username', $_POST['username']);
                    if (!empty($user)) {
                        //varify passsword
                        if (password_verify($_POST['password'], $user['password'])) {
                            //user valid
                            $_SESSION['user'] = $user['username'];
                            $_SESSION['logged'] = 1;
                            Main::redirect('dashboard');
                        } else {
                            //invalid password
                            $err = 'Invalid password !';
                        }
                    } else {
                        //invalid username
                        $err = 'Invalid username !';
                    }
                } else {
                    // username and password is required
                    $err = 'Username and password is required !';
                }
            }
            if ($this->logged) Main::redirect('dashboard');
            include (TEMPLATE . "/login.php");
        }
        /**
         * Users page action

         */
        public function users() {
            $err = '';
            $isEdit = $default = false;
            if (isset($this->pf) && !empty($this->pf)) {
                $action = $this->pf;
                switch ($this->pf) {
                    case 'add':
                        $user = $this->user->obj;
                    break;
                    case 'edit':
                        $isEdit = true;
                        if (isset($this->ps) && is_numeric($this->ps)) {
                            $user = $this->user->get('id', $this->ps);
                            if ($user == false) $err = 'User Not Found !';
                        } else {
                            // rediret

                        }
                        break;
                    default:
                        $this->_404();
                        break;
                    }
            } else {
                $default = true;
                $action = 'All';
                $users = $this->user->getAll();
            }
            $this->header();
            include (TEMPLATE . "/users.php");
            $this->footer();
        }
        /**
         * Video page action

         */
        public function video() {
            // $this->_firewall();
            $sources = '';
            if (isset($this->pf) && !empty($this->pf)) {
                $file = $this->link->findBySlug($this->pf);
                if ($file !== false) {
                    $ftype = $file['type'];
                    switch ($ftype) {
                        case 'GDrive':
                            //google drive
                            if ($this->config['stream'] == 1 && empty($file['data'])) {
                                $remaked = $this->_remake($file);
                                if ($remaked !== FALSE) {
                                    $file = $remaked;
                                } else {
                                    die('Can not play this video !');
                                }
                            }
                            $cdf = ($this->config['stream'] == 1) ? TRUE : FALSE;
                            $sources = Main::makeVideoFile($file, $cdf);
                        break;
                        case 'GPhoto':
                            $gp = $file['data'];
                            $sources = Main::getGPhotos($gp);
                        break;
                        case 'OneDrive':
                            $od = $file['data'];
                            $sources = Main::getOneDrive($od);
                        break;
                        default:
                            // code...

                        break;
                    }
                    if ($file['subtitles'] != 'NULL') {
                        $subtitles = "[" . $file['subtitles'] . "]";
                    } else {
                        $subtitles = '[{}]';
                    }
                    $thumb = $file['thumb'];
                    $this->link->addView($file['id']);
                } else {
                    //check if this is auto embed link
                    if ($this->config['auto_embed'] == 1 && strlen($this->pf) > 25) {
                        //can be google drive link
                        $videoFile = ['title' => 'auto embed', 'links' => [Main::getL($this->pf) ]];
                        $this->link->addAEView();
                    } else {
                        $this->_404();
                    }
                }
            }
            include (TEMPLATE . "/player/video.php");
        }
        /**
         * File streaming

         */
        public function file() {
            if (isset($this->pf) && !empty($this->pf)) {
                $file = $this->link->findBySlug($this->pf);
                if ($file !== false) {
                    $q = (isset($this->ps) && !empty($this->ps)) ? $this->ps : '360';
                    $source = $this->getVideo($q, Main::createObj($file));
                    $headers = $source["headers"];
                    header($headers[0]);
                    // dnd($headers);
                    header("Devloped-By: codyseller");
                    // header("Pragma: public");
                    // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    // header("Content-Disposition: attachment; filename=\"video_p.mp4\"");
                    if (http_response_code() != "403") {
                        if (isset($headers["Content-Type"])) {
                            header("Content-Type: " . $headers["Content-Type"]);
                        }
                        if (isset($headers["Accept-Ranges"])) {
                            header("Accept-Ranges: " . $headers["Accept-Ranges"]);
                        }
                        if (isset($headers["Content-Range"])) {
                            header("Content-Range: " . $headers["Content-Range"]);
                        }
                        header("Transfer-encoding: chunked");
                        header("Connection: keep-alive");
                        header("Cache-Control: max-age=2592000, public");
                        $fp = fopen($source['link'], "rb");
                        while (!feof($fp)) {
                            set_time_limit(0);
                            echo fread($fp, 1024 * 1024 * 5);
                            // flush();
                            ob_flush();
                        }
                        fclose($fp);
                        exit;
                    } else {
                        die('Something went wrong !');
                    }
                } else {
                    //file not found
                    die('File Not Found !');
                }
            } else {
                // /empty file id
                die('empty file id');
            }
        }
        /**
         * Links page action

         */
        public function links() {
            $type = 'active';
            $action = isset($this->pf) ? $this->pf : '';
            
            switch ($action) {
                case 'edit':
                case 'new':
                    // uniqid()
                    $isEdit = false;
                    if (isset($this->ps)) {
                        if (is_numeric($this->ps)) {
                            $isEdit = true;
                        } else {
                            $this->_404();
                        }
                    }
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $err = [];
                        $sub_list = [];
                        $altData = [];
                        $allowed = ['vtt', 'srt', 'dfxp', 'ttml', 'xml'];
                        if (empty($_POST['murl'])) {
                            $err[] = 'Main drive link is required !';
                        }
                        if (isset($_POST['sub']) && is_array($_POST['sub'])) {
                            foreach ($_POST['sub'] as $sk => $sub) {
                                if (!$isEdit || ($isEdit && isset($_FILES['sub']['name'][$sk]['file']) && !empty($_FILES['sub']['name'][$sk]['file']))) {
                                    if (isset($_FILES['sub']['name'][$sk]) && !empty($_FILES['sub']['name'][$sk]['file'])) {
                                        $filename = $_FILES['sub']['name'][$sk]['file'];
                                        if ($_FILES['sub']['error'][$sk]['file'] == 0) {
                                            $ext = pathinfo($filename, PATHINFO_EXTENSION);
                                            $temp_name = $_FILES['sub']['tmp_name'][$sk]['file'];
                                            if (in_array($ext, $allowed)) {
                                                //try to upload now
                                                $filename = strtolower(str_replace(' ', '_', $filename));
                                                $sdf = '{"kind": "captions","file": "' . PROOT . '/uploads/subtitles/' . $filename . '",  "label": "' . $sub['label'] . '"  }';
                                                $sub_list[] = $sdf;
                                                move_uploaded_file($temp_name, ROOT . "/uploads/subtitles/" . $filename);
                                            } else {
                                                $err[] = 'Invalid file extension ! -> ' . $filename;
                                            }
                                        }
                                    }
                                } else {
                                    if (isset($sub['file']) && !empty($sub['file'])) {
                                        if (!isset($sub['label'])) {
                                            $sub['label'] = '';
                                        }
                                        $sdf = '{"kind": "captions","file": "' . $sub['file'] . '",  "label": "' . $sub['label'] . '"  }';
                                        $sub_list[] = $sdf;
                                    }
                                }
                            }
                        }

                        if (!empty($_POST['alt_link']) &&  Main::is_url($_POST['alt_link'])) {
                          $type = Main::getSourceSite($_POST['alt_link']);
                          if($type != 'GDrive') $type = 'Custom';
                          $altData = [
                            'type' => $type,
                            'link' => $_POST['alt_link'],
                            'data' => NULL
                          ];
                        }

                        if (empty($err)) {
                            $id = (isset($_POST['id']) && is_numeric($_POST['id'])) ? $_POST['id'] : '';
                            $murl = $_POST['murl'];
                            $altData = json_encode($altData);

                            $title = isset($_POST['title']) ? $_POST['title'] : '';
                            $subs = !empty($sub_list) ? implode(',', $sub_list) : 'NULL';
                            $response = $this->link->add(['id' => $id, 'gurl' => $murl,'alt_data' => $altData,  'title' => $title, 'subtitles' => $subs, 'user_id' => $this->userId]);
                            if ($response->status == 'success') {
                                //attempt to save   data
                                if (!$this->link->save()) {
                                    $err[] = 'Link Save Failed !';
                                }
                            } else {
                                $err[] = $response->msg;
                            }
                        }
                    }
                    if ($isEdit) {
                        $link = $this->link->findById($this->ps);
                        $altLink = '';
                        if (!empty($link['alt_data'])) {
                          $alt_data = json_decode($link['alt_data'], true);
                          if (!empty($alt_data['link'])) {
                            $altLink = $alt_data['link'];
                          }
                        }
                        if (empty($link)) {
                            die('Link not found !');
                        }
                    }
                    $ralinks = $this->link->recentlyAdded();
					$this->header();
                    include (TEMPLATE . "/__new-link.php");
					$this->footer();
                break;
                case 'bulk-import':
					$this->header();
                    include (TEMPLATE . "/bulk-import.php");
					$this->footer();
                break;
                default:
                    if (isset($this->pf)) {
                        if ($this->link->getStatus($this->pf)) {
                            $type = $this->pf;
                        }
                    }
                    if ($type == 'deleted') $isDeleted = 1;
                    $links = $this->link->getAll($type);
					$this->header();
                    include (TEMPLATE . "/links.php");
					$this->footer();
                    break;
                }
                
        }
        /**
         * Ajax Action

         * @return string JSON
         */
        public function ajax() {
            if (!$this->hasAccess) {
                http_response_code(403);
                die('No Access !');
            }
            $err = [];
            $rep = ['success' => false];
            if (isset($_GET['type'])) {
                switch ($_GET['type']) {
                    case 'bulk_insert':
                        $links = (isset($_GET['links']) && !empty($_GET['links'])) ? $_GET['links'] : '';
                        if (!empty($links)) {
                            $links = explode(',', str_replace(' ', '', $links));
                            $response = $this->link->bulkInsert($links, $this->userId);
                            $rep = ['success' => true, 'result' => $response];
                        }
                    break;
                    case 'delete_link':
                        $ids = (isset($_GET['ids']) && !empty($_GET['ids'])) ? $_GET['ids'] : '';
                        $soft = (isset($_GET['soft']) && !empty($_GET['soft'])) ? true : false;
                        if (!empty($ids)) {
                            $ids = array_filter(explode(',', str_replace(' ', '', $ids)));
                            if (!$this->link->delete($ids, $soft)) $err = 1;
                            if (empty($err)) {
                                $rep = ['success' => true];
                            } else {
                                $rep = ['success' => false];
                            }
                        }
                        break;
                    case 'restore_link':
                        $id = (isset($_GET['id']) && !empty($_GET['id'])) ? $_GET['id'] : '';
                        if (!empty($id)) {
                            if (!$this->link->restore($id)) $err = 1;
                            if (empty($err)) {
                                $rep = ['success' => true];
                            } else {
                                $rep = ['success' => false];
                            }
                        }
                        break;
                    case 'save_user':
                        $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : '';
                        $username = (isset($_GET['username']) && !empty($_GET['username'])) ? $_GET['username'] : '';
                        $email = (isset($_GET['email']) && !empty($_GET['email'])) ? $_GET['email'] : '';
                        $password = (isset($_GET['password']) && !empty($_GET['password'])) ? $_GET['password'] : '';
                        $permission = (isset($_GET['permission']) && !empty($_GET['permission'])) ? $_GET['permission'] : '';
                        $isEdit = (isset($_GET['isEdit']) && is_numeric($_GET['isEdit'])) ? $_GET['isEdit'] : 0;
                        if (!empty($username) && (!empty($password) || $isEdit)) {
                            if (!empty($email) && !Main::isValidEmail($email)) {
                                $err[] = 'Invalid Email Address !';
                            }
                            if (!Main::isValidStr($username)) {
                                $err[] = 'Invalid Username !';
                            }
                            if (empty($err)) {
                                $data = ['id' => $id, 'username' => $username, 'email' => $email, 'password' => $password, 'permission' => $permission];
                                $this->user->add($data);
                                $save = $this->user->save();
                                if ($save->status != 'success') {
                                    $err[] = $save->msg;
                                }
                            }
                        } else {
                            $err[] = 'Username and password is required !';
                        }
                        if (empty($err)) {
                            $rep = ['success' => true];
                        } else {
                            $rep = ['success' => false, 'msg' => $err];
                        }
                        break;
                    case 'delete_user':
                        $id = (isset($_GET['id']) && !empty($_GET['id'])) ? $_GET['id'] : '';
                        if (!empty($id)) {
                            if (!$this->user->delete($id)) $err = 1;
                            if (empty($err)) {
                                $rep = ['success' => true];
                            } else {
                                $rep = ['success' => false];
                            }
                        }
                        break;
                    default:
                        // code...
                        break;
                    }
                }
                $this->jsonResponse($rep);
            }
            /**
             * Update settings
             
             * @since v1.1
             */
            protected function _updateSettings($data = []) {
                foreach ($data as $config => $val) {
                    $this->db->where('config', $config);
                    $this->db->update('settings', ['var' => $val]);
                }
            }
            /**
             * Header
             * @since 1.1
             *
             */
            protected function header() {
                include ($this->t(__FUNCTION__));
            }
            /**
             * Header
             * @since 1.1
             *
             */
            protected function footer() {
                include ($this->t(__FUNCTION__));
            }
            /**
             * Get Template
             * @since 1.1
             *
             */
            protected function t($template) {
                if (!file_exists(TEMPLATE . "/$template.php")) die("File ($template.php) is missing in the theme folder.");
                return TEMPLATE . "/$template.php";
            }
            protected function jsonResponse($resp) {
                header("Access-Control-Allow-Origin: *");
                header("Content-Type: applicaton/json; charset=UTF-8");
                http_response_code(200);
                echo json_encode($resp);
                exit;
            }
            /**
             * Get video
             
             * @since v1.1
             * @return array
             */
            protected function getVideo($q, $file, $reloads = 0) {
                $fileData = json_decode($file->data);
                if (empty($fileData->sources) || empty($fileData->cookies)) die("Can not play this video ! ");
                if (!property_exists($fileData->sources, $q)) die("Invalid Video Format ! ");
                $source = $fileData->sources->{$q}->file;
                $cookies = implode("; ", $fileData->cookies);
                $options = array("http" => array("header" => Main::setHeaders($cookies)));
                stream_context_set_default($options);
                $headers = get_headers($source, true);
                if (isset($headers["Location"])) {
                    if (is_array($headers["Location"])) {
                        $headers["Location"] = end($headers["Location"]);
                    }
                    $source = $headers["Location"];
                    $headers = get_headers($source, true);
                }
                $status_code = substr($headers[0], 9, 3);
                if ($reloads == 5) {
                    //link is broken
                    $this->link->broken($file->id);
                }
                if ($status_code == '403' && $reloads < 5) {
                    //attempt to refresh links data
                    $result = $this->link->getDrive($file->driveId);
                    if ($result->status == 'success') {
                        $data = $result->data;
                        $file->data = json_encode($data);
                        $this->link->add(['id' => $file->id, 'data' => $file->data]);
                        $dd = $this->link->save();
                        $reloads+= 1;
                        return $this->getVideo($q, $file, $reloads);
                    } else {
                        die('Can not play this video !');
                    }
                } else {
                    //update broken links
                    if ($this->link->isBroken($file->id)) {
                        $this->link->restore($file->id);
                    }
                }
                return array("link" => $source, "headers" => $headers);
            }
            /**
             * Remake damaged files data
             * @since v1.1
             */
            protected function _remake($file) {
                $result = $this->link->getDrive($file['driveId']);
                if ($result->status == 'success') {
                    $data = $result->data;
                    $file['data'] = json_encode($data);
                    $this->link->add(['id' => $file['id'], 'data' => $file['data']]);
                    $dd = $this->link->save();
                    return $file;
                } else {
                    return FALSE;
                }
            }
            /**
             * Application firewall for protect links
             * @since v1.1
             */
            protected function _firewall() {
                if ($this->config['firewall'] == 1) {
                    $domains = json_decode($this->config['allowed_domains'], true);
                    if (!isset($_SERVER["HTTP_REFERER"])) die('Nothing here... :) ');
                    $referer = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST);
                    if (empty($referer) || !in_array($referer, $domains)) {
                        header('HTTP/1.0 403 Forbidden');
                        die('Nothing here... :) ');
                        exit;
                    }
                }
            }
            /**
             * simple analytics data
             * @since v1.1
             */
            protected function _analytics() {
                $aData = [];
                $total_links = $this->link->getAll();
                $aData['total_links'] = (is_array($total_links)) ? count($total_links) : 0;
                $active_links = $this->link->getAll('active');
                $aData['active_links'] = (is_array($active_links)) ? count($active_links) : 0;
                $deleted_links = $this->link->getAll('deleted');
                $aData['deleted_links'] = (is_array($deleted_links)) ? count($deleted_links) : 0;
                $broken_links = $this->link->getAll('broken');
                $aData['broken_links'] = (is_array($broken_links)) ? count($broken_links) : 0;
                $aData['total_views'] = $this->link->getAllViews();
                $total_users = $this->user->getAll();
                $aData['total_users'] = (is_array($total_users)) ? count($total_users) : 0;
                $aData['mostViewed'] = $this->link->mostViewed();
                $aData['recentlyAdded'] = $this->link->recentlyAdded();
                $aData['num_of_auto_embeds'] = $this->link->getAllAEViews();
                $gdrive_links = $this->link->getAll(NULL, 'GDrive');
                $aData['gdrive_links'] = (is_array($gdrive_links)) ? count($gdrive_links) : 0;
                $gphoto_links = $this->link->getAll(NULL, 'GPhoto');
                $aData['gphoto_links'] = (is_array($gphoto_links)) ? count($gphoto_links) : 0;
                $onedrive_links = $this->link->getAll(NULL, 'OneDrive');
                $aData['onedrive_links'] = (is_array($onedrive_links)) ? count($onedrive_links) : 0;
                $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
                $last_day_this_month = date('Y-m-t');
                $year = date('Y');
                $month = date('m', strtotime(date('Y-m') . " -1 month"));
                $end_date = date('t');
                $lData = [];
                $dataPoint = [];
                $i = 1;
                while ($i <= $end_date) {
                    $i = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $lData[$i] = 0;
                    $i++;
                }
                $links = $this->link->getAll('', '', $first_day_this_month, $last_day_this_month);
                $dataPoints = [];
                foreach ($links as $link) {
                    $date = strtotime($link['created_at']);
                    $date = date('d', $date);
                    if (array_key_exists($date, $lData)) {
                        $lData[$date]+= 1;
                    }
                }
                foreach ($lData as $k => $v) {
                    $dataPointx[] = "{ x: new Date({$year}, {$month}, {$k}), y: {$v}}";
                }
                $this->dataPointx = implode(',', $dataPointx);
                return $aData;
            }
            /**
             * 404 page return
             * @since v1.1
             */
            protected function _404() {
                header('HTTP/1.1 404 Not Found');
                die('404 page not found !');
            }
        }
        //end
