<?php
class Main {
    protected static $title = "";
    protected static $description = "";
    protected static $url = "";
    private static $config = [];
    private static $session = [];
    /**
     * Generate URL
     * @return url
     * @since v1.1
     */
    public static function url() {
        if (empty(self::$url)) {
            return self::$config["url"];
        } else {
            return self::$url;
        }
    }
    /**
     * Set meta info
     * @since v1.1
     */
    public static function set($meta, $value) {
        if (!empty($value)) {
            self::$$meta = $value;
        }
    }
    /**
     * Clean a string
     * @param data
     * @return cleaned string
     */
    public static function clean($data) {
        // Fix &entity\n;
        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);
        // we are done...
        return $data;
    }
    /**
     * Validate URLs
     * @since 1.1
     *
     */
    public static function is_url($url) {
        if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url) && filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }
        return false;
    }
    /**
     * Redirect function
     * @param url/path (not including base), message and header code
     * @since 1.1
     */
    public static function redirect($url = '', $message = array(), $header = "", $fullurl = FALSE) {
        if (!empty($message)) {
            $_SESSION["msg"] = self::clean("{$message[0]}::{$message[1]}", 2);
        }
        switch ($header) {
            case '301':
                header('HTTP/1.1 301 Moved Permanently');
            break;
            case '404':
                header('HTTP/1.1 404 Not Found');
            break;
            case '503':
                header('HTTP/1.1 503 Service Temporarily Unavailable');
                header('Status: 503 Service Temporarily Unavailable');
                header('Retry-After: 60');
            break;
        }
        if ($fullurl) {
            header("Location: $url");
            exit;
        }
        header("Location: " . PROOT . "/$url");
        exit;
    }
    /**
     * Generated CSRF Token
     * @return token
     * @since v1.1
     */
    public static function csrf_token($form = FALSE, $echo = TRUE) {
        if ($form && $echo && isset($_SESSION["CSRF"])) return "<input type='hidden' name='token' value='{$_SESSION["CSRF"]}' />";
        if ($echo && isset($_SESSION["CSRF"])) return $_SESSION["CSRF"];
        $token = self::encode("csrf_token" . rand(0, 1000000) . time() . uniqid(), "SHA1");
        $_SESSION["CSRF"] = $token;
        if ($form) return "<input type='hidden' name='token' value='$token' />";
        return $token;
    }
    /**
     * Validate CSRF Token
     * @param token
     * @since v1.1
     */
    public static function validate_csrf_token($token, $redirect = "") {
        if (isset($_SESSION["CSRF"]) && ($_SESSION["CSRF"] == trim($token))) {
            unset($_SESSION["CSRF"]);
            return TRUE;
        }
        if (!empty($redirect)) self::redirect($redirect, array("error", e("The CSRF token is not valid. Please try again.")));
        return FALSE;
    }
    /**
     * Validate Gdrive url
     * @param url
     * @since v1.1
     */
    public static function isDrive($url) {
        if (strpos($url, 'drive.google.com/file/d/') !== false) {
            $gId = self::getDriveId($url);
        }
        return (!empty($gId)) ? true : false;
    }
    /**
     * Get curl header response
     * @since v1.3
     */
    public static function getHeaderResponse($response) {
        $headers = array();
        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
        foreach (explode("\r\n", $header_text) as $i => $line) if ($i === 0) $headers['http_code'] = $line;
        else {
            list($key, $value) = explode(': ', $line);
            $headers[$key] = $value;
        }
        return $headers;
    }
    /**
     * get source site
     * @param url
     * @since v1.3
     */
    public static function getSourceSite($url) {
        if (self::isDrive($url)) {
            return 'GDrive';
        } elseif (self::isPhoto($url)) {
            return 'GPhoto';
        } elseif (self::isOneDrive($url)) {
            return 'OneDrive';
        } else {
            return false;
        }
    }
    /**
     * check google photo url
     * @since v1.3
     */
    public static function isPhoto($url) {
        //only allowed short url
        if (strpos($url, 'photos.app.goo.gl') !== false) {
            return true;
        }
        return false;
    }
    /**
     * check one drive url
     * @since v1.3
     */
    public static function isOneDrive($url) {
        if (strpos($url, '1drv.ms') !== false || strpos($url, 'buwung-my.sharepoint.com') !== false) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * get full url of gphotos
     * @since v1.3
     */
    public static function getFullURL($srtUrl) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $srtUrl);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Must be set to true so that PHP follows any "Location:" header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $a = curl_exec($ch); // $a will contain all headers
        $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); // This is what you need, it will return you the last effective URL
        $photoid = self::fetch_value(file_get_contents($url), '[null,[["', '",');
        $substr = '?key';
        $fullurl = '/photo/' . $photoid;
        $gpurl = str_replace($substr, $fullurl . $substr, $url);
        return $gpurl;
    }
    /**
     * get str between
     * @since v1.3
     */
    public static function fetch_value($str, $find_start = '', $find_end = '') {
        if ($find_start == '') {
            return '';
        }
        $start = strpos($str, $find_start);
        if ($start === false) {
            return '';
        }
        $length = strlen($find_start);
        $substr = substr($str, $start + $length);
        if ($find_end == '') {
            return $substr;
        }
        $end = strpos($substr, $find_end);
        if ($end === false) {
            return $substr;
        }
        return substr($substr, 0, $end);
    }
    /**
     * get one drive download url
     * @since v1.3
     */
    public static function getOneDrive($link) {
        if (strpos($link, 'buwung-my.sharepoint.com') !== false) {
            $link = $link . '&download=1';
        } else {
            if (filter_var($link, FILTER_VALIDATE_URL) !== FALSE && strpos($link, "1drv.ms") !== false) {
                $link = strtok($link, "?");
                $link = @file_get_contents(str_replace('?txt', '', str_replace('1drv.ms', '1drv.ws', $link)) . '?txt');
            }
        }
        // getOneDrive
        if (!empty($link)) {
            $s = '{"file": "' . self::getStreamURI() . self::encrypt($link) . '&t=onedrive' . '", "type": "video\/mp4", "label": "HD"}';
        } else {
            $s = '{"file": "undefinded", "type": "video\/mp4", "label": "HD"}';
        }
        return $s;
    }
    public static function cleanStr($str) {
        return htmlentities(trim($str), ENT_QUOTES, 'UTF-8');
    }
    public static function unsanitized($str) {
        return html_entity_decode($str, ENT_QUOTES, 'UTF-8');
    }
    /**
     * get stream uri
     * @since v1.3
     */
    public static function getStreamURI() {
        return PROOT . '/stream/?token=';
    }
    public static function get_string_between($string, $start, $end) {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini+= strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    /**
     * Get drive id
     * @param url
     * @since v1.1
     */
    public static function getDriveId($url) {
        $path = explode('/', parse_url($url) ['path']);
        return (isset($path[3]) && !empty($path[3])) ? $path[3] : '';
    }
    public static function parse($str) {
        parse_str($str, $parse);
        return self::createObj($parse);
    }
    public static function dtNow() {
        $dt = new DateTime("now", new DateTimeZone(self::$config['timezone']));
        return $dt->format('Y-m-d H:i:s');
    }
    public static function random($length = 15) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0;$i < $length;$i++) {
            $randomString.= $characters[rand(0, $charactersLength - 1) ];
        }
        return $randomString;
    }
    public static function createObj($array) {
        return is_array($array) ? json_decode(json_encode($array)) : $array;
    }
    /**
     * curl request
     * @author CodySeller <https://codyseller.com>
     * @since v1.0
     * @return  object
     */
    public static function curl($url, $cookie = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, $cookie);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        $cookies = [];
        if ($cookie === true) {
            $header = substr($result, 0, $info["header_size"]);
            $result = substr($result, $info["header_size"]);
            preg_match_all("/^Set-Cookie:\\s*([^=]+)=([^;]+)/mi", $header, $cookie);
            foreach ($cookie[0] as $i => $val) {
                $cookies[] = str_replace('set-cookie: ', '', $val);
            }
        }
        $ecode = '';
        if (empty($result) || $info["http_code"] != "200") {
            if ($info["http_code"] == "200") {
                $error = "cURL Error (" . curl_errno($ch) . "): " . (curl_error($ch) ? : "Unknown");
            } else {
                $error = "Error Occurred (" . $info["http_code"] . ")";
                $ecode = '12100';
            }
        }
        curl_close($ch);
        if (empty($error)) {
            $res = ["status" => "success", "contents" => $result, "cookies" => $cookies];
        } else {
            $res = ["status" => "error", "e_code" => $ecode, "msg" => $error];
        }
        return self::createObj($res);
    }
    public static function dateFormat($date) {
        return date("j-M-Y", strtotime($date));
    }
    public static function setHeaders($cookies) {
        $headers = (!empty($cookies)) ? array("Cookie: " . $cookies) : $headers = array();
        if (isset($_SERVER["HTTP_RANGE"])) $headers[] = "Range: " . $_SERVER["HTTP_RANGE"];
        return $headers;
    }
    public static function makeVideoFile($file, $cdf) {
        if (!empty($file['data']) && $cdf) {
            $qulities = self::getQulities($file['data']);
            $slug = $file['slug'];
            $links = [];
            foreach ($qulities as $q) {
                $f = PROOT . "/stream/?id={$slug}&q={$q}&t=gdrive";
                $links[] = '{"label":"' . $q . 'p","type":"video\/mp4","file":"' . $f . '"}';
            }
            $links = '[' . implode(',', $links) . ']';
            return $links;
        } else {
            $apiurl = "https://www.googleapis.com/drive/v3/files/{$file['driveId']}?alt=media&key=AIzaSyBFHimHWDyLOtcNJjA268KwRLhsBuckUxc";
            return '[{"label":"HD","type":"video\/mp4","file":"' . $apiurl . '"}]';
        }
    }
    public static function getL($gid) {
        return "https://www.googleapis.com/drive/v3/files/{$gid}?alt=media&key=AIzaSyBFHimHWDyLOtcNJjA268KwRLhsBuckUxc";
    }
    public static function getGPhotos($link) {
        $curl = new cURL();
        $getSource = $curl->get($link);
        $checkLink = preg_match('/photos.google.com\/share\/.*\/photo\/.*/', $link, $match);
        if ($checkLink) {
            $deSource = rawurldecode($getSource);
            preg_match_all('/https:\/\/(.*?)=m(22|18|37)/', $deSource, $matchSource);
            foreach ($matchSource[2] as $key => $value) {
                switch ($value) {
                    case '37':
                        $s[1080] = '{"file": "https://' . $matchSource[1][0] . '=m37", "type": "video\/mp4", "label": "1080p"}';
                    break;
                    case '22':
                        $s[720] = '{"file": "https://' . $matchSource[1][0] . '=m22", "type": "video\/mp4", "label": "720p"}';
                    break;
                    case '18':
                        $s[360] = '{"file": "https://' . $matchSource[1][0] . '=m18", "type": "video\/mp4", "label": "360p"}';
                    break;
                }
            }
            krsort($s);
            $enJson = implode(',', $s);
            $sources = '[' . $enJson . ']';
            $checkSource = preg_match('/\[\]/', $sources, $match);
            if ($checkSource) {
                $sources = '[{"label":"undefined","type":"video\/mp4","file":"undefined"}]';
            }
        } else {
            preg_match('/<meta property="og:image" content="(.*?)\=.*">/', $getSource, $matchLink);
            $f1080p = trim($matchLink[1] . '=m37');
            $f720p = trim($matchLink[1] . '=m22');
            $f360p = trim($matchLink[1] . '=m18');
            if ($curl->checkCode($f1080p) != 404) {
                $sources = '[{"label":"1080p","type":"video\/mp4","file":"' . self::getStreamURI() . self::encrypt($f1080p) . '&t=gphoto' . '"}, {"label":"720p","type":"video\/mp4","file":"' . self::getStreamURI() . self::encrypt($f720p) . '&t=gphoto' . '"}, {"label":"360p","type":"video\/mp4","file":"' . self::getStreamURI() . self::encrypt($f360p) . '&t=gphoto' . '"}]';
            } else if ($curl->checkCode($f720p) != 404) {
                $sources = '[{"label":"720p","type":"video\/mp4","file":"' . self::getStreamURI() . self::encrypt($f720p) . '&t=gphoto' . '"}, {"label":"360p","type":"video\/mp4","file":"' . self::getStreamURI() . self::encrypt($f360p) . '&t=gphoto' . '"}]';
            } else if ($curl->checkCode($f360p) != 404) {
                $sources = '[{"label":"360p","type":"video\/mp4","file":"' . self::getStreamURI() . self::encrypt($f360p) . '&t=gphoto' . '"}]';
            } else $sources = '[{"label":"undefined","type":"video\/mp4","file":"undefined"}]';
        }
        $sources = str_replace('lh3.googleusercontent.com', '3.bp.blogspot.com', $sources);

        return $sources;
    }
    public static function encrypt($plainText) {
        return base64_encode($plainText);
    }
    public static function decrypt($encryptedTextBase64) {
        return base64_decode($encryptedTextBase64);
    }
    public static function getSources($gid) {
      /*
        hahahah are you see to share this script on nulled forums ?
        yes yes you can try it and see what happend. everything in my hand.
        good luck :)
      */
        $results = self::curl('http://prxy.codyseller.com/?id=' . $gid .'&host=' . self::getHost() . '&apikey=' . APIKEY );
        if ($results->status == 'success') {
            return json_decode($results->contents, true);
        } else {
            return false;
        }
    }
    public static function getQulities($data) {
        $data = json_decode($data, true);
        $q = array_keys($data['sources']);
        return $q;
    }
    public static function getHost() {
        if (isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } else {
            $host = $_SERVER['SERVER_NAME'];
        }
        return trim($host);
    }
    public static function getDomain() {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . self::getHost();
    }
    public static function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    public static function isValidStr($str) {
        return preg_match("/^[a-zA-Z-' ]*$/", $str);
    }
    public static function getTimeZoneList() {
        return DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    }
    public static function upload($name, $temp_name) {
        $filename = strtolower(str_replace(' ', '_', $name));
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "ico" => "image/ico", "png" => "image/png", "gif" => "image/gif");
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (array_key_exists($ext, $allowed)) {
            move_uploaded_file($temp_name, ROOT . "/uploads/" . $filename);
            return $filename;
        }
        return false;
    }
}
// adas
