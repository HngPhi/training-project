<?php
require_once ("helper/validate/BaseValidate.php");

class UserValidate extends BaseValidate {
    public function validateCreateUser($data = [])
    {
        $error = [];
        $avatar= UserValidate::getErrorAvatar($data['avatar']);
        $name = UserValidate::getErrorName($data['name']);
        $email = UserValidate::getErrorEmail($data['email']);
        $password = UserValidate::getErrorPassword($data['password']);
        if(empty($data['confirm-password'])) $error['error-confirm-password'] = ERROR_EMPTY_CONFIRM_PASSWORD;
        $error = array_merge($error, $avatar, $name, $email, $password);
        return $error;
    }

    public function validateEditUser($dataPost = []){
        $error = [];

        if(!empty($dataPost['avatar'])) {
            empty(UserValidate::validateAvatar($dataPost['avatar'])) ? "" : $error['error-avatar'] = UserValidate::validateAvatar($dataPost['avatar']);
        }

        if(empty($dataPost['name'])) $error['error-name'] = ERROR_EMPTY_NAME;
        else{
            empty(UserValidate::validateName($dataPost['name'])) ? "" : $error['error-name'] = UserValidate::validateName($dataPost['name']);
        }

        if(empty($dataPost['email'])) $error['error-email'] = ERROR_EMPTY_EMAIL;
        else{
            empty(UserValidate::validateEmail($dataPost['email'])) ? "" : $error['error-email'] = UserValidate::validateEmail($dataPost['email']);
        }

        if(!empty($dataPost['password'])){
            empty(UserValidate::validatePassword($dataPost['password'])) ? "" : $error['error-password'] = UserValidate::validatePassword($dataPost['password']);
        }

        return $error;
    }
}