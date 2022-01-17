<?php
require_once "models/BaseModel.php";
class AdminModel extends BaseModel
{
    public $table;
    public $db;
    function __construct()
    {
        $this->table = "admin";
        $this->db = DB::getInstance();
    }

    public function checkExistsEmailAdmin($str){
        if(!empty($str)) return $this->db->query("SELECT `email` FROM `{$this->table}` WHERE `email` LIKE '{$str}' AND `del_flag` = ".DEL_FLAG_0)->rowCount();
    }

    public function getInfoAdmin($id){
        return $this->db->query("SELECT * FROM `{$this->table}` WHERE `id` = '{$id}' AND `del_flag` = ".DEL_FLAG_0)->fetch();
    }

    public function getRoleAdmin($email){
        return $this->db->query("SELECT `role_type` FROM `{$this->table}` WHERE `email` = '{$email}' AND `del_flag` = ".DEL_FLAG_0)->fetch();
    }
}