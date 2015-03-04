<?php

require_once 'rest_handler.php';

class RenderHandler extends RESTHandler {
    function render_tester(
        $body,
        $stylesheets=NULL,
        $javascripts=NULL,
        array $params=array()
    ) {
        $this->render($body, $stylesheets, $javascripts, $params);
    }

    function render_not_found_tester() {
        $this->render_not_found();
    }

    function sanitize_tester($raw) {
        return $this->sanitize($raw);
    }

    function create_css_include_tag_tester($path) {
        return $this->create_css_include_tag($path);
    }

    function create_js_include_tag_tester($path) {
        return $this->create_js_include_tag($path);
    }
}

class AllForbiddenHandler extends RESTHandler {
    protected function finalize() {}
}

class DeleteAllowedHandler extends RESTHandler {
    function __construct() {
        parent::__construct(array(self::METHOD_DELETE));
    }
    protected function finalize() {}
}

class RESTHandlerTest extends PHPUnit_Framework_TestCase {
    function eachMethodRequestProvider() {
        return array(
            array(array('REQUEST_METHOD' => 'GET')),
            array(array('REQUEST_METHOD' => 'POST')),
            array(array('REQUEST_METHOD' => 'PUT')),
            array(array('REQUEST_METHOD' => 'DELETE')),
            array(array('REQUEST_METHOD' => 'HEAD'))
        );
    }

    /**
     * @dataProvider eachMethodRequestProvider
     * @runInSeparateProcess
     */
    function testUnavailableResourceRenders404Page($request) {
        $_SERVER = $request;
        ob_start();
        (new AllForbiddenHandler())->handle();
        $rendered = ob_get_clean();
        $expect = file_get_contents(dirname(__FILE__).'/_404.html');
        $this->assertTrue(strpos($rendered, $expect) !== FALSE);
    }

    /**
     * @dataProvider eachMethodRequestProvider
     * @runInSeparateProcess
     */
    function testForbiddenMethodReturns405($request) {
        $_SERVER = $request;
        (new DeleteAllowedHandler())->handle();
        $headers = xdebug_get_headers();
        $this->assertContains('Allow: DELETE', $headers);
    }

    function testRenderFileWithNoParams() {
        $h = new RenderHandler();
        $body_file = tmpfile();
        $js_file = tmpfile();
        $ss_file = tmpfile();
        $body_expect = '<h1>This is the test for the method render.</h1>';
        $js_expect = '<script type="text/javascript" src="javascript.js"></script>';
        $ss_expect = '<link rel="stylesheet" type="text/css" href="stylesheet.css">';
        fwrite($body_file, $body_expect);
        fwrite($js_file, $js_expect);
        fwrite($ss_file, $ss_expect);
        $body = stream_get_meta_data($body_file)['uri'];
        $js = stream_get_meta_data($js_file)['uri'];
        $ss = stream_get_meta_data($ss_file)['uri'];

        ob_start();
        $h->render_tester($body, $js, $ss);
        $rendered = ob_get_clean();

        $this->assertTrue(strpos($rendered, $body_expect) !== FALSE);
        $this->assertTrue(strpos($rendered, $js_expect) !== FALSE);
        $this->assertTrue(strpos($rendered, $ss_expect) !== FALSE);
        fclose($body_file);
        fclose($js_file);
        fclose($ss_file);
    }

    function testRenderStringWithNoParams() {
        $h = new RenderHandler();
        $body = '<h1>This is the test for the method render.</h1>';
        $js = '<script type="text/javascript" src="javascript.js"></script>';
        $ss = '<link rel="stylesheet" type="text/css" href="stylesheet.css">';

        ob_start();
        $h->render_tester($body, $js, $ss);
        $rendered = ob_get_clean();

        $this->assertTrue(strpos($rendered, $body) !== FALSE);
        $this->assertTrue(strpos($rendered, $js) !== FALSE);
        $this->assertTrue(strpos($rendered, $ss) !== FALSE);
    }

    function testRenderWithParams() {
        $h = new RenderHandler();
        $body_file = tmpfile();
        fwrite($body_file, '<?php echo $text ?>');
        $body = stream_get_meta_data($body_file)['uri'];
        $expect = 'This is a text for parameter test.';

        ob_start();
        $h->render_tester($body, NULL, NULL, array('text' => $expect));
        $rendered = ob_get_clean();

        $this->assertTrue(strpos($rendered, $expect) !== FALSE);
        fclose($body_file);
    }

    function testRenderNotFoundRenders404Page() {
        $h = new RenderHandler();
        ob_start();
        $h->render_not_found_tester();
        $rendered = ob_get_clean();
        $expect = file_get_contents(dirname(__FILE__).'/_404.html');
        $this->assertTrue(strpos($rendered, $expect) !== FALSE);
    }

    function testSanitize() {
        $h = new RenderHandler();
        $raw = '&"\'<>';
        $expect = '&amp;&quot;&#039;&lt;&gt;';
        $this->assertEquals($h->sanitize_tester($raw), $expect);
    }

    function testCreateCSSIncludeTag() {
        $h = new RenderHandler();
        $path = 'index.html';
        $expect = '<link rel="stylesheet" type="text/css" href="'.$path.'">';
        $rendered = $h->create_css_include_tag_tester($path);
        $this->assertEquals($rendered, $expect);
    }

    function testCreateJSIncludeTag() {
        $h = new RenderHandler();
        $path = 'index.html';
        $expect = '<script type="text/javascript" src="'.$path.'"></script>';
        $rendered = $h->create_js_include_tag_tester($path);
        $this->assertEquals($rendered, $expect);
    }
}

?>
