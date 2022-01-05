<?php
require_once "models/BaseModel.php";

class UserModel extends BaseModel
{
    private static $UserTable = "user";

    static function getListUser(){
        $db = DB::getInstance();
        $user = self::$UserTable;
        $arr = $db->query("SELECT * FROM `{$user}` WHERE `del_flag` = ".DEL_FLAG);
        return $arr->fetchAll();
    }

    static function getSearchUser($email, $name){
        $user = self::$UserTable;
        $db = DB::getInstance();
        $arr = $db->query("SELECT `id`, `avatar`, `name`, `email`, `status` FROM `{$user}` WHERE `email` LIKE '%{$email}%' AND `name` LIKE '%{$name}%'");
        return $arr->fetchAll();
    }

    static function checkExistsEmailUser($str){
        $user = self::$UserTable;
        $db = DB::getInstance();
        $arr = $db->query("SELECT `email` FROM `{$user}` WHERE `email` LIKE '{$str}'");
        return $arr->rowCount();
    }

    static function getInfoUserByEmail($str){
        $user = self::$UserTable;
        $db = DB::getInstance();
        $arr = $db->query("SELECT * FROM `{$user}` WHERE `email` LIKE '{$str}'");
        return $arr->fetch();
    }

    static function getInfoAdminByEmail($str){
        $db = DB::getInstance();
        $arr = $db->query("SELECT * FROM `admin` WHERE `email` LIKE '{$str}'");
        return $arr->fetch();
    }

    static function getInfoUserByID($id){
        $user = self::$UserTable;
        $db = DB::getInstance();
        $arr = $db->query("SELECT * FROM `{$user}` WHERE `id` = '{$id}'");
        return $arr->fetch();
    }

//    Validate
    static function validateEmailUser($email){
        $data = array();
        $pattern = "/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9]+\.[a-zA-Z0-9]{2,6}$/";
        if(!empty($email)){
            if(!preg_match($pattern, $email, $matches)) $data['error-email'] = ERROR_VALID_EMAIL;
        }
        return $data;
    }

    static function validatePasswordUser($password){
        $data = array();
        $pattern = "/^([\w_\.!@#$%^&*()-]+){5,50}$/";
        if(!empty($password)){
            if(!preg_match($pattern, $password, $matches)) $data['error-password'] = ERROR_VALID_PASSWORD;
        }
        return $data;
    }

    static function validateNameUser($name){
        $data = array();
        $pattern = "/^([a-zA-Z0-9\s\._-]+){3,50}$/";
        if(!empty($name)){
            if(!preg_match($pattern, $name, $matches)) $data['error-name'] = ERROR_VALID_NAME;
        }
        return $data;
    }

    static function validateImgUser(){
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

    static function checkConfirmPasswordUser($password, $confirm_password){
        $data = array();
        if(isset($password)){
            if($password != $confirm_password) $data["error-confirm-password"] =  ERROR_CONFIRM_PASSWORD;
        }
        return $data;
    }

    //Sort
    static function sortIDUser($sort){
        $user = self::$UserTable;
        $db = DB::getInstance();
        $arr = $db->query("SELECT * FROM `{$user}` ORDER BY `id` $sort");
        return $arr->fetchAll();
    }

    static function sortNameUser($sort){
        $user = self::$UserTable;
        $db = DB::getInstance();
        $arr = $db->query("SELECT * FROM `{$user}` ORDER BY `name` $sort");
        return $arr->fetchAll();
    }

    static function sortEmailUser($sort){
        $user = self::$UserTable;
        $db = DB::getInstance();
        $arr = $db->query("SELECT * FROM `{$user}` ORDER BY `email` $sort");
        return $arr->fetchAll();
    }

    static function sortStatusUser($sort){
        $user = self::$UserTable;
        $db = DB::getInstance();
        $arr = $db->query("SELECT * FROM `{$user}` ORDER BY `status` $sort");
        return $arr->fetchAll();
    }
}