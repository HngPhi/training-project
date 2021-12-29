<?php
    function is_email($string){
        $pattern = "/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9]+\.[a-zA-Z0-9]{2,6}$/";
        return preg_match($pattern, $string, $matches) ? true : false;
    }

    function is_password($string){
        $pattern = "/^([\w_\.!@#$%^&*()-]+){5,30}$/";
        return preg_match($pattern, $string, $matches) ? true : false;
    }

    function is_name($string){
        $pattern = "/^[a-zA-Z0-9\._-]{3,50}$/";
        return preg_match($pattern, $string, $matches) ? true : false;
    }

    function is_img($string){
        $str_split = pathinfo($string, PATHINFO_EXTENSION);
        if($str_split == "png" || $str_split == "jpeg" || $str_split == "jpg"){
            return true;
        }
        else return false;
    }
?>