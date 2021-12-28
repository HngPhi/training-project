<?php
    require_once "base_controller.php";
    require_once "models/management.php";
    require_once "libraries/management_func.php";

    class ManagementController extends BaseController
    {

        function __construct()
        {
            $this->folder = "management";
        }

        function error()
        {
            $this->render('error');
        }

        function login()
        {
            $error = array();
            if (isset($_POST['login'])) {
                if (empty($_POST['email'])) {
                    $error['email'] = "Email can not blank";
                }
                if (empty($_POST['password'])) {
                    $error['password'] = "Password can not blank";
                }
                if (empty($error)) {
                    $check_login = list_admin();
                    foreach ($check_login as $item) {
                        if ($_POST['email'] == $item['email'] && $_POST['password'] == $item['password']) {
                            $_SESSION['is_login'] = true;
//                            header("Location: views/management/search.php");
                            $this->render('search');
                        } else {
                            $error['login'] = "Login information is incorrect";
                            $this->render('login', $error);
                        }
                    }
                } else {
                    $this->render('login', $error);
                }
            } else {
                $this->render('login');
            }
        }

        function logout(){
            if(isset($_SESSION['is_login'])){
                unset($_SESSION['is_login']);
                header("Location: views/management/login.php");
            }
        }

        function search()
        {
            $temp = array();

            /**
             * Chức năng RESET
             */
            if (isset($_GET['reset'])) {
                isset($_GET['email']) ? $_GET['email'] = "" : "";
                isset($_GET['name']) ? $_GET['name'] = "" : "";
                $this->render('search');
            }

            /**
             * Chức năng SEARCH
             * TH1: Tìm kiếm theo EMAIL(trống NAME) hoặc Tìm kiếm theo NAME(trống EMAIL)
             * TH2: Tìm kiếm theo cả 2
             */
            if (isset($_GET['search'])) {
                $email = $_GET['email'];
                $name = $_GET['name'];

                //Báo lỗi khi thực thi Search mà chưa điền thông tin
                $check_info = "";
                if(empty($_GET['name']) && empty($_GET['email'])){
                    $data['fill_out'] = "Fill out Name or Email";
                    $this->render('search', $data);
                }

                //Fill out 1 trong 2 trường hoặc cả 2
                else{
                    if((isset($_GET['email']) && empty($_GET['name'])) || (empty($_GET['email']) && isset($_GET['name']))){
                        $check_info = check_info($email, $name, "OR");
                    }else{
                        $check_info = check_info($email, $name, "AND");
                    }
                    if(empty($check_info)){
                        $this->render('search', "No exists user");
                    }else{
                        $this->render('search', $check_info);
                    }
                }

            }
            $this->render('search');
        }

        function create(){
            $this->render('create');
        }
    }
?>