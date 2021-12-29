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

    function get_img($image){
        $path = "public/images/$image";
        if(isset($path)) return $path;
        else echo "Image link does not exist";
    }
?>
