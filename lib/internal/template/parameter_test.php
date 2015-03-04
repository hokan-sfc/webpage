<?php

require_once 'parameter.php';

class ParameterTest extends PHPUnit_Framework_TestCase {
    function invalidKeyRequestProvider() {
        $key = 'key';
        $empty_data = array($key, array());
        $nonexistent_key = array('nonexistent_key', array($key => 'value'));
        $empty_string = array($key, array($key => ''));
        $empty_array = array($key, array($key => array()));
        return array($empty_data, $nonexistent_key, $empty_string, $empty_array);
    }

    /**
     * @dataProvider invalidKeyRequestProvider
     */
    function testGetStringWithInvalidKeyRequest($key, $data) {
        $p = new Parameter($data);
        $this->assertTrue($p->get_string($key) == FALSE);
    }

    function testGetStringWithValidData() {
        $key = 'key';
        $value = 'value';
        $p = new Parameter(array($key => $value));
        $this->assertEquals($value, $p->get_string($key));
    }

    /**
     * @dataProvider invalidKeyRequestProvider
     */
    function testGetArrayWithInvalidKeyRequest($key, $data) {
        $p = new Parameter($data);
        $this->assertTrue($p->get_array($key) == FALSE);
    }

    function testGetArrayWithValidData() {
        $key = 'key';
        $value = array('value');
        $p = new Parameter(array($key => $value));
        $this->assertEquals($value, $p->get_array($key));
    }

    /**
     * @dataProvider invalidKeyRequestProvider
     */
    function testGetFileWithInvalidKeyRequest($key, $data) {
        $p = new Parameter(array(), $data);
        $this->assertFalse($p->get_file($key));
    }

    function testGetFileWithNoFile() {
        $p = new Parameter(array());
        $this->assertFalse($p->get_file('key'));
    }

    function testGetFileWithError() {
        $key = 'key';
        $data = array($key => array('error' => UPLOAD_ERR_INI_SIZE));
        $p = new Parameter(array(), $data);
        $this->assertFalse($p->get_file($key));
    }

    function testGetFileWithValidData() {
        $key = 'key';
        $value = array('error' => UPLOAD_ERR_OK);
        $p = new Parameter(array(), array($key => $value));
        $this->assertEquals($value, $p->get_file($key));
    }
}

?>
