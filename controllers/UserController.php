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

        if (isset($_GET['reset'])) {
            header("Location: ".URL_SEARCH_USER);
        }

        $email = isset($_GET['email']) ? $_GET['email'] : "";
        $name = isset($_GET['name']) ? $_GET['name'] : "";
        $search = isset($_GET['search']) ? $_GET['search'] : "";
        $add_url_search = "&email={$email}&name={$name}&search={$search}";

        /**
         * Pagging
         * 5 tham số:
         * -- $record_per_page: Số bản ghi mỗi trang
         * -- $total_record: Tổng số bản ghi
         * -- $total_page: Tổng số trang
         * -- $start: Chỉ số bản ghi bắt đầu mỗi trang
         * -- $page: Chỉ số trang hiện tại
         */
        $record_per_page = RECORD_PER_PAGE;
        $total_record = UserModel::getTotalRow('user');
        $total_page = ceil($total_record/$record_per_page);
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start = ($page-1)*$record_per_page;
        $previous = $page;
        $next = $page;

        if($page > 1) $previous = $page - 1;
        if($page < $total_page) $next = $page + 1;

        /**
         * Sort
         */
        $sort = "DESC";
        $getSort = "";
        if(isset($_GET['sort'])){
            $getSort = $_GET['sort'];
            if($_GET['sort'] == $sort) $sort = "ASC";
        }
        $column = isset($_GET['column']) ? $_GET['column'] : "id";
        $add_url_pagging = $search."&column=".$column."&sort=".$getSort;

        /**
         * SQL
         */
        $where = "WHERE `email` LIKE '%{$email}%' AND `name` LIKE '%{$name}%'";
        $orderBy = "ORDER BY `{$column}` {$getSort}";
        $limit = "LIMIT $start, $record_per_page";

        $data = UserModel::getInfoSearch('user', $where, $orderBy, $limit);
        if(empty($data)) $data = NO_EXISTS_USER;

        $arr = [
            'data' => $data,
            'sort' => $sort,
            'page' => $page,
            'total_page' => $total_page,
            'previous' => $previous,
            'next' => $next,
            'add_url_search' => $add_url_search,
            'add_url_pagging' => $add_url_pagging,
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