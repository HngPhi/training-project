<?php
require_once "models/BaseModel.php";
class AdminModel extends BaseModel
{
    private static $AdminTable = "admin";
    static function getListAdmin(){
        $array = [];
        $db = DB::getInstance();
        $admin = self::$AdminTable;
        $get_list_admin = $db->query("SELECT * FROM `{$admin}` WHERE `del_flag` = ".DEL_FLAG);
        return $get_list_admin->fetchAll();
    }

    static function checkExistsEmailAdmin($str){
        $admin = self::$AdminTable;
        $db = DB::getInstance();
        $arr = $db->query("SELECT `email` FROM `{$admin}` WHERE `email` LIKE '{$str}'");
        return $arr->rowCount();
    }

    static function getIdAdmin($str){
        $admin = self::$AdminTable;
        $db = DB::getInstance();
        $arr = $db->query("SELECT `id` FROM `{$admin}` WHERE `email` LIKE '{$str}'");
        return $arr->fetch();
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

//    Validate
    static function validateEmailAdmin($email){
        $data = array();
        $pattern = "/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9]+\.[a-zA-Z0-9]{2,6}$/";
        if(!empty($email)){
            if(!preg_match($pattern, $email, $matches)) $data['error-email'] = ERROR_VALID_EMAIL;
        }
        return $data;
    }

    static function validatePasswordAdmin($password){
        $data = array();
        $pattern = "/^([\w_\.!@#$%^&*()-]+){5,50}$/";
        if(!empty($password)){
            if(!preg_match($pattern, $password, $matches)) $data['error-password'] = ERROR_VALID_PASSWORD;
        }
        return $data;
    }

    static function validateNameAdmin($name){
        $data = array();
        $pattern = "/^([a-zA-Z0-9ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+){3,50}$/";
        if(!empty($name)){
            if(!preg_match($pattern, $name, $matches)) $data['error-name'] = ERROR_VALID_NAME;
        }
        return $data;
    }

    static function validateImgAdmin(){
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

    static function checkConfirmPasswordAdmin($password, $confirm_password){
        $data = array();
        if(isset($password)){
            if($password != $confirm_password) $data["error-confirm-password"] =  ERROR_CONFIRM_PASSWORD;
        }
        return $data;
    }
}