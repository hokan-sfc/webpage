<?php

function _abs_path($file_name) {
    return dirname(__FILE__).'/'.$file_name;
}

require_once _abs_path('parameter.php');

class RESTHandler {
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    private $allow_methods;

    function __construct(array $allow_methods=array()) {
        $this->allow_methods = $allow_methods;
    }

    function handle() {
        switch ($_SERVER['REQUEST_METHOD']) {
        case self::METHOD_GET:
            $this->get(new Parameter($_GET));
            break;
        case self::METHOD_POST:
            $this->post(new Parameter($_POST, $_FILES));
            break;
        case self::METHOD_PUT:
            $this->put();
            break;
        case self::METHOD_DELETE:
            $this->delete();
            break;
        default:
            $this->method_not_allowed();
        }
    }

    protected function get(Parameter $params) {
        $this->method_not_allowed();
    }

    protected function post(Parameter $params) {
        $this->method_not_allowed();
    }

    protected function put() {
        $this->method_not_allowed();
    }

    protected function delete() {
        $this->method_not_allowed();
    }

    protected function render(
        $body,
        $stylesheets=NULL,
        $javascripts=NULL,
        array $params=array()
    ) {
        if (count($params) > 0) {
            extract($params);
        }
        include _abs_path('template.php');
    }

    protected function render_not_found() {
        $body = _abs_path('_404.html');
        include _abs_path('template.php');
    }

    protected function sanitize($raw) {
        return htmlspecialchars($raw, ENT_QUOTES, 'UTF-8');
    }

    protected function create_css_include_tag($path) {
        return '<link rel="stylesheet" type="text/css" href="'.$path.'">';
    }

    protected function create_js_include_tag($path) {
        return '<script type="text/javascript" src="'.$path.'"></script>';
    }

    /**
     * @codeCoverageIgnore
     */
    protected function finalize() {
        exit();
    }

    private function method_not_allowed() {
        if (count($this->allow_methods) > 0) {
            header('Allow: '.join(", ", $this->allow_methods), true, 405);
        } else {
            header(' ', true, 404);
            $this->render_not_found();
        }
        $this->finalize();
    }
}

?>
