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

    if (!function_exists('siteURL')) {
        function siteURL()
        {
            $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
                $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $domainName = $_SERVER['HTTP_HOST'];
            return $protocol . $domainName;
        }
    }

    if (!function_exists('getUrl')) {
        function getUrl($assetUrl)
        {
            $url = siteURL();
            return $url . '/' . $assetUrl;
        }
    }

    function checkConfirmPassword($password, $confirmPassword)
    {
        return $password == $confirmPassword ? true : false;
    }

    function getQuery()
    {
        $email = isset($_GET['email']) ? $_GET['email'] : "";
        $name = isset($_GET['name']) ? $_GET['name'] : "";
        $search = isset($_GET['search']) ? $_GET['search'] : "";
        $queries = "?email={$email}&name={$name}&search={$search}";
        if(isset($_GET['column'])){
            $queries .= "&column={$_GET['column']}";
        }
        if(isset($_GET['sort'])){
            $queries .= "&sort={$_GET['sort']}";
        }
        return $queries;
    }
?>