<?php

require_once __DIR__.'/../../external/yconnect/autoload.php';

use YConnect\Credential\ClientCredential;
use YConnect\YConnectClient;

class Config {
    private $c;

    function __construct() {
        $this->c = include(dirname(__FILE__).'/config.php');
    }

    function yahoo_client_id() {
        return $this->c['yahoo']['client_id'];
    }

    function yahoo_callback() {
        return $this->c['yahoo']['callback'];
    }

    function google_client_id() {
        return $this->c['google']['client_id'];
    }

    function yahoo_client() {
        $app = $this->c['yahoo']['client_id'];
        $sec = $this->c['yahoo']['client_secret'];
        $cred = new ClientCredential($app, $sec);
        return new YConnectClient($cred);
    }
}

?>
