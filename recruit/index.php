<?php

require_once dirname(__FILE__).'/../lib/internal/template/rest_handler.php';

class Handler extends RESTHandler {
    function __construct() {
        parent::__construct(array(self::METHOD_GET));
    }

    protected function get(Parameter $params) {
        $this->render(
            '_index.html',
            $this->create_css_include_tag('index.css')
        );
    }
}

$h = new Handler();
$h->handle();

?>
