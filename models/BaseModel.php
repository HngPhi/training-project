<?php
require_once "DBInterface.php";

abstract class BaseModel implements DBInterface
{
    protected $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function getIdAdmin($str)
    {
        return $this->db->query("SELECT `id` FROM `admin` WHERE `email` LIKE '{$str}' AND `del_flag` = ". ACTIVED)->fetch();
    }

    //CRUD
    public function insert($data = [])
    {
        $ins = [
            'ins_id' => $_SESSION['admin']['login']['id'],
            'ins_datetime' => date("Y-m-d H:i:s a"),
        ];
        $data = array_merge($data, $ins);

        //INSERT INTO `admin` (``, ``, ``, ``) VALUES('', '', '', '');
        $fields = "";
        $values = "";
        foreach ($data as $key => $value) {
            $fields .= "`" . $key . "`, ";
            $values .= "'" . $value . "', ";
        }

        $fields = substr($fields, 0, -2);
        $values = substr($values, 0, -2);

        $sql = "INSERT INTO $this->table ($fields) VALUES($values)";
        $arr = $this->db->query($sql);
        return $arr ? true : false;
    }

    public function update($data = [], $where = "")
    {
        $ins = [
            'upd_id' => $_SESSION['admin']['login']['id'],
            'upd_datetime' => date("Y-m-d H:i:s a"),
        ];
        $data = array_merge($data, $ins);

        $sql = "";
        foreach ($data as $field => $value) {
            $sql .= "{$field} = '{$value}', ";
        }
        $sql = substr($sql, 0, -2);

        $sql = "UPDATE {$this->table} SET $sql WHERE $where";;
        $arr = $this->db->query($sql);
        return $arr ? true : false;
    }

    public function delete($where = "")
    {
        // TODO: Implement delete() method.
        $sql = "UPDATE $this->table SET `del_flag` = '" . BANNED . "' WHERE $where";
        $query = $this->db->query($sql);
        return $query ? true : false;
    }

    //Pagging
    public function getTotalRow($where)
    {
        return $this->db->query("SELECT * FROM `{$this->table}` $where")->rowCount();
    }

    public function getInfoSearch($where, $orderBy, $limit)
    {
        return $this->db->query("SELECT * FROM `{$this->table}` $where $orderBy $limit")->fetchAll();
    }

    public function checkLength($str, $minLengthStr, $maxLengthStr)
    {
        if (!empty($str)) return (strlen($str) < $minLengthStr || strlen($str) > $maxLengthStr) ? false : true;
    }

    public function checkLogin($email, $password)
    {
        $arr = $this->db->query("SELECT `email`, `password` FROM `{$this->table}` WHERE `email` LIKE '{$email}' AND `password` LIKE '{$password}' AND `del_flag` = " . ACTIVED)->rowCount();
        return $arr == 1 ? true : false;
    }

    public function checkConfirmPassword($password, $confirm_password)
    {
        $data = array();
        if (isset($password)) {
            if ($password != $confirm_password) $data["error-confirm-password"] = ERROR_CONFIRM_PASSWORD;
        }
        return $data;
    }

    //Validate
    public function validateEmail($email)
    {
        $data = array();
        $pattern = "/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9]+\.[a-zA-Z0-9]{2,6}$/";
        if (!empty($email)) {
            if (!preg_match($pattern, $email, $matches)) $data['error-email'] = ERROR_VALID_EMAIL;
        }
        return $data;
    }

    public function validatePassword($password)
    {
        $data = array();
        $pattern = "/^([\w_\.!@#$%^&*()-]+)$/";
        if (!empty($password)) {
            if (!preg_match($pattern, $password, $matches)) $data['error-password'] = ERROR_VALID_PASSWORD;
        }
        return $data;
    }

    public function validateName($name)
    {
        $data = array();
        $pattern = "/^([a-zA-Z0-9ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+)$/";
        if (!empty($name)) {
            if (!preg_match($pattern, $name, $matches)) $data['error-name'] = ERROR_VALID_NAME;
        }
        return $data;
    }

    public function validateImg()
    {
        $data = array();
        if ($_FILES['avatar']['name'] != "") {
            $type_allow = ['jpg', 'jpeg', 'png', 'gif'];
            $type_img = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            //Check đuôi file
            if (!in_array(strtolower($type_img), $type_allow)) $data["error-avatar"] = IMAGE_INVALID;
            else {
                //Check kích thước file(<20MB ~ 29.000.000Byte)
                $size_img = $_FILES['avatar']['size'];
                if ($size_img > MAXIMUM_SIZE_IMAGE) $data["error-avatar"] = ERROR_IMAGE_MAX_SIZE;
            }
        }
        return $data;
    }
}