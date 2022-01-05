<?php
require_once "DBInterface.php";
abstract class BaseModel implements DBInterface
{
    public function insert($table = "", $data = array())
    {
        //INSERT INTO `admin` (``, ``, ``, ``) VALUES('', '', '', '');
        $fields = "";
        $values = "";
        foreach($data as $key => $value){
            $fields .= "`" . $key . "`, ";
            $values .=  "'" . $value . "', ";
        }

        $fields = substr($fields, 0, -2);
        $values = substr($values, 0, -2);

        $db = DB::getInstance();
        $sql = "INSERT INTO $table ($fields) VALUES($values)";
        $arr = $db->query($sql);
        if($arr) return true;
        else return false;
    }

    public function update($table = "", $data = array(), $where = ""){
        $sql = "";
        foreach($data as $field => $value){
            $sql .= "{$field} = '{$value}', ";
        }
        $sql = substr($sql, 0, -2);

        $db = DB::getInstance();
        $sql = "UPDATE {$table} SET $sql WHERE $where";;
        $arr = $db->query($sql);
        if($arr) return true;
        else return false;
    }

    public function delete($table = "", $where = "")
    {
        // TODO: Implement delete() method.
        $db = DB::getInstance();
        $sql = "UPDATE $table SET `del_flag` = '". DEL_FLAG_DELETE . "' WHERE $where";
        $query = $db->query($sql);
        if($query) return true;
        else return false;
    }
}