<?php
    function base_url($uri){
        $path = "http://localhost/BasePHP/".$uri;
        echo $path;
//        if(file_exists($path)){
//            echo $path;
//        }
//        else{
//            echo "Đường dẫn {$path} không tồn tại";
//        }
    }

    function get_header(){
        return "views/layouts/header.php";
    }

    function get_footer(){
        return "views/layouts/footer.php";
    }

    function check_search($isset, $arr, $data, $error){
        foreach($arr as $value){
            if($value[$isset] == $_GET[$isset]){
                $data[] = $value;
            }else{
//                return $error = "No exists user";
                $data = "Rong";
            }
        }
        return $data;
    }
