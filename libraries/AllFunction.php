<?php
    function get_header(){
        return "views/layouts/header.php";
    }

    function get_footer(){
        return "views/layouts/footer.php";
    }

    function showArr($arr){
        echo "<pre>";
            print_r($arr);
        echo "</pre>";
    }
?>