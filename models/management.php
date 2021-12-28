<?php
    require_once "libraries/database.php";
    function list_admin(){
        $list_admin = db_fetch_array("SELECT * FROM admin ");
        return $list_admin;
    }

    function check_info($email, $name, $temp){
        return $check_search = db_fetch_array("SELECT * FROM admin WHERE `name` LIKE '%$name%' {$temp} `email` LIKE '%$email%'");
    }
?>