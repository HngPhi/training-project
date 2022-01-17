<?php
require_once "BaseController.php";
require_once "models/AdminModel.php";

class AdminController extends BaseController
{
    private $adminModel;

    function __construct()
    {
        $this->folder = "admin";
        $this->adminModel = new AdminModel();
    }

    function error()
    {
        $this->render('error');
    }

    function login()
    {
        $error = array();
        if(isset($_POST['login'])){
            empty($_POST['email']) ? $error['error-empty-email'] = ERROR_EMPTY_EMAIL : "";
            empty($_POST['password']) ? $error['error-empty-password'] = ERROR_EMPTY_PASSWORD : "";
            if(empty($error)){
                var_dump($this->adminModel);
                if($this->adminModel->checkLogin($_POST['email'], md5($_POST['password']))){
                    $_SESSION['admin']['login'] = [
                      'is_login' => IS_LOGIN,
                      'email' => $_POST['email'],
                    ];
                    $getRole = $this->adminModel->getRoleAdmin($_SESSION['admin']['login']['email']);
                    $getRole['role_type'] == ROLE_TYPE_SUPERADMIN ? header("Location: search") : header("Location: https://vdhp.com/user/search");
                    $_SESSION['admin']['role_type'] = $getRole['role_type'];
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
        $addUrlSearch = "&email={$email}&name={$name}&search={$search}";

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

        $recordPerPage = RECORD_PER_PAGE;
        $totalRecord = $this->adminModel->getTotalRow($where);
        $totalPage = ceil($totalRecord/$recordPerPage);
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start = ($page-1)*$recordPerPage;
        $previous = $page;
        $next = $page;

        if($page > 1) $previous = $page - 1;
        if($page < $totalPage) $next = $page + 1;

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
        $addUrlPagging = $addUrlSearch."&column=".$column."&sort=".$getSort;

        /**
         * SQL
         */
        $orderBy = "ORDER BY `{$column}` {$getSort}";
        $limit = "LIMIT $start, $recordPerPage";

        $data = $this->adminModel->getInfoSearch($where, $orderBy, $limit);
        if(empty($data)) $data = NO_EXISTS_USER;

        $arr = [
            'data' => $data,
            'sort' => $sort,
            'page' => $page,
            'totalPage' => $totalPage,
            'previous' => $previous,
            'next' => $next,
            'addUrlSearch' => $addUrlSearch,
            'addUrlPagging' => $addUrlPagging,
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
            $_FILES['avatar']['name'] == "" ? $data['error-avatar'] = ERROR_EMPTY_AVATAR : "";
            empty($_POST['email']) ? $data['error-email'] = ERROR_EMPTY_EMAIL : "";
            empty($_POST['name']) ? $data['error-name'] = ERROR_EMPTY_NAME : "";
            empty($_POST['password']) ? $data['error-password'] = ERROR_EMPTY_PASSWORD : "";
            empty($_POST['confirm-password']) ? $data['error-confirm-password'] = ERROR_EMPTY_CONFIRM_PASSWORD : "";

            $this->adminModel->checkLength($_POST['email'], MINIMUM_LENGTH_EMAIL, MAXIMUM_LENGTH_EMAIL) ? "" : $data['error-length-email'] = ERROR_LENGTH_EMAIL;
            $this->adminModel->checkLength($_POST['name'], MINIMUM_LENGTH_NAME, MAXIMUM_LENGTH_NAME) ? "" : $data['error-length-name'] = ERROR_LENGTH_NAME;
            $this->adminModel->checkLength($_POST['password'], MINIMUM_LENGTH_PASSWORD, MAXIMUM_LENGTH_PASSWORD) ? "" : $data['error-length-password'] = ERROR_LENGTH_PASSWORD;

            $validEmail = $this->adminModel->validateEmail($_POST['email']);
            $validName = $this->adminModel->validateName($_POST['name']);
            $validPassword = $this->adminModel->validatePassword($_POST['password']);
            $validImg = $this->adminModel->validateImg();

            $data = array_merge($data, $validEmail, $validName, $validPassword, $validImg);

            // 3, Check thông tin EMAIL và PASSWORD
            if ($this->adminModel->checkExistsEmailAdmin($_POST['email']) > 0) $data['error-email'] = ERROR_EMAIL_EXISTS;
            if ($_POST['password'] != $_POST['confirm-password']) $data['error-confirm-password'] = ERROR_CONFIRM_PASSWORD;


            /* 4, Upload file
            * - B1: Check đuôi file
            * - B2: Kiểm tra dung lượng ảnh
            * - B3: Chuyển vào thư mục lưu ảnh
            */

            // Tạo thư mục chứa ảnh
            $upload_file = UPLOADS_ADMIN . $_FILES['avatar']['name'];

            //Insert dữ liệu
            $ins_id_admin = $this->adminModel->getIdAdmin($_SESSION['admin']['login']['email']);

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
                if ($this->adminModel->insert($arr)) {
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
        $data = $this->adminModel->getInfoAdmin($id);
        $error = array();

        if(isset($_POST['save'])){
            $avatar = $_FILES['avatar']['name'];
            $name =  $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm-password'];
            $roleType = $_POST['role_type'];

            $validImg = $this->adminModel->validateImg($avatar);
            $validName = $this->adminModel->validateName($name);
            $validEmail = $this->adminModel->validateEmail($email);
            $validPass = $this->adminModel->validatePassword($password);
            $checkConfirmPass = $this->adminModel->checkConfirmPassword($password, $confirm_password);

            !empty($avatar) ? $error = array_merge($error, $validImg) : $avatar = $data['avatar'];

            if($name!= $data['name']) $error = array_merge($error, $validName);

            if($email != $data['email']){
                if($this->adminModel->checkExistsEmailAdmin($email) > 0) $error['error-email'] = ERROR_EMAIL_EXISTS;
                $error = array_merge($error, $validEmail);
            }

            if(!empty($password)) {
                $this->adminModel->checkLength($_POST['password'], MINIMUM_LENGTH_PASSWORD, MAXIMUM_LENGTH_PASSWORD) ? "" : $error['error-length-password'] = ERROR_LENGTH_PASSWORD;
                $error = array_merge($error, $validPass, $checkConfirmPass);
            }else{
                $password = $data['password'];
            }

            $this->adminModel->checkLength($_POST['email'], MINIMUM_LENGTH_EMAIL, MAXIMUM_LENGTH_EMAIL) ? "" : $error['error-length-email'] = ERROR_LENGTH_EMAIL;
            $this->adminModel->checkLength($_POST['name'], MINIMUM_LENGTH_NAME, MAXIMUM_LENGTH_NAME) ? "" : $error['error-length-name'] = ERROR_LENGTH_NAME;

            if(empty($error)){
                $upd_id = $this->adminModel->getIdAdmin($_SESSION['admin']['login']['email']);
                $arr = array(
                    'avatar' => $avatar,
                    'name' => $name,
                    'email' => $email,
                    'password' => md5($password),
                    'role_type' => $roleType,
                    'upd_id' => $upd_id['id'],
                    'upd_datetime' => date("Y-m-d H:i:s a"),
                );

                $upload_file = UPLOADS_ADMIN . $_FILES['avatar']['name'];

                if ($this->adminModel->update($arr, "`id` = '{$id}'")) {
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
        if($this->adminModel->delete("`id`={$id}")); $_SESSION['alert']['delete-success'] = DELETE_SUCCESSFUL." with ID = {$id}";
        header("Location: search");
    }
}
?>