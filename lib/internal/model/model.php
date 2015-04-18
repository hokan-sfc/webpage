<?php

require_once __DIR__.'/../config/config_handler.php';

class Model {
    static private $_pdo;
    private $_table;
    private $_id;
    protected $columns;

    function __construct($table, array $columns=array()) {
        if (is_null(self::$_pdo)) {
            self::$_pdo = new PDO((new Config())->sqlite());
        }
        $this->_table = $table;
        $this->columns = $columns;
    }

    function __destruct() {
        self::$_pdo = NULL;
    }

    function load_by_id($id) {
        if (!is_null($this->_id)) {
            return FALSE;
        }
        $this->_id = $id;
        $data = $this->fetch();
        if (!$data) {
            return FALSE;
        }
        foreach ($data as $k => $v) {
            if ($k !== 'id') {
                $this->columns[$k] = $v;
            }
        }
        return TRUE;
    }

    function reload() {
        if (is_null($this->_id)) {
            return FALSE;
        }
        $data = $this->fetch();
        if (!$data) {
            return FALSE;
        }
        foreach ($this->columns as $k => $v) {
            $this->columns[$k] = $data[$k];
        }
        return TRUE;
    }

    function save() {
        $keys = array_keys($this->columns);
        if (is_null($this->_id)) {
            // 新しくデータを作成する場合
            $columns = implode(',', $keys);
            $values = implode(", :", $keys);
            $stm = "insert into $this->_table ($columns) values (:$values);";
            $sql = self::$_pdo->prepare($stm);
        } else {
            // 既存データを更新する場合
            $bind = function ($k) { return "$k = :$k"; };
            $binds = implode(', ', array_map($bind, $keys));
            $stm = "update $this->_table set $binds where id = :id;";
            $sql = $this->prepare_with_id($stm);
        }
        foreach ($this->columns as $k => $v) {
            if (is_null($v)) {
                $type = PDO::PARAM_NULL;
            } else if (is_string($v)) {
                $type = PDO::PARAM_STR;
            } else {
                $type = PDO::PARAM_INT;
            }
            $sql->bindValue(":$k", $v, $type);
        }
        if (!$sql->execute()) {
            return FALSE;
        }
        if (is_null($this->_id)) {
            $this->_id = self::$_pdo->lastInsertId();
        }
        return $this->reload();
    }

    function destroy() {
        if (!$this->_id) {
            return FALSE;
        }
        $stm = "delete from $this->_table where id = :id;";
        $sql = $this->prepare_with_id($stm);
        $res = $sql->execute();
        $this->_id = NULL;
        return $res;
    }

    private function prepare_with_id($stm) {
        $sql = self::$_pdo->prepare($stm);
        $sql->bindValue(':id', $this->_id, PDO::PARAM_INT);
        return $sql;
    }

    private function fetch() {
        $stm = "select * from $this->_table where id = :id;";
        $sql = $this->prepare_with_id($stm);
        if (!$sql->execute()) {
            return FALSE;
        }
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    protected function pdo() {
        return self::$_pdo;
    }

    protected function id() {
        return $this->_id;
    }

    protected function table() {
        return $this->_table;
    }
}

?>
