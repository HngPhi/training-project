<?php
    function base_url($uri){
        $path = "http://localhost/BasePHP/".$uri;
        echo $path;
    }

    function get_header(){
        return "views/layouts/header.php";
    }

    function get_footer(){
        return "views/layouts/footer.php";
    }

     function check_login_admin($email, $password, $arr){
        foreach($arr as $value){
            if($value['email'] == $email && $value['password'] == $password) return true;
        }
        return false;
    }
?>
