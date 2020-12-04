<?php
class User extends App {
    protected $_db, $_config;
    protected $_table = 'users';
    protected $_blackListed = ['id'];
    public $obj = [];
    public function __construct($db, $config) {
        $this->_config = $config;
        $this->_db = $db;
        $this->_initProperties();
    }
    public function add($data = []) {
        $id = false;
        $err = '';
        if (!empty($data['id'])) $this->setID($data['id']);
        if (is_array($data['permission'])) {
            $allowedVal = [1, 2, 3];
            $permission = $data['permission'];
            foreach ($permission as $k => $v) {
                if (!in_array($v, $allowedVal)) unset($data['permission'][$k]);
            }
        } else {
            $data['permission'] = [];
        }
        $data['permission'] = json_encode($data['permission']);
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        $this->assign($data);
        return main::createObj(['status' => 'success']);
    }
    public function save() {
        $id = NULL;
        $err = '';
        if (!$this->isExists('username')) {
            $this->_beforeSave();
            $data = $this->_getData();
            if (empty($this->obj['id'])) {
                $id = $this->_db->insert($this->_table, $data);
            } else {
                $this->_db->where('id', $this->obj['id']);
                $id = $this->_db->update($this->_table, $data);
            }
        } else {
            $err = 'Username is already exists !';
        }
        if (empty($err) && empty($id)) $err = 'User Save Failed !';
        if (empty($err)) {
            return main::createObj(['status' => 'success']);
        } else {
            return main::createObj(['status' => 'error', 'msg' => $err]);
        }
    }
    protected function _beforeSave() {
        $objData = [];
        $now = Main::dtNow();
        $objData['last_logged'] = $now;
        if (!isset($this->obj['id'])) {
            $objData['created_at'] = $now;
        }
        $this->assign($objData);
    }
    public function isExists($k = '', $v = '') {
        if (empty($v)) $v = $this->obj[$k];
        $l = $this->get($k, $v);
        $e = 0;
        if ($l !== false) {
            if (!empty($this->obj['id']) && isset($this->obj[$k])) {
                if ($l[$k] != $v) $e = 1;
            } else {
                $e = 1;
            }
        }
        return ($e) ? true : false;
    }
    public function get($k = '', $v = '') {
        if (!empty($k) && !empty($v)) {
            $this->_db->where($k, $v);
            $link = $this->_db->getOne($this->_table);
            if ($this->_db->count > 0) $s = 1;
        }
        return (!empty($s)) ? $link : false;
    }
    public function getAll() {
        $this->_db->where("deleted", 0);
        $users = $this->_db->get($this->_table);
        if ($this->_db->count > 0) {
            return $users;
        } else {
            return [];
        }
    }
    public function delete($id) {
        $err = 0;
        if (!empty($id)) {
            $this->_db->where('id', $id);
            if (!$this->_db->update($this->_table, ['deleted' => 1])) $err = 1;
        } else {
            $err = 1;
        }
        return (empty($err)) ? true : false;
    }
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
    public function assign($data) {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if (array_key_exists($k, $this->obj)) {
                    $this->obj[$k] = $v;
                }
            }
        }
    }
    protected function setID($id) {
        $this->_db->where("id", $id);
        $link = $this->_db->getOne($this->_table);
        if ($this->_db->count > 0) {
            $this->obj['id'] = $link['id'];
            return true;
        }
        return false;
    }
    protected function _initProperties() {
        $dbColumns = $this->_db->rawQuery("DESCRIBE " . $this->_table);
        if (!empty($dbColumns)) {
            foreach ($dbColumns as $col) {
                $this->obj[$col['Field']] = NULL;
            }
        }
    }
}
