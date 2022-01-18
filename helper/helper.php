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
?>