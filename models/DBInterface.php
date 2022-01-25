<?php
interface DBInterface {
    public function insert();
    public function update();
    public function deleteById($id);
    public function getInfoById($id);
}