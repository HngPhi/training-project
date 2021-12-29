<?php
    require_once "libraries/database.php";
    function list_admin(){
        $list_admin = db_fetch_array("SELECT * FROM admin ");
        return $list_admin;
    }

    function check_login($email, $password){
        return $check_login = db_num_rows("SELECT * FROM admin WHERE `email` LIKE '$email' AND `password` LIKE '$password'");
    }

    function check_search_email($email){
        return $check_search = db_fetch_array("SELECT * FROM admin WHERE `email` LIKE '%$email%'");
    }

    function check_search_name($name){
        return $check_search = db_fetch_array("SELECT * FROM admin WHERE `name` LIKE '%$name%'");
    }

    function check_search($email, $name){
        return $check_search = db_fetch_array("SELECT * FROM admin WHERE `email` LIKE '%$email%' AND `name` LIKE '%$name%'");
    }

    function check_exists_email($email){
        return $check_search = db_num_rows("SELECT * FROM admin WHERE `email` LIKE '$email'");
    }
?>
