<?php
    class DB
    {
        private static $instance = NULl;
        public static function getInstance() {
            if (!isset(self::$instance)) {
                try {
                    self::$instance = new PDO("mysql:host=LOCALHOST;dbname=basephp", USERNAME, PASSWORD);
                    self::$instance->exec("SET NAMES 'utf8'");
                } catch (PDOException $ex) {
                    die($ex->getMessage());
                    echo "Lỗi truy vấn";
                }
            }
            return self::$instance;
        }
    }