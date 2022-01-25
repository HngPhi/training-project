<?php
abstract class BaseValidate
{
    public function validateAvatar()
    {
        $error = "";
        if ($_FILES['avatar']['name'] != "") {
            $type_img = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            //Check đuôi file
            if (!in_array(strtolower($type_img), EXTENSION_IMAGE)) {
                $error = ERROR_IMAGE_INVALID;
            } else {
                //Check kích thước file(<20MB ~ 29.000.000Byte)
                $size_img = $_FILES['avatar']['size'];
                if ($size_img > MAXIMUM_SIZE_IMAGE) {
                    $error = ERROR_IMAGE_MAX_SIZE;
                }
            }
        }
        return $error;
    }

    public function validateName($name)
    {
        $error = "";
        $pattern = "/^([a-zA-Z0-9ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+)$/";
        if (strlen($name) < MINIMUM_LENGTH_NAME || strlen($name) > MAXIMUM_LENGTH_NAME) {
            $error = ERROR_LENGTH_NAME;
        } else {
            if (!preg_match($pattern, $name, $matches)) {
                $error = ERROR_VALID_NAME;
            }
        }
        return $error;
    }

    public function validateEmail($email, $checkExistsEmail)
    {
        $error = "";
        $pattern = "/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/";
        if (strlen($email) < MINIMUM_LENGTH_EMAIL || strlen($email) > MAXIMUM_LENGTH_EMAIL) {
            $error = ERROR_LENGTH_EMAIL;
        } else {
            if (!preg_match($pattern, $email, $matches)) {
                $error = ERROR_VALID_EMAIL;
            } else {
                if (!$checkExistsEmail) {
                    $error = ERROR_EMAIL_EXISTS;
                }
            }
        }
        return $error;
    }

    public function validatePassword($password)
    {
        $error = "";
        $pattern = "/^([\w_\.!@#$%^&*()-]+)$/";
        if (strlen($password) < MINIMUM_LENGTH_PASSWORD || strlen($password) > MAXIMUM_LENGTH_PASSWORD) {
            $error = ERROR_LENGTH_PASSWORD;
        } else {
            if (!preg_match($pattern, $password, $matches)) {
                $error = ERROR_VALID_PASSWORD;
            }
        }
        return $error;
    }

    public function getErrorAvatar($avatar)
    {
        $error = [];
        $validateAvatar = BaseValidate::validateAvatar($avatar);
        if (empty($avatar)) {
            $error['error-avatar'] = ERROR_EMPTY_AVATAR;
        } else {
            if (!empty($validateAvatar)) {
                $error['error-avatar'] = $validateAvatar;
            }
        }
        return $error;
    }

    public function getErrorName($name)
    {
        $error = [];
        $validateName = BaseValidate::validateName($name);
        if (empty($name)) {
            $error['error-name'] = ERROR_EMPTY_NAME;
        } else {
            if (!empty($validateName)) {
                $error['error-name'] = $validateName;
            }
        }
        return $error;
    }

    public function getErrorEmail($email, $checkExistsEmail)
    {
        $error = [];
        $validateEmail = BaseValidate::validateEmail($email, $checkExistsEmail);
        if (empty($email)) {
            $error['error-email'] = ERROR_EMPTY_EMAIL;
        } else {
            if (!empty($validateEmail)) {
                $error['error-email'] = $validateEmail;
            }
        }
        return $error;
    }

    public function getErrorPassword($password)
    {
        $error = [];
        $validatePassword = BaseValidate::validatePassword($password);
        if (empty($password)) {
            $error['error-password'] = ERROR_EMPTY_PASSWORD;
        } else {
            if (!empty($validatePassword)) {
                $error['error-password'] = $validatePassword;
            }
        }
        return $error;
    }

    public function getErrorConfirmPassword($password, $confirmPassword)
    {
        $error = [];
        if (empty($confirmPassword)) {
            $error['error-confirm-password'] = ERROR_EMPTY_CONFIRM_PASSWORD;
        }else{
            if(!checkConfirmPassword($password, $confirmPassword)){
                $error['error-confirm-password'] = ERROR_CONFIRM_PASSWORD;
            }
        }
        return $error;
    }
}

?>