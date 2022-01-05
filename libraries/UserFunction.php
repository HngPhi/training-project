<?php
    function check_login_user($email, $arr){
        foreach($arr as $value){
            if($value['email'] == $email) return true;
        }
        return false;
    }
?>