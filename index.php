<?php
    require_once('libraries/database.php');
    db_connect();
//    require_once('libraries/management_func.php');
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
        $controller = 'management';
        $action = 'login';
    }

    require_once('routes.php');