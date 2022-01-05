<?php
require_once "BaseController.php";
require_once "models/UserModel.php";

class UserController extends BaseController{
    function __construct()
    {
        $this->folder = "user";
    }

    function error()
    {
        $this->render('error');
    }

    function login()
    {
        $error = array();
        if(isset($_POST['login'])){
            if(empty($_POST['email'])) $error['error-empty-email'] = ERROR_EMPTY_EMAIL;
//            if(empty($_POST['password'])) $error['error-empty-password'] = ERROR_EMPTY_PASSWORD;
            if(empty($error)){
                $get_list_user = UserModel::getListUser();
                if(check_login_user($_POST['email'], $get_list_user)){
                    $_SESSION['loginUser'] = [
                        'is-login' => 1,
                        'email' => $_POST['email'],
                    ];
                    header("Location: ".URL_DETAIL_USER);
                }
                else{
                    $error['error-login'] = ERROR_LOGIN;
                    $this->render('login', $error);
                }
            }else{
                $this->render('login', $error);
            }
        }else{
            $this->render('login');
        }
    }

    function logout()
    {
        if (isset($_SESSION['loginUser'])) {
            session_destroy();
            header("Location: views/user/login.php");
        }
    }

    function detail(){
        $data = UserModel::getInfoUserByEmail($_SESSION['loginUser']['email']);
        $this->render('detail', $data);
    }

    function search(){
        $data = UserModel::getListUser();
        $sort = "ASC";

        if(isset($_GET['sortID'])){
            if($_GET['sortID'] == $sort) $sort = "DESC";
            $data = UserModel::sortIDUser($_GET['sortID']);
        }

        if(isset($_GET['sortName'])){
            if($_GET['sortName'] == $sort) $sort = "DESC";
            $data = UserModel::sortNameUser($_GET['sortName']);
        }

        if(isset($_GET['sortEmail'])){
            if($_GET['sortEmail'] == $sort) $sort = "DESC";
            $data = UserModel::sortEmailUser($_GET['sortEmail']);
        }

        if(isset($_GET['sortStatus'])){
            if($_GET['sortStatus'] == $sort) $sort = "DESC";
            $data = UserModel::sortStatusUser($_GET['sortStatus']);
        }

        /**
         * Chức năng RESET
         */
        if (isset($_GET['reset'])) {
            header("Location: ".URL_SEARCH_USER);
        }

        /**
         * Chức năng SEARCH
         */
        if (isset($_GET['search'])) {
            if (empty($_GET['name']) && empty($_GET['email'])) {
                header("Location: ".URL_SEARCH_USER);
            }
            else {
                $data = UserModel::getSearchUser($_GET['email'], $_GET['name']);
                if(empty($data)) $data = NO_EXISTS_USER;
            }
        }
        $arr = [
            'data' => $data,
            'sort' => $sort,
        ];
        $this->render('search', $arr);
    }

    function edit(){
        $id = $_GET['id'];
        $data = UserModel::getInfoUserByID($id);
        $error = array();

        if(isset($_POST['save'])){
            $avatar = $_FILES['avatar']['name'];
            $name =  $_POST['name'];
            $email = $_POST['email'];
//            $password = $_POST['password'];
//            $confirm_password = $_POST['confirm-password'];
            $status = $_POST['status'];

            $validImg = UserModel::validateImgUser($avatar);
            $validName = UserModel::validateNameUser($name);
            $validEmail = UserModel::validateEmailUser($email);
//            $validPass = UserModel::validatePasswordUser($password);
//            $checkConfirmPass = UserModel::checkConfirmPasswordUser($password, $confirm_password);

            if(!empty($avatar)) $error = array_merge($error, $validImg);
            else $avatar = $data['avatar'];

            if($name!= $data['name']) $error = array_merge($error, $validName);

            if($email != $data['email']){
                if(UserModel::checkExistsEmailUser($email) > 0) $error['error-email'] = ERROR_EMAIL_EXISTS;
                $error = array_merge($error, $validEmail);
            }

//            if(!empty($password)) $error = array_merge($error, $validPass, $checkConfirmPass);
//            else $password = $data['password'];

            if(empty($error)){
                $upd_id_user = UserModel::getInfoAdminByEmail($_SESSION['loginAdmin']['email']);
//                echo "Arr: ";
//                showArr($upd_id_user);
                $arr = array(
                    'avatar' => $avatar,
                    'name' => $name,
                    'email' => $email,
//                    'password' => md5($password),
                    'status' => $status,
                    'upd_id' => $upd_id_user['id'],
                );

                $upload_file = UPLOADS_USER . $_FILES['avatar']['name'];

                if (UserModel::update('user', $arr, "`id` = '{$id}'")) {
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file);
                    $_SESSION['alert']['update-success'] = UPDATE_SUCCESSFUL." with ID = {$id}";
                    header("Location: ".URL_SEARCH_USER);
                }
            }
        }

        $temp = array(
            'error' => $error,
            'data' => $data,
        );

        $this->render('edit', $temp);
    }

    function delete(){
        $id = $_GET['id'];
        if(UserModel::delete('user', "`id`={$id}")); $_SESSION['alert']['delete-success'] = DELETE_SUCCESSFUL." with ID = {$id}";
        header("Location: ".URL_SEARCH_USER);
    }
}
?>