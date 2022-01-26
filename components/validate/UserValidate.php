<?php
require_once("components/validate/BaseValidate.php");

class UserValidate extends BaseValidate
{
    public $validate;

    public function validateCreateUser($dataPost, $checkExistsEmail)
    {
        $validAvatar = UserValidate::validateAvatar('avatar');
        $validName = UserValidate::validateName($dataPost['name']);
        $validEmail = UserValidate::validateEmail($dataPost['email'], $checkExistsEmail);
        $validPassword = UserValidate::validatePassword($dataPost['password']);
        $validConfirmPassword = UserValidate::validateConfirmPassword($dataPost['password'], $dataPost['confirm-password']);
        $error = array_merge($validAvatar, $validName, $validEmail, $validPassword, $validConfirmPassword);
        return $error;
    }

    public function validateEditUser($data, $dataPost, $checkExistsEmail)
    {
        $error = [];
        $validAvatar = UserValidate::validateAvatar('avatar');
        $validName = UserValidate::validateName($dataPost['name']);
        $validEmail = UserValidate::validateEmail($dataPost['email'], $checkExistsEmail);
        $validPassword = UserValidate::validatePassword($dataPost['password']);
        $validConfirmPassword = UserValidate::validateConfirmPassword($dataPost['password'], $dataPost['confirm-password']);

        if ($dataPost['avatar'] != "") {
            if (empty($validAvatar)) {
                $data['avatar'] = $dataPost['avatar'];
            } else {
                $error = $validAvatar;
            }
        }

        if ($dataPost['name'] != $data['name']) {
            if (!empty($validName)) {
                $error = $validName;
            } else {
                $data['name'] = $dataPost['name'];
            }
        }

        if ($dataPost['email'] != $data['email']) {
            if (!empty($validEmail)) {
                $error = $validEmail;
            } else {
                $data['email'] = $dataPost['email'];
            }
        }

        if (!empty($dataPost['password'])) {
            if (!empty($validPassword)) {
                $error = $validPassword;
            } elseif (!empty($validConfirmPassword)) {
                $error = $validConfirmPassword;
            } else {
                $data['password'] = md5($dataPost['password']);
            }
        }

        return [
            'data' => $data,
            'error' => $error,
        ];
    }
}