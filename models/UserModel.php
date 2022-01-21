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

    public function searchUser($conditions = [])
    {
        $where = "`name` LIKE '%{$conditions['name']}%' AND `email` LIKE '%{$conditions['email']}%' AND `del_flag`=".ACTIVED;
        $orderBy = "ORDER BY `{$conditions['column']}` {$conditions['sort']}";
        $limit = "limit {$conditions['start']}, ".RECORD_PER_PAGE;
        $query = "SELECT `id`, `name`, `email`, `avatar`, `status` from {$this->table} where $where $orderBy $limit";
        return $this->db->query($query)->fetchAll();
    }

    public function getTotalRowUser($name, $email)
    {
        $where = "`name` LIKE '%$name%' AND `email` LIKE '%{$email}%' AND `del_flag`=".ACTIVED;
        $query = "SELECT `id`, `name`, `email`, `avatar`, `status` FROM {$this->table} WHERE $where";
        return $this->db->query($query)->rowCount();
    }

    public function checkExistsEmailUser($str){
        $arr = $this->db->query("SELECT `email` FROM `{$this->table}` WHERE `email` LIKE '{$str}' AND `del_flag` = ".ACTIVED)->rowCount();
        return $arr > 0 ? false : true;
    }

    public function getInfoUserByEmail($str){
        return $this->db->query("SELECT `id`, `name`, `email`, `avatar`, `status` FROM `{$this->table}` WHERE `email` LIKE '{$str}' AND `del_flag` = ".ACTIVED)->fetch();
    }

    public function getInfoAdminByEmail($str){
        return $this->db->query("SELECT `id`, `name`, `email`, `avatar`, `status` FROM `admin` WHERE `email` LIKE '{$str}' AND `del_flag` = ".ACTIVED)->fetch();
    }

    public function getInfoUserByID($id){
        return $this->db->query("SELECT `id`, `name`, `email`,  `password`, `avatar`, `status`  FROM `{$this->table}` WHERE `id` = '{$id}' AND `del_flag` = ".ACTIVED)->fetch();
    }

    public function getIdUserByEmail($email){
        return $this->db->query("SELECT `id` FROM `{$this->table}` WHERE `email` = '{$email}' AND `del_flag` = ".ACTIVED)->fetch();
    }
}