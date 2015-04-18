<?php

require_once __DIR__.'/../../external/yconnect/autoload.php';

use YConnect\Credential\ClientCredential;
use YConnect\YConnectClient;

class Config {
    private $c;
    private $env;

    function __construct() {
        $this->c = include(__DIR__.'/config.php');
        $this->env = getenv('PHP_ENV');
        if (!$this->env) {
            $this->env = 'default';
        }
    }

    private function db_file() {
        return $this->c[$this->env]['db']['file'];
    }

    function sqlite() {
        if ($this->env === 'test') {
            $path = '.'.$this->db_file();
        } else {
            $path = $_SERVER['DOCUMENT_ROOT'].$this->db_file();
        }
        return "sqlite:$path";
    }

    function yahoo_client_id() {
        return $this->c[$this->env]['yahoo']['client_id'];
    }

    function yahoo_callback() {
        return $this->c[$this->env]['yahoo']['callback'];
    }

    function google_client_id() {
        return $this->c[$this->env]['google']['client_id'];
    }

    function yahoo_client() {
        $app = $this->c[$this->env]['yahoo']['client_id'];
        $sec = $this->c[$this->env]['yahoo']['client_secret'];
        $cred = new ClientCredential($app, $sec);
        return new YConnectClient($cred);
    }
}

?>
