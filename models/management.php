<?php
    require_once "libraries/database.php";
    function list_admin(){
        return $list_admin = db_fetch_array("SELECT * FROM admin WHERE `del_flag` = 0");
    }

    function check_login($email, $password){
        return $check_login = db_num_rows("SELECT * FROM admin WHERE `email` LIKE '$email' AND `password` LIKE '$password'");
    }

    function check_search_email($email){
        return $check_search = db_fetch_array("SELECT * FROM admin WHERE `email` LIKE '%$email%' AND `del_flag` = 0");
    }

    function check_search_name($name){
        return $check_search = db_fetch_array("SELECT * FROM admin WHERE `name` LIKE '%$name%' AND `del_flag` = 0");
    }

    function check_search($email, $name){
        return $check_search = db_fetch_array("SELECT * FROM admin WHERE `email` LIKE '%$email%' AND `name` LIKE '%$name%' AND `del_flag` = 0");
    }

//    function check_exists_email($email){
//        return $check_search = db_num_rows("SELECT * FROM admin WHERE `email` LIKE '$email'");
//    }

    function get_info_id($id){
        return $get_info_id = db_fetch_array("SELECT * FROM admin WHERE `id` = '{$id}'");
    }
?>
