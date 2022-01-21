<?php
require_once ("helper/validate/BaseValidate.php");

class AdminValidate extends BaseValidate {
    public function validateCreateAdmin($data = [])
    {
        $error = [];
        $avatar= AdminValidate::getErrorAvatar($data['avatar']);
        $name = AdminValidate::getErrorName($data['name']);
        $email = AdminValidate::getErrorEmail($data['email']);
        $password = AdminValidate::getErrorPassword($data['password']);
        if(empty($data['confirm-password'])) $error['error-confirm-password'] = ERROR_EMPTY_CONFIRM_PASSWORD;
        $error = array_merge($error, $avatar, $name, $email, $password);
        return $error;
    }

    public function validateEditAdmin($dataPost = []){
        $error = [];

        if(!empty($dataPost['avatar'])) {
            empty(AdminValidate::validateAvatar($dataPost['avatar'])) ? "" : $error['error-avatar'] = AdminValidate::validateAvatar($dataPost['avatar']);
        }

        if(empty($dataPost['name'])) $error['error-name'] = ERROR_EMPTY_NAME;
        else{
            empty(AdminValidate::validateName($dataPost['name'])) ? "" : $error['error-name'] = AdminValidate::validateName($dataPost['name']);
        }

        if(empty($dataPost['email'])) $error['error-email'] = ERROR_EMPTY_EMAIL;
        else{
            empty(AdminValidate::validateEmail($dataPost['email'])) ? "" : $error['error-email'] = AdminValidate::validateEmail($dataPost['email']);
        }

        if(!empty($dataPost['password'])){
            empty(AdminValidate::validatePassword($dataPost['password'])) ? "" : $error['error-password'] = AdminValidate::validatePassword($dataPost['password']);
        }

        return $error;
    }
}