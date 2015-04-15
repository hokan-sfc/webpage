<?php

require_once 'model.php';

class ModelTest extends PHPUnit_Framework_TestCase {
    function testModelConstructPDO() {
        $model = new Model('test_table', array());
    }
}

?>
