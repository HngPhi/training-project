<?php
require_once "BaseController.php";
require_once "models/AdminModel.php";

class AdminController extends BaseController
{

    function __construct()
    {
        $this->folder = "admin";
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
            if(empty($_POST['password'])) $error['error-empty-password'] = ERROR_EMPTY_PASSWORD;
            if(empty($error)){
                if(AdminModel::checkLogin('admin', $_POST['email'], md5($_POST['password']))){
                    $_SESSION['admin']['login'] = [
                      'is_login' => 1,
                      'email' => $_POST['email'],
                    ];
                    $get_role = AdminModel::getRoleAdmin($_SESSION['admin']['login']['email']);
                    $_SESSION['admin']['role_type'] = $get_role['role_type'];
                    if($get_role['role_type'] == 2) header("Location: search");
                    else header("Location: https://vdhp.com/user/search");
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
        session_destroy();
        header("Location: login");
    }

    function search()
    {
        if (isset($_GET['reset'])) {
            header("Location: management/search");
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

        $where = "WHERE `email` LIKE '%{$email}%' AND `name` LIKE '%{$name}%' AND `del_flag` = ".DEL_FLAG_0;

        $record_per_page = RECORD_PER_PAGE;
        $total_record = AdminModel::getTotalRow('admin', $where);
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
        $add_url_pagging = $add_url_search."&column=".$column."&sort=".$getSort;

        /**
         * SQL
         */
        $orderBy = "ORDER BY `{$column}` {$getSort}";
        $limit = "LIMIT $start, $record_per_page";

        $data = AdminModel::getInfoSearch('admin', $where, $orderBy, $limit);
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

    function create()
    {
        $data = array();

        if (isset($_POST['reset'])) {
            header("Location: create");
        }

        if (isset($_POST['save'])) {
            /**
             * 1, Empty
             * 2, Validate
             * 3, Check email - password
             * 4, Upload file
             * 5, Save
             */

            // 1-2, Empty - Validate
            if($_FILES['avatar']['name'] == "") $data['error-avatar'] = ERROR_EMPTY_AVATAR;
            if(empty($_POST['email'])) $data['error-email'] = ERROR_EMPTY_EMAIL;
            if(empty($_POST['name'])) $data['error-name'] = ERROR_EMPTY_NAME;
            if(empty($_POST['password'])) $data['error-password'] = ERROR_EMPTY_PASSWORD;
            if(empty($_POST['confirm-password'])) $data['error-confirm-password'] = ERROR_EMPTY_CONFIRM_PASSWORD;

            $checkLengthEmail = AdminModel::checkLengthEmail($_POST['email']);
            $checkLengthName = AdminModel::checkLengthName($_POST['name']);
            $checkLengthPassword = AdminModel::checkLengthPassword($_POST['password']);

            $validEmail = AdminModel::validateEmail($_POST['email']);
            $validName = AdminModel::validateName($_POST['name']);
            $validPassword = AdminModel::validatePassword($_POST['password']);
            $validImg = AdminModel::validateImg();

            $data = array_merge($data, $checkLengthEmail, $checkLengthName, $checkLengthPassword, $validEmail, $validName, $validPassword, $validImg);

            // 3, Check thông tin EMAIL và PASSWORD
            if (AdminModel::checkExistsEmailAdmin($_POST['email']) > 0) $data['error-email'] = ERROR_EMAIL_EXISTS;
            if ($_POST['password'] != $_POST['confirm-password']) $data['error-confirm-password'] = ERROR_CONFIRM_PASSWORD;

            /* 4, Upload file
            * - B1: Check đuôi file
            * - B2: Kiểm tra dung lượng ảnh
            * - B3: Chuyển vào thư mục lưu ảnh
            */

            // Tạo thư mục chứa ảnh
            $upload_file = UPLOADS_ADMIN . $_FILES['avatar']['name'];

            //Insert dữ liệu
            $ins_id_admin = AdminModel::getIdAdmin($_SESSION['admin']['login']['email']);

            if (empty($data)) {
                $arr = array(
                    'avatar' => $_FILES['avatar']['name'],
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'password' => md5($_POST['password']),
                    'role_type' => $_POST['role_type'],
                    'ins_id' => $ins_id_admin['id'],
                    'ins_datetime' => date("Y-m-d H:i:s a"),
                );
                if (AdminModel::insert('admin', $arr)) {
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file);
                    $_SESSION['admin']['upload'] = $upload_file;
                    $data['alert-success'] = INSERT_SUCCESSFUL;
                }
            }
        }
        $this->render('create', $data);
    }

    function edit()
    {
        /**
         * Change:
         *      Change Avatar, Name -> Validate
         *      Change Email -> Validate + Check email exists
         * Isset Password -> Validatae -> Check confirm
         * Update
         */

        $id = $_GET['id'];
        $data = AdminModel::getInfoAdmin($id);
        $error = array();

        if(isset($_POST['save'])){
            $avatar = $_FILES['avatar']['name'];
            $name =  $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm-password'];
            $role_type = $_POST['role_type'];

            $validImg = AdminModel::validateImg($avatar);
            $validName = AdminModel::validateName($name);
            $validEmail = AdminModel::validateEmail($email);
            $validPass = AdminModel::validatePassword($password);
            $checkConfirmPass = AdminModel::checkConfirmPassword($password, $confirm_password);

            if(!empty($avatar)) $error = array_merge($error, $validImg);
            else $avatar = $data['avatar'];

            if($name!= $data['name']) $error = array_merge($error, $validName);

            if($email != $data['email']){
                if(AdminModel::checkExistsEmailAdmin($email) > 0) $error['error-email'] = ERROR_EMAIL_EXISTS;
                $error = array_merge($error, $validEmail);
            }

            if(!empty($password)) $error = array_merge($error, $validPass, $checkConfirmPass);
            else $password = $data['password'];

            $checkLengthEmail = AdminModel::checkLengthEmail($_POST['email']);
            $checkLengthName = AdminModel::checkLengthName($_POST['name']);
            $checkLengthPassword = AdminModel::checkLengthPassword($_POST['password']);

            $error = array_merge($error, $checkLengthEmail, $checkLengthName, $checkLengthPassword);

            if(empty($error)){
                $upd_id = AdminModel::getIdAdmin($_SESSION['admin']['login']['email']);
                $arr = array(
                    'avatar' => $avatar,
                    'name' => $name,
                    'email' => $email,
                    'password' => md5($password),
                    'role_type' => $role_type,
                    'upd_id' => $upd_id['id'],
                    'upd_datetime' => date("Y-m-d H:i:s a"),
                );

                $upload_file = UPLOADS_ADMIN . $_FILES['avatar']['name'];

                if (AdminModel::update('admin', $arr, "`id` = '{$id}'")) {
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file);
                    $_SESSION['alert']['update-success'] = UPDATE_SUCCESSFUL." with ID = {$id}";
                    header("Location: ".URL_SEARCH_ADMIN);
                }
            }
        }

        $temp = array(
            'error' => $error,
            'data' => $data,
        );

        $this->render('edit', $temp);
    }

    function delete()
    {
        $id = $_GET['id'];
        if(AdminModel::delete('admin', "`id`={$id}")); $_SESSION['alert']['delete-success'] = DELETE_SUCCESSFUL." with ID = {$id}";
        header("Location: search");
    }
}
?>