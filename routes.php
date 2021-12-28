<?php
    $controllers = array(
        'management' => ['login', 'logout', 'search', 'create', 'edit'],
    );

    if(!array_key_exists($controller, $controllers) || !in_array($action, $controllers[$controller])){
        $controller = "management";
        $action = "error";
    }

    include_once "controllers/". $controller . "_controller.php";

    $temp = str_replace("_", "", ucwords($controller)).'Controller';
    $controller = new $temp;
    $controller->$action();
?>