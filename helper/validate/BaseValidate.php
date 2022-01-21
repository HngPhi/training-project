<?php
abstract class BaseValidate{
    public function validateAvatar()
    {
        $error = "";
        if ($_FILES['avatar']['name'] != "") {
            $type_img = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            //Check đuôi file
            if (!in_array(strtolower($type_img), EXTENSION_IMAGE)) $error = ERROR_IMAGE_INVALID;
            else {
                //Check kích thước file(<20MB ~ 29.000.000Byte)
                $size_img = $_FILES['avatar']['size'];
                if ($size_img > MAXIMUM_SIZE_IMAGE) $error = ERROR_IMAGE_MAX_SIZE;
            }
        }
        return $error;
    }

    public function validateName($name)
    {
        $error = "";
        $pattern = "/^([a-zA-Z0-9ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+)$/";
        if(strlen($name)<MINIMUM_LENGTH_NAME || strlen($name)>MAXIMUM_LENGTH_NAME){
            $error = ERROR_LENGTH_NAME;
        }
        else{
            !preg_match($pattern, $name, $matches) ? $error = ERROR_VALID_NAME : "";
        }
        return $error;
    }

    public function validateEmail($email)
    {
        $error = "";
        $pattern = "/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/";
        if(strlen($email)<MINIMUM_LENGTH_EMAIL || strlen($email)>MAXIMUM_LENGTH_EMAIL){
            $error = ERROR_LENGTH_EMAIL;
        }
        else{
            !preg_match($pattern, $email, $matches) ? $error = ERROR_VALID_EMAIL : "";
        }
        return $error;
    }

    public function validatePassword($password)
    {
        $error = "";
        $pattern = "/^([\w_\.!@#$%^&*()-]+)$/";
        if(strlen($password)<MINIMUM_LENGTH_PASSWORD || strlen($password)>MAXIMUM_LENGTH_PASSWORD){
            $error = ERROR_LENGTH_PASSWORD;
        }else{
            !preg_match($pattern, $password, $matches) ? $error = ERROR_VALID_PASSWORD : "";
        }
        return $error;
    }

    public function getErrorAvatar($avatar){
        $error = [];
        if(empty($avatar)) $error['error-avatar'] = ERROR_EMPTY_AVATAR;
        else{
            empty(BaseValidate::validateAvatar($avatar)) ? "" : $error['error-avatar'] = BaseValidate::validateAvatar($avatar);
        }
        return $error;
    }

    public function getErrorName($name){
        $error = [];
        if(empty($name)) $error['error-name'] = ERROR_EMPTY_NAME;
        else{
            empty(BaseValidate::validateName($name)) ? "" : $error['error-name'] = BaseValidate::validateName($name);
        }
        return $error;
    }

    public function getErrorEmail($email){
        $error = [];
        if(empty($email)) $error['error-email'] = ERROR_EMPTY_EMAIL;
        else{
            empty(BaseValidate::validateEmail($email)) ? "" : $error['error-email'] = BaseValidate::validateEmail($email);
        }
        return $error;
    }

    public function getErrorPassword($password){
        $error = [];
        if(empty($password)) $error['error-password'] = ERROR_EMPTY_PASSWORD;
        else{
            empty(BaseValidate::validatePassword($password)) ? "" : $error['error-password'] = BaseValidate::validatePassword($password);
        }
        return $error;
    }
}
?>