<?php
    require_once "connection.php";
    require_once "config/const.php";
    require_once "config/messages.php";
    require_once "libraries/AllFunction.php";
    require_once "libraries/AdminFunction.php";
    require_once "libraries/UserFunction.php";

    session_start();
    ob_start();

    if (isset($_GET['controller'])) {
        $controller = $_GET['controller'];
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        } else {
            $action = 'login';
        }
    } else {
        $controller = 'admin';
        $action = 'login';
    }

    require_once('routes.php');