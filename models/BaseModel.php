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

    public function getQuery($fields, $conditions, $temp)
    {
        if ($this->table == "admin") {
            $conditions['del_flag'] = ACTIVED;
        }
        else {
            $conditions['status'] = ACTIVED;
        }
        $values = "";
        foreach ($conditions as $field => $value) {
            $values .= "{$field} = '{$value}' AND ";
        }
        $values = substr($values, 0, -5);

//        echo "Select $fields from $this->table where $values";
        return $this->db->query("Select $fields from $this->table where $values")->fetch();
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

        $values = "";
        foreach ($data as $field => $value) {
            $values .= "{$field} = '{$value}', ";
        }
        $values = substr($values, 0, -2);

        $sql = "UPDATE {$this->table} SET $values WHERE $where";
        $arr = $this->db->query($sql);

        return $arr ? true : false;
    }

    public function deleteById($id)
    {
        // TODO: Implement delete() method.
        $sql = "UPDATE $this->table SET `del_flag` = '" . BANNED . "' WHERE `id` = {$id}";
        $query = $this->db->query($sql);
        return $query ? true : false;
    }

    abstract public function getInfoById($id);
    abstract public function getSearch($arr);

    public function getInfoByEmail($str)
    {
        return $this->db->query("SELECT `id`, `name`, `email`, `avatar`, `status` FROM `{$this->table}` WHERE `email` LIKE '{$str}' AND `del_flag` = " . ACTIVED)->fetch();
    }

    public function checkExistsEmail($email)
    {
        $arr = $this->db->query("SELECT `email` FROM `{$this->table}` WHERE `email` LIKE '{$email}' AND `del_flag` = " . ACTIVED)->rowCount();
        return $arr > 0 ? false : true;
    }

    public function getTotalRow($name, $email)
    {
        $where = "`name` LIKE '%$name%' AND `email` LIKE '%{$email}%' AND `del_flag`=" . ACTIVED;
        $query = "SELECT `id` FROM {$this->table} WHERE $where";
        return $this->db->query($query)->rowCount();
    }

}