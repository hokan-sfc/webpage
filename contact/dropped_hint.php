<?php

require_once __DIR__.'/../lib/internal/template/rest_handler.php';

class Handler extends RESTHandler {
    function __construct() {
        parent::__construct(array(self::METHOD_GET));
    }

    protected function get(Parameter $params) {
        if (file_exists('shown')) {
            header('Location: index.php');
            exit;
        }
        touch('shown');
        $this->render(
            '_dropped_hint.php',
            $this->create_css_include_tag('dropped_hint.css')
        );
    }
}

$h = new Handler();
$h->handle();

?>
