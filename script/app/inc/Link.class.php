<?php
class Link extends App {
    protected $_db, $_config;
    protected $_table = 'links';
    protected $_blackListed = ['id'];
    public $obj = [];
    public function __construct($db, $config) {
        $this->_config = $config;
        $this->_db = $db;
        $this->_initProperties();
    }
    /**
     * Add data to obj
     * @author CodySeller <http://codyseller.com>
     * @since v1.1
     * @return  object
     */
    public function bulkInsert($data, $userId) {
        // getVideoInfo
        $fLinks = [];
        $sl = 0;
        if (is_array($data)) {
            foreach ($data as $v) {
                if (!empty(trim($v))) {
                    if (Main::isDrive($v)) {
                        $gId = Main::getDriveId($v);
                        $vInfo = $this->getVideoInfo($gId);
                        if (isset($vInfo['fileName'])) {
                            $thumb = $this->getThumb($gId);
                            $title = !empty($vInfo['fileName']) ? $vInfo['fileName'] : 'Unknown Title';
                            $objData = ['title' => $title, 'driveId' => $gId, 'type' => 'GDrive', 'thumb' => $thumb, 'user_id' => $userId];
                            $this->assign($objData);
                            $this->save();
                            $sl+= 1;
                        } else {
                            $fLinks[] = $v;
                        }
                    } else {
                        $fLinks[] = $v;
                    }
                }
            }
        }
        return ['success' => $sl, 'faild' => count($fLinks), 'fail_links' => $fLinks];
    }
    public function add($data = []) {
        $id = false;
        $err = '';
        if (!empty($data['id'])) $this->setID($data['id']);
        $altData = (isset($data['alt_data'])) ? $data['alt_data'] : [];

        if (isset($data['gurl']) && !empty($data['gurl'])) {
            $gurl = $data['gurl'];
            if (Main::isDrive($gurl)) {
                $title = $data['title'];
                $gId = Main::getDriveId($gurl);
                if ($this->_config['stream'] == 1) {
                    $drive = $this->getDrive($gId);
                    if ($drive->status == 'success') {
                        //if title is empty set default file title
                        if (empty($title)) $title = $drive->title;
                        $thumb = $this->getThumb($gId);

                        // json encode gdrive file data
                        $gdata = json_encode($drive->data);
                        $objData = ['title' => $title, 'driveId' => $gId, 'data' => $gdata, 'alt_data' => $altData,  'type' => 'GDrive', 'thumb' => $thumb, 'subtitles' => $data['subtitles'], 'user_id' => $data['user_id']];
                        $this->assign($objData);
                    } else {
                        $err = 'Somthing Went Wrong !';
                    }
                } else {
                    if (empty($title)) $title = 'Unknown';
                    $objData = ['title' => $title, 'driveId' => $gId, 'type' => 'GDrive', 'data' => NULL, 'subtitles' => $data['subtitles'], 'user_id' => $data['user_id']];
                    $this->assign($objData);
                }
            } elseif (Main::isPhoto($gurl) || Main::isOneDrive($gurl)) {
                //gogole photos
                if (empty(trim($data['title']))) $data['title'] = 'Unknown Title';
                if (Main::isPhoto($gurl)) {
                    $type = 'GPhoto';
                } else {
                    $type = 'OneDrive';
                }
                $objData = ['title' => $data['title'], 'type' => $type, 'data' => $gurl,'alt_data' => $altData,  'subtitles' => $data['subtitles'], 'user_id' => $data['user_id']];
                $this->assign($objData);
            } else {
                $err = 'Invalid URL. This URL not supported !';
            }
        } elseif (isset($data['data']) && !empty($data['data'])) {
            $this->assign(['data' => $data['data']]);
        } else {
            $err = 'Drive URL Can Not Empty !';
        }
        if (empty($err)) {
            return main::createObj(['status' => 'success']);
        } else {
            return main::createObj(['status' => 'error', 'msg' => $err]);
        }
    }
    /**
     * get gdrive data
     * @author CodySeller <https://codyseller.com>
     * @since v1.1
     * @return  object
     */
    protected function getDrive($id) {
        $url = "https://drive.google.com/e/get_video_info?docid=" . $id;
        $sources = [];
        $error = '';
        $data = '';
        $result = null;
        $cCtsr = Main::getSources($id);
        if ($cCtsr !== false) {
            $title = $cCtsr['title'];
            $data = $cCtsr['data'];
        } else {
            $error = 'Something went wrong !';
        }
        if (!empty($error) || empty($data['sources'])) {
            $result = ['status' => 'error', 'error' => $error];
        } else {
            $result = ['status' => 'success', 'data' => $data, 'title' => $title];
        }
        return Main::createObj($result);
    }
    /**
     * Save currect object
     * @author CodySeller <http://codyseller.com>
     * @since v1.1
     * @return  string|boolean
     */
    public function save() {
        $id = NULL;
        if (!empty($this->obj['driveId']) || !empty($this->obj['data'])) {
            $this->_beforeSave();
            $data = $this->_getData();
            if (empty($this->obj['id'])) {
                $id = $this->_db->insert($this->_table, $data);
            } else {
                $this->_db->where('id', $this->obj['id']);
                $id = $this->_db->update($this->_table, $data);
            }
        }
        return (!empty($id)) ? $id : false;
    }
    /**
     * Add view
     * @since v1.1
     */
    public function addView($id) {
        $err = 0;
        if (!empty($id)) {
            $this->_db->where('id', $id);
            if (!$this->_db->update($this->_table, ['views' => $this->_db->inc(1) ])) $err = 1;
        } else {
            $err = 1;
        }
        return (empty($err)) ? true : false;
    }
    /**
     * Add auto embed view
     * @since v1.1
     */
    public function addAEView() {
        $this->_db->where('config', 'ae_views');
        $this->_db->update('settings', ['var' => $this->_db->inc(1) ]);
    }
    /**
     * Delete currect object
     * @author CodySeller <http://codyseller.com>
     * @since v1.1
     * @return  boolean
     */
    public function delete($id, $soft = true) {
        $err = 0;
        if (is_array($id)) {
            $ids = implode(',', $id);
            if (!$soft) {
                $sql = "DELETE FROM " . $this->_table . " WHERE id in ({$ids})";
            } else {
                $sql = "UPDATE " . $this->_table . " SET status = 2 WHERE id  in ({$ids}) ";
            }
            $this->_db->rawQuery($sql);
        } else {
            $this->_db->where('id', $id);
            if (!$this->_db->delete($this->_table)) $err = 1;
        }
        return (empty($err)) ? true : false;
    }
    /**
     * restore currect object
     * @author CodySeller <http://codyseller.com>
     * @since v1.1
     * @return  boolean
     */
    public function restore($id) {
        $err = 0;
        if (!empty($id)) {
            $this->_db->where('id', $id);
            if (!$this->_db->update($this->_table, ['status' => 0])) $err = 1;
        } else {
            $err = 1;
        }
        return (empty($err)) ? true : false;
    }
    /**
     * Find link by slug
     * @since v1.1
     * @return  array|boolean
     */
    public function findBySlug($slug) {
        $this->_db->where("slug", $slug);
        $this->_db->where("status", 2, "!=");
        $link = $this->_db->getOne($this->_table);
        if ($this->_db->count > 0) {
            return $link;
        } else {
            return false;
        }
    }
    /**
     * Setup more info before save
     * @since v1.1
     */
    protected function _beforeSave() {
        $objData = [];
        $now = Main::dtNow();
        $objData['updated_at'] = $now;
        if (!isset($this->obj['id'])) {
            $objData['created_at'] = $now;
            $objData['slug'] = Main::random();
        }
        $this->assign($objData);
    }
    /**
     * Get object data
     * @since v1.1
     * @return  array
     */
    protected function _getData() {
        $data = $this->obj;
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if (in_array($k, $this->_blackListed)) unset($data[$k]);
                if (empty($v)) unset($data[$k]);
            }
        }
        return $data;
    }
    /**
     * initial object properties
     * @since v1.1
     */
    protected function _initProperties() {
        $dbColumns = $this->_db->rawQuery("DESCRIBE " . $this->_table);
        if (!empty($dbColumns)) {
            foreach ($dbColumns as $col) {
                $this->obj[$col['Field']] = NULL;
            }
        }
    }
    /**
     * assign data to object
     * @since v1.1
     */
    public function assign($data) {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if (array_key_exists($k, $this->obj)) {
                    $this->obj[$k] = $v;
                }
            }
        }
    }
    /**
     * set obj id
     * @since v1.1
     */
    protected function setID($id) {
        $this->_db->where("id", $id);
        $link = $this->_db->getOne($this->_table);
        if ($this->_db->count > 0) {
            $this->obj['id'] = $link['id'];
            return true;
        }
        return false;
    }
    /**
     * broken links
     * @since v1.1
     */
    public function broken($id) {
        $err = 0;
        if (!empty($id)) {
            $this->_db->where('id', $id);
            if (!$this->_db->update($this->_table, ['status' => 1])) $err = 1;
        } else {
            $err = 1;
        }
        return (empty($err)) ? true : false;
    }
    /**
     * check broken links
     * @since v1.1
     */
    public function isBroken($id) {
        $this->_db->where("id", $id);
        $link = $this->_db->getOne($this->_table);
        if ($this->_db->count > 0) {
            if ($link['status'] == 1) return true;
        }
        return false;
    }
    /**
     * Get all links
     * @since v1.1
     */
    public function getAll($t = '', $s = '', $sd = '', $ed = '') {
        if (!empty($t)) {
            $status = self::getStatus($t);
            if ($status !== false) $this->_db->where("l.status", $status);
        }
        if (!empty($s)) {
            $this->_db->where("l.type", $s);
        }
        if (!empty($sd) && !empty($ed)) {
            if ($sd != $ed) {
                $this->_db->where('l.created_at', Array($sd, $ed), 'BETWEEN');
            } else {
                $dt2 = strtotime("1 day", strtotime($ed));
                $dt2 = date("Y-m-d", $ed);
                $this->_db->where('l.created_at', Array($sd, $ed), 'BETWEEN');
            }
        }
        $this->_db->join("links l", "u.id=l.user_id", "INNER");
        $links = $this->_db->get("users u", NULL, "l.*, u.username");
        if ($this->_db->count > 0) {
            return $links;
        } else {
            return [];
        }
    }
    public function getStatus($st) {
        $status = ['active' => 0, 'broken' => 1, 'deleted' => 2];
        if (array_key_exists($st, $status)) {
            return $status[$st];
        }
        return false;
    }
    /**
     * find by id
     * @since v1.1
     */
    public function findById($id) {
        $this->_db->where("id", $id);
        $this->_db->where("status", 2, "!=");
        $link = $this->_db->getOne($this->_table);
        if ($this->_db->count > 0) {
            return $link;
        } else {
            return false;
        }
    }
    /**
     * get all views
     * @since v1.1
     */
    public function getAllAEViews() {
        $this->_db->where("config", 'ae_views');
        $ae_views = $this->_db->getOne('settings');
        return isset($ae_views['var']) ? $ae_views['var'] : 0;
    }
    public function getAllViews() {
        $stats = $this->_db->getOne($this->_table, "sum(views)");
        return (isset($stats['sum(views)'])) ? $stats['sum(views)'] : 0;
    }
    public function mostViewed() {
        $this->_db->join("links l", "u.id=l.user_id", "INNER");
        $this->_db->where("l.status", 2, "!=");
        $this->_db->orderBy("l.views", "desc");
        $results = $this->_db->get("users u", 10, "l.*, u.username");
        if ($this->_db->count > 0) {
            return $results;
        } else {
            return [];
        }
    }
    public function recentlyAdded() {
        $this->_db->join("links l", "u.id=l.user_id", "INNER");
        $this->_db->where("l.status", 2, "!=");
        $this->_db->orderBy("l.created_at", "desc");
        $results = $this->_db->get("users u", 10, "l.*, u.username");
        if ($this->_db->count > 0) {
            return $results;
        } else {
            return [];
        }
    }
    function getThumb($id) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_URL, "https://drive.google.com/thumbnail?sz=w1280-h720-n&id=" . $id);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "HEAD");
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $result = curl_exec($ch);
        $info = Main::createObj(curl_getinfo($ch));
        $image = '';
        if ($info->http_code == "200") {
            $image = $info->url;
        }
        curl_close($ch);
        return $image ? : '';
    }
    function getVideoInfo($google_id) {
        $ch = curl_init("https://drive.google.com/uc?id=$google_id&authuser=0&export=download");
        curl_setopt_array($ch, array(CURLOPT_CUSTOMREQUEST => 'POST', CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_POSTFIELDS => [], CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => 'gzip,deflate', CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4, CURLOPT_HTTPHEADER => ['accept-encoding: gzip, deflate, br', 'content-length: 0', 'content-type: application/x-www-form-urlencoded;charset=UTF-8', 'origin: https://drive.google.com', 'referer: https://drive.google.com/drive/my-drive', 'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 'x-drive-first-party: DriveWebUi', 'x-json-requested: true']));
        $response = curl_exec($ch);
        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($response_code == '200') { // Jika response status OK
            $object = json_decode(str_replace(')]}\'', '', $response), true);
            return $object;
        }
        return false;
    }
}
// end
