<?php
require_once("components/validate/BaseValidate.php");

class AdminValidate extends BaseValidate
{
    public $validate;

    public function validateCreateAdmin($dataPost, $checkExistsEmail)
    {
        $validAvatar = AdminValidate::validateAvatar('avatar');
        $validName = AdminValidate::validateName($dataPost['name']);
        $validEmail = AdminValidate::validateEmail($dataPost['email'], $checkExistsEmail);
        $validPassword = AdminValidate::validatePassword($dataPost['password']);
        $validConfirmPassword = AdminValidate::validateConfirmPassword($dataPost['password'], $dataPost['confirm-password']);
        $error = array_merge($validAvatar, $validName, $validEmail, $validPassword, $validConfirmPassword);
        return $error;
    }

    public function validateEditAdmin($data, $dataPost, $checkExistsEmail)
    {
        $error = [];
        $validAvatar = AdminValidate::validateAvatar('avatar');
        $validName = AdminValidate::validateName($dataPost['name']);
        $validEmail = AdminValidate::validateEmail($dataPost['email'], $checkExistsEmail);
        $validPassword = AdminValidate::validatePassword($dataPost['password']);
        $validConfirmPassword = AdminValidate::validateConfirmPassword($dataPost['password'], $dataPost['confirm-password']);

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