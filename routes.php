<?php
    $controllers = array(
        'admin' => ['login', 'logout', 'search', 'create', 'edit', 'delete'],
    );

    if(!array_key_exists($controller, $controllers) || !in_array($action, $controllers[$controller])){
        $controller = "admin";
        $action = "error";
    }

    include_once "controllers/". ucfirst($controller) . "Controller.php";

    $temp = str_replace("_", "", ucwords($controller)).'Controller';
    $controller = new $temp;
    $controller->$action();
?>