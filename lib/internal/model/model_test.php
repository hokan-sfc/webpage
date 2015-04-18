<?php

require_once 'model.php';

class ModelTester extends Model {
    function __construct($table) {
        parent::__construct($table);
    }

    function get_pdo() {
        return $this->pdo();
    }

    function get_id() {
        return $this->id();
    }

    function get_columns() {
        return $this->columns;
    }

    function get($key) {
        return $this->columns[$key];
    }

    function set($key, $value) {
        $this->columns[$key] = $value;
    }
}

class ModelTest extends PHPUnit_Framework_TestCase {
    const TABLE_NAME = 'sample';

    private $model;

    function setUp() {
        $this->model = new ModelTester(self::TABLE_NAME);
        $sql = 'create table if not exists '.self::TABLE_NAME.' (
            id         integer   primary key,
            data       text,
            created_at timestamp default current_timestamp
        );';
        $this->model->get_pdo()->exec($sql);
    }

    function testLoadDataByID() {
        $data = 'data';
        $id = $this->insertSampleData($data);
        $this->assertTrue($this->model->load_by_id($id));
        $this->assertEquals(count($this->model->get_columns()), 2);
        $this->assertEquals($this->model->get('data'), $data);
        $this->assertFalse(
            $this->model->load_by_id($id),
            '読み込み済みデータがある場合はload_by_idが失敗する'
        );
    }

    function testReload() {
        $data = 'data';
        $id = $this->insertSampleData($data);
        $this->assertFalse(
            $this->model->reload(),
            '何もデータを読み込んでない場合はreloadが失敗する'
        );

        $this->model->load_by_id($id);
        $this->assertTrue($this->model->reload());
        $this->assertEquals(
            $this->model->get('data'), $data,
            'データが変更されない限りreloadしてもデータは変わらない'
        );

        $new_data = 'data updated';
        $this->updateSampleData($id, $new_data);
        $this->model->reload();
        $this->assertEquals($this->model->get('data'), $new_data);
    }

    function testSaveNewData() {
        $data = 'data';
        $this->model->set('data', $data);
        $this->assertTrue($this->model->save());
        $this->assertTrue(!is_null($this->model->get_id()));
        $columns = $this->selectSampleData($this->model->get_id());
        $this->assertEquals($columns['data'], $data);
        $this->assertTrue(!empty($columns['created_at']));
    }

    function testSaveUpdatedData() {
        $id = $this->insertSampleData('data');
        $this->model->load_by_id($id);
        $created_at = $this->model->get('created_at');
        $new_data = 'data updated';
        $this->model->set('data', $new_data);
        $this->assertTrue($this->model->save());
        $columns = $this->selectSampleData($this->model->get_id());
        $this->assertEquals($columns['data'], $new_data);
        $this->assertEquals($columns['created_at'], $created_at);
    }

    function testDestroy() {
        $id = $this->insertSampleData('data');
        $this->assertFalse(
            $this->model->destroy(),
            '何もデータを読み込んでない場合にはdestroyが失敗する'
        );
        $this->model->load_by_id($id);
        $this->assertTrue($this->model->destroy());
        $this->assertFalse($this->selectSampleData($id));
        $this->assertTrue(is_null($this->model->get_id()));

        $this->assertFalse(
            $this->model->reload(),
            '削除後はreloadが失敗する'
        );
        $this->assertTrue($this->model->save());
        $this->assertEquals(
            $this->model->get_id(), $id,
            '削除後のsaveでは新しいデータが作成される'
        );
    }

    function tearDown() {
        $sql = 'drop table if exists sample;';
        $this->model->get_pdo()->exec($sql);
    }

    private function insertSampleData($data) {
        $stm = 'insert into '.self::TABLE_NAME.' (data) values (:data);';
        $sql = $this->model->get_pdo()->prepare($stm);
        $sql->bindValue(':data', $data);
        $sql->execute();
        return $this->model->get_pdo()->lastInsertId();
    }

    private function updateSampleData($id, $data) {
        $stm = 'update '.self::TABLE_NAME.' set data = :data where id = :id;';
        $sql = $this->model->get_pdo()->prepare($stm);
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->bindValue(':data', $data);
        $sql->execute();
    }

    private function selectSampleData($id) {
        $stm = 'select * from '.self::TABLE_NAME.' where id = :id;';
        $sql = $this->model->get_pdo()->prepare($stm);
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }
}

?>
