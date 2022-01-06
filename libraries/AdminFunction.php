<?php
    //Check Login
     function check_login_admin($email, $password, $arr){
        foreach($arr as $value){
            if($value['email'] == $email && $value['password'] == $password) return true;
        }
        return false;
    }
?>
