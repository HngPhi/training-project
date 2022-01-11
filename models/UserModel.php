<?php
require_once "models/BaseModel.php";

class UserModel extends BaseModel
{
    private static $UserTable = "user";

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

    static function getIdUserByEmail($email){
        $user = self::$UserTable;
        $db = DB::getInstance();
        $arr = $db->query("SELECT `id` FROM `{$user}` WHERE `email` = '{$email}'");
        return $arr->fetch();
    }
}