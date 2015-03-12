<?php

require_once __DIR__.'/../lib/internal/config/config_handler.php';
require_once __DIR__.'/../lib/internal/template/rest_handler.php';

class Handler extends RESTHandler {
    function __construct() {
        parent::__construct(array(self::METHOD_GET));
    }

    protected function get(Parameter $params) {
        $c = new Config();
        session_start();
        $state = $this->secure_random();
        $nonce = $this->secure_random();
        $_SESSION['YAHOO_STATE'] = $state;
        $_SESSION['YAHOO_NONCE'] = $nonce;
        $this->render(
            '_login.html',
            $this->create_css_include_tag('login.css'),
            '_login.js',
            array(
                'state' => $state,
                'nonce' => $nonce,
                'yahoo_client_id' => $c->yahoo_client_id(),
                'yahoo_callback' => $c->yahoo_callback(),
                'google_client_id' => $c->google_client_id()
            )
        );
    }

    private function secure_random() {
        return md5(uniqid(rand(), true));
    }
}

$h = new Handler();
$h->handle();

?>
