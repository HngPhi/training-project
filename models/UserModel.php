<?php
require_once "models/BaseModel.php";

class UserModel extends BaseModel
{
    public $table;
    public $db;
    function __construct()
    {
        $this->table = "user";
        $this->db = DB::getInstance();
    }

    public function checkExistsEmailUser($str){
        return $this->db->query("SELECT `email` FROM `{$this->table}` WHERE `email` LIKE '{$str}'")->rowCount();
    }

    public function getInfoUserByEmail($str){
        return $this->db->query("SELECT * FROM `{$this->table}` WHERE `email` LIKE '{$str}'")->fetch();
    }

    public function getInfoAdminByEmail($str){
        return $this->db->query("SELECT * FROM `admin` WHERE `email` LIKE '{$str}'")->fetch();
    }

    public function getInfoUserByID($id){
        return $this->db->query("SELECT * FROM `{$this->table}` WHERE `id` = '{$id}'")->fetch();
    }

    public function getIdUserByEmail($email){
        return $this->db->query("SELECT `id` FROM `{$this->table}` WHERE `email` = '{$email}'")->fetch();
    }
}