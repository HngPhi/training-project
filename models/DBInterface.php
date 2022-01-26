<?php
interface DBInterface {
    public function insert();
    public function updateById($data, $id);
    public function deleteById($id);
    public function getInfoById($id);
}