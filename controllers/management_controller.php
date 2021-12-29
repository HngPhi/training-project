<?php
    require_once "base_controller.php";
    require_once "models/management.php";
    require_once "libraries/management_func.php";
    require_once "libraries/validate.php";

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
                    $check_login = check_login($_POST['email'], $_POST['password']);
                    if ($check_login == 1) {
                        $_SESSION['is_login'] = true;
                        $this->render('search');
                    } else {
                        $error['login'] = "Login information is incorrect";
                        $this->render('login', $error);
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
             * TH1: Trống cả 2
             * TH2: Tồn tại 1 trong 2 trường, hoặc cả 2
             */
            if (isset($_GET['search'])) {
                $email = $_GET['email'];
                $name = $_GET['name'];

                //Báo lỗi khi thực thi Search mà chưa điền thông tin
                $check_info = "";
                if(empty($_GET['name']) && empty($_GET['email'])){
//                    $data['fill_out'] = "Fill out Name or Email";
                    $list_admin = list_admin();
                    $this->render('search', $list_admin);
                }

                //Fill out 1 trong 2 trường hoặc cả 2
                else{
                    if((isset($_GET['email']) && empty($_GET['name']))){
                        $check_search = check_search_email($email);
                    }
                    if(empty($_GET['email']) && isset($_GET['name'])){
                        $check_search = check_search_name(name);
                    }
                    else{
                        $check_search = check_search($email, $name);
                    }
                    if(empty($check_search)){
                        $this->render('search', "No exists user");
                    }else{
                        $this->render('search', $check_search);
                    }
                }

            }
            $this->render('search');
        }

        function create(){
            if(isset($_POST['reset'])){
                $_POST['avatar'] = "";
                $_POST['name'] = "";
                $_POST['email'] = "";
                $_POST['password'] = "";
                $_POST['confirm-password'] = "";
                $_POST['role-type'] = "";
                $this->render('create');
            }

            if(isset($_POST['save'])){
//                if(!empty($_POST['avatar']) && !empty($_POST['email']) && !empty($_POST['name']) && !empty($_POST['password']) && !empty($_POST['confirm-password']) && !empty($_POST['role-type'])){
                    if(empty($_POST['avatar'])) $data['error-avatar'] = "Avatar is not blank";
                    if(empty($_POST['email'])) $data['error-email'] = "Email is not blank";
                    if(empty($_POST['name'])) $data['error-name'] = "Name is not blank";
                    if(empty($_POST['password'])) $data['error-password'] = "Password is not blank";
                    if(empty($_POST['confirm-password'])) $data['error-confirm-password'] = "Confirm-password is not blank";
                    if(empty($_POST['role-type'])) $data['error-role-type'] = "Role is not blank";

                    //Validate
                    if(!is_email($_POST['email'])) $data['error-email'] = "Email is incorrect format";
//                    if(!is_img($_POST['avatar'])) $data['error-avatar'] = "Avatar is incorrect format(.jpg, .png, .jpeg)";

                    //Check thông tin
                    if(check_exists_email($_POST['email']) > 0) $data['error-email'] = "Email already exists";
                    if($_POST['password'] != $_POST['confirm-password']) $data['error-confirm-password'] = "Confirm password is incorrect ";

                    //Insert dữ liệu
                    if(empty($data)){
                        $avatar = $_POST['avatar'];
                        $email = $_POST['email'];
                        $name = $_POST['name'];
                        $password = $_POST['password'];
                        $role = $_POST['role-type'];
                        $arr = ['avatar' => $avatar, 'email' => $email, 'name' => $name, 'password' => $password, 'role_type' => $role];
                        if(db_insert("admin", $arr)){
//                            $path = move_uploaded_file($_POST['avatar'], base_url($_POST['avatar']));
                            $data['alert-success'] = "Thêm dữ liệu thành công";
                            $this->render('create', $data);
                        }
                    }
                    else{
                        $this->render('create', $data);
                    }
                }
                else{
                    $data['alert-error'] = "Fill out FULL field";
                    $this->render('create', $data);
                }
//            }
            $this->render('create');
        }
    }
?>