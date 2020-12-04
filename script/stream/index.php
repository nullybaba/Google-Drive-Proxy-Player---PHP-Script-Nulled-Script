<?php
require_once ('../app/config.php');
require_once ('../app/lib/curl.php');
require_once ('../app/inc/Main.class.php');

$conn = mysqli_connect($dbinfo['host'], $dbinfo['username'], $dbinfo['password'], $dbinfo['db']);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$config = getConfig();
if ($config == false) die('Something went wrong!');
if (isset($_GET['t']) && !empty($_GET['t'])) {
    switch ($_GET['t']) {
        case 'gdrive':
            if ($config['firewall'] == 1) {
                $domains = json_decode($config['allowed_domains'], true);
                if (!isset($_SERVER["HTTP_REFERER"])) {
                    include ('lol.php');
                    exit;
                };
                $referer = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST);
                if (empty($referer) || !in_array($referer, $domains)) {
                    header('HTTP/1.0 403 Forbidden');
                    exit;
                }
            }
            if (isset($_GET['id'])) {
                $slug = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
                if ($file = findBySlug($slug)) {
                    $q = (isset($_GET['q']) && !empty($_GET['q'])) ? $_GET['q'] : '360';
                    $source = getVideo($q, Main::createObj($file));
                    $headers = $source["headers"];
                    header($headers[0]);
                    header("Devloped-By: codyseller");
                    if (http_response_code() != "403") {
                        if (isset($headers["Content-Type"])) {
                            header("Content-Type: " . $headers["Content-Type"]);
                        }
                        if (isset($headers["Content-Length"])) {
                            header("Content-Length: " . $headers["Content-Length"]);
                        }
                        if (isset($headers["Accept-Ranges"])) {
                            header("Accept-Ranges: " . $headers["Accept-Ranges"]);
                        }
                        if (isset($headers["Content-Range"])) {
                            header("Content-Range: " . $headers["Content-Range"]);
                        }
                        $fp = fopen($source["link"], "rb");
                        while (!feof($fp)) {
                            set_time_limit(0);
                            echo fread($fp, 1024 * 1024 * 5);
                            flush();
                            ob_flush();
                        }
                        fclose($fp);
                    } else {
                        die('something went wrong !');
                    }
                } else {
                    die('Video not found !');
                }
            } else {
                die('Insert video id !');
            }
        break;
        case 'gphoto':
        case 'onedrive':
            $link = base64_decode($_GET['token']);
            header('location: ' . $link);
        break;
        default:
            header('HTTP/1.1 404 Not Found');
            die('404 page not found !');
        break;
    }
}
function findBySlug($slug) {
    global $conn;
    $sql = "SELECT * FROM links WHERE slug = '{$slug}' AND status != 2  LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row;
    } else {
        return false;
    }
}
function getVideo($q, $file, $reloads = 0) {
    global $conn;
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
        broken($file->id);
    }
    if ($status_code == '403' && $reloads < 5) {
        $result = getDrive($file->driveId);
        if ($result->success) {
            $data = $result->data;
            $file->data = json_encode($data);
            $sql = "UPDATE links SET data = '{$file->data}' WHERE id={$file->id} LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $reloads+= 1;
            return getVideo($q, $file, $reloads);
        } else {
            $altData = $file->alt_data;
            if (!empty($altData)) {
               $altData = json_decode($file->alt_data, true);
               if (!empty($altData['link'])) {
                 $altlink = $altData['link'];
                 if ($altData['type'] == 'GDrive') {
                   $gId = Main::getDriveId($altlink);
                   $file->driveId = $gId;
                   $reloads+= 1;
                   return getVideo($q, $file, $reloads);
                 }else{
                   header('location: ' . $altlink);
                   die();
                 }

               }
            }

            die('Can not play this video !');
        }
    }
    return array("link" => $source, "headers" => $headers);
}
function broken($file_id) {
    global $conn;
    $sql = "UPDATE links SET status = 1 WHERE id={$file_id} LIMIT 1";
    mysqli_query($conn, $sql);
    return false;
}
function isBlocked() {
    global $conn;
    $sql = "SELECT * FROM settings WHERE config = 'is_blocked' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row['var'] == 1) return true;
    }
    return false;
}
function getDrive($id) {
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
        $result = ['success' => false, 'error' => $error];
    } else {
        $result = ['success' => true, 'data' => $data, 'title' => $title];
    }
    return Main::createObj($result);
}
function getConfig() {
    global $conn;
    $config = NULL;
    $sql = "SELECT * FROM settings WHERE config != 'vastAds' AND config != 'popAds' ";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $config[$row['config']] = $row['var'];
        }
        return $config;
    }
    return false;
}
mysqli_close($conn);
