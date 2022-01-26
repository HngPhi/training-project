<?php

abstract class BaseValidate
{
    public function validateAvatar($avatar)
    {
        $error = [];
        if ($_FILES["{$avatar}"]["name"] == "") {
            $error['error-avatar'] = ERROR_EMPTY_AVATAR;
        } else {
            $type_img = pathinfo($_FILES["{$avatar}"]['name'], PATHINFO_EXTENSION);
            //Check đuôi file
            if (!in_array(strtolower($type_img), EXTENSION_IMAGE)) {
                $error['error-avatar'] = ERROR_IMAGE_INVALID;
            } else {
                //Check kích thước file(<20MB ~ 29.000.000Byte)
                $size_img = $_FILES["{$avatar}"]["size"];
                if ($size_img > MAXIMUM_SIZE_IMAGE) {
                    $error['error-avatar'] = ERROR_IMAGE_MAX_SIZE;
                }
            }
        }
        return $error;
    }

    public function validateName($name)
    {
        $error = [];
        $pattern = "/^([a-zA-Z0-9ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+)$/";
        if (empty($name)) {
            $error['error-name'] = ERROR_EMPTY_NAME;
        } elseif (strlen($name) < MINIMUM_LENGTH_NAME || strlen($name) > MAXIMUM_LENGTH_NAME) {
            $error['error-name'] = ERROR_LENGTH_NAME;
        } elseif (!preg_match($pattern, $name, $matches)) {
            $error['error-name'] = ERROR_VALID_NAME;
        }
        return $error;
    }

    public function validateEmail($email, $checkExistsEmail)
    {
        $error = [];
        $pattern = "/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/";
        if (empty($email)) {
            $error['error-email'] = ERROR_EMPTY_EMAIL;
        } elseif (strlen($email) < MINIMUM_LENGTH_EMAIL || strlen($email) > MAXIMUM_LENGTH_EMAIL) {
            $error['error-email'] = ERROR_LENGTH_EMAIL;
        } elseif (!preg_match($pattern, $email, $matches)) {
            $error['error-email'] = ERROR_VALID_EMAIL;
        } elseif (!$checkExistsEmail) {
            $error['error-email'] = ERROR_EMAIL_EXISTS;
        }
        return $error;
    }

    public function validatePassword($password)
    {
        $error = [];
        $pattern = "/^([\w_\.!@#$%^&*()-]+)$/";
        if (empty($password)) {
            $error['error-password'] = ERROR_EMPTY_PASSWORD;
        } elseif (strlen($password) < MINIMUM_LENGTH_PASSWORD || strlen($password) > MAXIMUM_LENGTH_PASSWORD) {
            $error['error-password'] = ERROR_LENGTH_PASSWORD;
        } elseif (!preg_match($pattern, $password, $matches)) {
            $error['error-password'] = ERROR_VALID_PASSWORD;
        }
        return $error;
    }

    public function validateConfirmPassword($password, $confirmPassword)
    {
        $error = [];
        if (empty($confirmPassword)) {
            $error['error-confirm-password'] = ERROR_EMPTY_CONFIRM_PASSWORD;
        } elseif (!checkConfirmPassword($password, $confirmPassword)) {
            $error['error-confirm-password'] = ERROR_CONFIRM_PASSWORD;
        }
        return $error;
    }
}

?>