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
                $get_list_admin = AdminModel::getListAdmin();
                foreach ($get_list_admin as $value) {
                    if(check_login_admin($_POST['email'], md5($_POST['password']), $get_list_admin)){
                        $_SESSION['login'] = [
                          'is_login' => 1,
                          'email' => $_POST['email'],
                        ];
                        header("Location: index.php?controller=admin&action=search");
                    }
                    else{
                        $error['error-login'] = ERROR_LOGIN;
                        $this->render('login', $error);
                    }
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
        if (isset($_SESSION['login'])) {
            unset($_SESSION['login']);
            header("Location: views/admin/login.php");
        }
    }

    function search()
    {
        $data = AdminModel::getListAdmin();

        /**
         * Sort
         */

        $sort = "ASC";

        if(isset($_GET['sortID'])){
            if($_GET['sortID'] == $sort) $sort = "DESC";
            $data = AdminModel::sortID($_GET['sortID']);
        }

        if(isset($_GET['sortName'])){
            if($_GET['sortName'] == $sort) $sort = "DESC";
            $data = AdminModel::sortName($_GET['sortName']);
        }

        if(isset($_GET['sortEmail'])){
            if($_GET['sortEmail'] == $sort) $sort = "DESC";
            $data = AdminModel::sortEmail($_GET['sortEmail']);
        }

        if(isset($_GET['sortRole'])){
            if($_GET['sortRole'] == $sort) $sort = "DESC";
            $data = AdminModel::sortRole($_GET['sortRole']);
        }

        /**
         * Chức năng RESET
         */
        if (isset($_GET['reset'])) {
            header("Location: index.php?controller=admin&action=search");
        }

        /**
         * Chức năng SEARCH
         * TH1: Trống cả 2
         * TH2: Tồn tại 1 trong 2 trường, hoặc cả 2
         */
        if (isset($_GET['search'])) {
            if (empty($_GET['name']) && empty($_GET['email'])) {
                header("Location: index.php?controller=admin&action=search");
            }
            else {
                $data = AdminModel::getSearchAdmin($_GET['email'], $_GET['name']);
                if(empty($data)) $data = NO_EXISTS_USER;
            }
        }
        $arr = [
            'data' => $data,
            'sort' => $sort,
        ];
        $this->render('search', $arr);
    }

    function create()
    {
        $data = array();

        if (isset($_POST['reset'])) {
            header("Location: index.php?controller=admin&action=create");
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

            $validEmail = AdminModel::validateEmailAdmin($_POST['email']);
            $validName = AdminModel::validateNameAdmin($_POST['name']);
            $validPassword = AdminModel::validatePasswordAdmin($_POST['password']);
            $validImg = AdminModel::validateImgAdmin();

            $data = array_merge($data, $validEmail, $validName, $validPassword, $validImg);

            // 3, Check thông tin EMAIL và PASSWORD
            if (AdminModel::checkExistsEmail($_POST['email']) > 0) $data['error-email'] = ERROR_EMAIL_EXISTS;
            if ($_POST['password'] != $_POST['confirm-password']) $data['error-confirm-password'] = ERROR_CONFIRM_PASSWORD;

            /* 4, Upload file
            * - B1: Check đuôi file
            * - B2: Kiểm tra dung lượng ảnh
            * - B3: Chuyển vào thư mục lưu ảnh
            */

            // Tạo thư mục chứa ảnh
            $upload_dir = UPLOADS;
            $upload_file = $upload_dir . $_FILES['avatar']['name'];

            //Insert dữ liệu
            $ins_id = AdminModel::getId($_SESSION['login']['email']);

            if (empty($data)) {
                $arr = array(
                    'avatar' => $_FILES['avatar']['name'],
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'password' => md5($_POST['password']),
                    'role_type' => $_POST['role_type'],
                    'ins_id' => $ins_id['id'],
                );
                if (AdminModel::insert('admin', $arr)) {
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file);
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

            $validImg = AdminModel::validateImgAdmin($avatar);
            $validName = AdminModel::validateNameAdmin($name);
            $validEmail = AdminModel::validateEmailAdmin($email);
            $validPass = AdminModel::validatePasswordAdmin($password);
            $checkConfirmPass = AdminModel::checkConfirmPassword($password, $confirm_password);

            if(!empty($avatar)) $error = array_merge($error, $validImg);
            else $avatar = $data['avatar'];

            if($name!= $data['name']) $error = array_merge($error, $validName);

            if($email != $data['email']) $error = array_merge($error, $validEmail);

            if(!empty($password)) $error = array_merge($error, $validPass, $checkConfirmPass);
            else $password = $data['password'];

            if(empty($error)){
                $upd_id = AdminModel::getId($_SESSION['login']['email']);
                $arr = array(
                    'avatar' => $avatar,
                    'name' => $name,
                    'email' => $email,
                    'password' => md5($password),
                    'role_type' => $role_type,
                    'upd_id' => $upd_id['id'],
                );

                $upload_file = UPLOADS . $_FILES['avatar']['name'];

                if (AdminModel::update('admin', $arr, "`id` = '{$id}'")) {
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file);
                    $_SESSION['alert']['update-success'] = UPDATE_SUCCESSFUL." with ID = {$id}";
                    header("Location: index.php?controller=admin&action=search");
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
//        $data = AdminModel::getInfoAdmin($id);
        if(AdminModel::delete('admin', "`id`={$id}")); $_SESSION['alert']['delete-success'] = DELETE_SUCCESSFUL." with ID = {$id}";
        header("Location: index.php?controller=admin&action=search");
    }
}
?>