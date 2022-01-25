<?php
require_once("components/validate/BaseValidate.php");

class AdminValidate extends BaseValidate {
    public function validateCreateAdmin($data = [], $checkExistsEmail)
    {
        $error = [];
        $avatar= AdminValidate::getErrorAvatar($data['avatar']);
        $name = AdminValidate::getErrorName($data['name']);
        $email = AdminValidate::getErrorEmail($data['email'], $checkExistsEmail);
        $password = AdminValidate::getErrorPassword($data['password']);
        $confirmPassword = AdminValidate::getErrorConfirmPassword($data['password'], $data['confirm-password']);
        $error = array_merge($error, $avatar, $name, $email, $password, $confirmPassword);
        return $error;
    }

    public function validateEditAdmin($dataPost = [], $checkExistsEmail, $data = []){
        $error = [];
        $validateAvatar = AdminValidate::validateAvatar($dataPost['avatar']);
        $validateName = AdminValidate::validateName($dataPost['name']);
        if($dataPost['email'] != $data['email']){
            $validateEmail = AdminValidate::validateEmail($dataPost['email'], $checkExistsEmail);
        }
        $validatePassword = AdminValidate::validatePassword($dataPost['password']);

        if($dataPost['avatar'] != ""){
            if(!empty($validateAvatar)){
                $error['error-avatar'] = $validateAvatar;
            }else{
                $data['avatar'] = $dataPost['avatar'];
            }
        }

        if(empty($dataPost['name'])){
            $error['error-name'] = ERROR_EMPTY_NAME;
        }
        else{
            if(!empty($validateName)){
                $error['error-name'] = $validateName;
            }else{
                $data['name'] = $dataPost['name'];
            }
        }

        if(empty($dataPost['email'])){
            $error['error-email'] = ERROR_EMPTY_EMAIL;
        }
        else{
            if(!empty($validateEmail)){
                $error['error-email'] = $validateEmail;
            }else{
                $data['email'] = $dataPost['email'];
            }
        }

        if(!empty($dataPost['password'])){
            if(!empty($validatePassword)){
                $error['error-password'] = $validatePassword;
            }else{
                if(!checkConfirmPassword($dataPost['password'], $dataPost['confirm-password'])){
                    $error['error-confirm-password'] = ERROR_CONFIRM_PASSWORD;
                }else{
                    $data['password'] = md5($dataPost['password']);
                }
            }
        }

        return [
            'data' => $data,
            'error' => $error,
        ];
    }
}