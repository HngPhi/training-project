<?php
require_once "DBInterface.php";
abstract class BaseModel implements DBInterface
{
    //CRUD
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
        $sql = "UPDATE $table SET `del_flag` = '". DEL_FLAG_1 . "' WHERE $where";
        $query = $db->query($sql);
        if($query) return true;
        else return false;
    }

    //Pagging
    public function getTotalRow($table, $where){
        $db = DB::getInstance();
        $arr = $db->query("SELECT * FROM `{$table}` $where");
        return $arr->rowCount();
    }

    function getInfoSearch($table, $where, $orderBy, $limit){
        $db = DB::getInstance();
        $arr = $db->query("SELECT * FROM `{$table}` $where $orderBy $limit");
        return $arr->fetchAll();
    }

    //Validate
    static function validateEmail($email){
        $data = array();
        $pattern = "/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9]+\.[a-zA-Z0-9]{2,6}$/";
        if(!empty($email)){
            if(!preg_match($pattern, $email, $matches)) $data['error-email'] = ERROR_VALID_EMAIL;
        }
        return $data;
    }

    static function validatePassword($password){
        $data = array();
        $pattern = "/^([\w_\.!@#$%^&*()-]+){5,50}$/";
        if(!empty($password)){
            if(!preg_match($pattern, $password, $matches)) $data['error-password'] = ERROR_VALID_PASSWORD;
        }
        return $data;
    }

    static function validateName($name){
        $data = array();
        $pattern = "/^([a-zA-Z0-9ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+){3,50}$/";
        if(!empty($name)){
            if(!preg_match($pattern, $name, $matches)) $data['error-name'] = ERROR_VALID_NAME;
        }
        return $data;
    }

    static function validateImg(){
        $data = array();
        if($_FILES['avatar']['name'] != ""){
            $type_allow = ['jpg', 'jpeg', 'png', 'gif'];
            $type_img = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            //Check đuôi file
            if(!in_array(strtolower($type_img), $type_allow)) $data["error-avatar"] = IMAGE_INVALID;
            else{
                //Check kích thước file(<20MB ~ 29.000.000Byte)
                $size_img = $_FILES['avatar']['size'];
                if($size_img > 29000000) $data["error-avatar"] = IMAGE_MAX_SIZE;
            }
        }
        return $data;
    }

    static function checkLogin($table, $email, $password){
        $db = DB::getInstance();
        $arr = $db->query("SELECT `email`, `password` FROM `{$table}` WHERE `email` LIKE '{$email}' AND `password` LIKE '{$password}'");
        if($arr) return true;
        else return false;
    }

    static function checkConfirmPassword($password, $confirm_password){
        $data = array();
        if(isset($password)){
            if($password != $confirm_password) $data["error-confirm-password"] =  ERROR_CONFIRM_PASSWORD;
        }
        return $data;
    }

    static function getIdAdmin($str){
        $db = DB::getInstance();
        $arr = $db->query("SELECT `id` FROM `admin` WHERE `email` LIKE '{$str}'");
        return $arr->fetch();
    }
}