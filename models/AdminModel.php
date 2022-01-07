<?php
require_once "models/BaseModel.php";
class AdminModel extends BaseModel
{
    private static $AdminTable = "admin";
    static function checkExistsEmailAdmin($str){
        $admin = self::$AdminTable;
        $db = DB::getInstance();
        $arr = $db->query("SELECT `email` FROM `{$admin}` WHERE `email` LIKE '{$str}'");
        return $arr->rowCount();
    }

    static function getInfoAdmin($id){
        $admin = self::$AdminTable;
        $db = DB::getInstance();
        $arr = $db->query("SELECT * FROM `{$admin}` WHERE `id` = '{$id}'");
        return $arr->fetch();
    }

    static function getRoleAdmin($email){
        $admin = self::$AdminTable;
        $db = DB::getInstance();
        $arr = $db->query("SELECT `role_type` FROM `{$admin}` WHERE `email` = '{$email}'");
        return $arr->fetch();
    }
}