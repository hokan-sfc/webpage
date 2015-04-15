<?php

require_once __DIR__.'/../config/config_handler.php';

class Model {
    private $pdo;
    private $table;
    private $columns;
    private $id;

    function __construct($table, $columns) {
        $this->pdo = new PDO((new Config())->sqlite());
        $this->table = $table;
        $this->columns = $columns;
    }

    function __destruct() {
        $this->pdo = NULL;
    }

    function loadByID($id) {
        if ($this->id) {
            return FALSE;
        }
        $this->id = $id;
        $data = $this->fetch();
        foreach ($data as $k => $v) {
            if ($k != 'id') {
                $this->columns[$k] = $v;
            }
        }
        return TRUE;
    }

    function reload() {
        if (!$this->id) {
            return FALSE;
        }
        $data = $this->fetch();
        foreach ($this->columns as $k => $v) {
            $this->columns[$k] = $data[$k];
        }
        return TRUE;
    }

    function save() {
        $keys = array_keys($this->columns);
        if ($this->id) {
            $columns = implode(',', $keys);
            $values = implode(", :", $keys);
            $stm = "insert into $this->table ($columns) values (:$values);";
        } else {
            $bind = function ($k) { return "$k = :$k"; };
            $binds = implode(', ', array_map($bind, $keys));
            $stm = "update $this->table set $binds whre id = :id;";
        }
        $sql = $this->prepare_with_id($stm);
        foreach ($this->columns as $k => $v) {
            // PDO::PARAM_INTの指定時でも文字列型はそのまま扱われる
            $sql->bindValue(":$k", $v, PDO::PARAM_INT);
        }
        if (!$sql->execute()) {
            return FALSE;
        }
        $this->id = $this->pdo->lastInsertId();
        return $this->reload();
    }

    function destroy() {
        if (!$this->id) {
            return FALSE;
        }
        $stm = "delete from $this->table where id = :id;";
        $sql = $this->prepare_with_id($stm);
        return $sql->execute();
    }

    private function prepare_with_id($stm) {
        $sql = $this->pdo->prepare($stm);
        $sql->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $sql;
    }

    private function fetch() {
        $stm = "select * from $this->table where id = :id;";
        $sql = $this->prepare_with_id($stm);
        if (!$sql->execute()) {
            return FALSE;
        }
        return $sql->fetch(PDO::FETCH_ASSOC);
    }
}

?>
