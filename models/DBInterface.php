<?php
interface DBInterface {
    public function insert();
    public function update();
    public function delete();
    public function getInfoById($id);
}