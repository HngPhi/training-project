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

    public function getQuery($fields, $conditions)
    {
        $conditions['del_flag'] = ACTIVED;
        $values = "";
        foreach ($conditions as $field => $value) {
            $values .= "{$field} LIKE '{$value}' AND ";
        }
        $values = substr($values, 0, -5);

        return $this->db->query("Select $fields from $this->table where $values");
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

    public function updateById($data = [], $id)
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

        $sql = "UPDATE {$this->table} SET $values WHERE `id` = {$id}";
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

    public function getInfoByEmail($email)
    {
        return $this->getQuery("`id`, `name`, `email`, `avatar`, `status`", ['email' => $email])->fetch();
    }

    public function checkExistsEmail($email)
    {
        $arr = $this->getQuery("id", ['email' => $email])->rowCount();
        return $arr > 0 ? false : true;
    }

    public function getTotalRow($name, $email)
    {
        return $this->getQuery("id", ['name' => "%$name%", 'email' => "%$email%"])->rowCount();
    }

    public function getIdByEmail($email)
    {
        return $this->getQuery("id", ['email' => $email])->fetch();
    }
}