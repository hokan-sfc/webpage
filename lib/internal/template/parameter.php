<?php

class Parameter {
    private $data;
    private $file;

    function __construct($data, $file=NULL) {
        $this->data = $data;
        $this->file = $file;
    }

    function get_string($key) {
        if (!$this->exist($this->data, $key)) {
            return FALSE;
        }
        $value = $this->data[$key];
        if (!is_string($value)) {
            return FALSE;
        }
        return $value;
    }

    function get_array($key) {
        if (!$this->exist($this->data, $key)) {
            return FALSE;
        }
        $value = $this->data[$key];
        if (!is_array($value)) {
            return FALSE;
        }
        return $value;
    }

    function get_file($key) {
        if (is_null($this->file)) {
            return FALSE;
        }
        if (!$this->exist($this->file, $key)) {
            return FALSE;
        }
        $value = $this->file[$key];
        if (!is_array($value) || !isset($value['error'])) {
            return FALSE;
        }
        if ($value['error'] != UPLOAD_ERR_OK) {
            return FALSE;
        }
        return $value;
    }

    private function exist($data, $key) {
        if (!isset($data[$key])) {
            return FALSE;
        }
        return TRUE;
    }
}

?>
