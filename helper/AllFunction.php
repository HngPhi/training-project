<?php
    function getHeader(){
        require_once "views/layouts/header.php";
    }

    function getFooter(){
        require_once "views/layouts/footer.php";
    }

    function showArr($arr){
        echo "<pre>";
            print_r($arr);
        echo "</pre>";
    }

//    function checkLogin($email, $password, $arr){
//        foreach($arr as $value){
//            if($value['email'] == $email && $value['password'] == $password) return true;
//        }
//        return false;
//    }
?>