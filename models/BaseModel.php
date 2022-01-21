<?php
require_once "DBInterface.php";

date_default_timezone_set('Asia/Ho_Chi_Minh');

abstract class BaseModel implements DBInterface
{
    protected $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function getIdAdmin($str)
    {
        return $this->db->query("SELECT `id` FROM `admin` WHERE `email` LIKE '{$str}' AND `del_flag` = " . ACTIVED)->fetch();
    }

    //CRUD
    public function insert($data = [])
    {
        $ins = [
            'ins_id' => isset($_SESSION['admin']['login']['id']) ? $_SESSION['admin']['login']['id'] : "",
            'ins_datetime' => date("Y-m-d H:i:s a"),
        ];
        $data = array_merge($data, $ins);

        //INSERT INTO `admin` (``, ``, ``, ``) VALUES('', '', '', '');
        $fields = "";
        $values = "";
        foreach ($data as $key => $value) {
            $fields .= "`" . $key . "`, ";
            $values .= "'" . $value . "', ";
        }

        $fields = substr($fields, 0, -2);
        $values = substr($values, 0, -2);

        $sql = "INSERT INTO $this->table ($fields) VALUES($values)";
        $arr = $this->db->query($sql);
        return $arr ? true : false;
    }

    public function update($data = [], $where = "")
    {
        $upd = [
            'upd_id' => isset($_SESSION['admin']['login']['id']) ? $_SESSION['admin']['login']['id'] : "",
            'upd_datetime' => date("Y-m-d H:i:s a"),
        ];
        $data = array_merge($data, $upd);

        $sql = "";
        foreach ($data as $field => $value) {
            $sql .= "{$field} = '{$value}', ";
        }
        $sql = substr($sql, 0, -2);

        $sql = "UPDATE {$this->table} SET $sql WHERE $where";;
        $arr = $this->db->query($sql);
        return $arr ? true : false;
    }

    public function delete($where = "")
    {
        // TODO: Implement delete() method.
        $sql = "UPDATE $this->table SET `del_flag` = '" . BANNED . "' WHERE $where";
        $query = $this->db->query($sql);
        return $query ? true : false;
    }

    public function findById($id)
    {
        // TODO: Implement findById() method.
    }

    public function checkLogin($email, $password)
    {
        $arr = $this->db->query("SELECT `email`, `password` FROM `{$this->table}` WHERE `email` LIKE '{$email}' AND `password` LIKE '{$password}' AND `del_flag` = " . ACTIVED)->rowCount();
        return $arr == 1 ? true : false;
    }
}