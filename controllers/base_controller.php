<?php
    class BaseController{
        protected $folder;

        function render($file, $data = array()){
            $view_file = "views/". $this->folder . "/". $file .".php";
            if(is_file($view_file)){
                require_once($view_file);
            }
            else{
                header("Location: index.php?controller=management&action=error");
            }
        }
    }
?>