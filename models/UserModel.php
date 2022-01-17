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
        return $this->db->query("SELECT `email` FROM `{$this->table}` WHERE `email` LIKE '{$str}' AND `del_flag` = ".ACTIVED)->rowCount();
    }

    public function getInfoUserByEmail($str){
        return $this->db->query("SELECT * FROM `{$this->table}` WHERE `email` LIKE '{$str}' AND `del_flag` = ".ACTIVED)->fetch();
    }

    public function getInfoAdminByEmail($str){
        return $this->db->query("SELECT * FROM `admin` WHERE `email` LIKE '{$str}' AND `del_flag` = ".ACTIVED)->fetch();
    }

    public function getInfoUserByID($id){
        return $this->db->query("SELECT * FROM `{$this->table}` WHERE `id` = '{$id}' AND `del_flag` = ".ACTIVED)->fetch();
    }

    public function getIdUserByEmail($email){
        return $this->db->query("SELECT `id` FROM `{$this->table}` WHERE `email` = '{$email}' AND `del_flag` = ".ACTIVED)->fetch();
    }
}