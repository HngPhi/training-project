<?php
require_once "BaseController.php";
require_once "models/UserModel.php";
require_once('vendor/autoload.php');

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
        if(isset($_POST['login'])) {
            if (empty($_POST['email'])) $error['error-empty-email'] = ERROR_EMPTY_EMAIL;
            if (empty($_POST['password'])) $error['error-empty-password'] = ERROR_EMPTY_PASSWORD;
            if (empty($error)) {
                if (UserModel::checkLogin('user', $_POST['email'], md5($_POST['password']))) {
                    $_SESSION['user']['login'] = [
                        'is_login' => 1,
                        'email' => $_POST['email'],
                    ];
                    header("Location: profile");
                } else {
                    $error['error-login'] = ERROR_LOGIN;
                    $this->render('login', $error);
                }
            } else {
                $this->render('login', $error);
            }
        }
        else{
            $this->render('login');
        }
    }

    function loginViaFB(){
        $fb = new Facebook\Facebook([
            'app_id' => APP_ID,
            'app_secret' => APP_SECRET,
            'default_graph_version' => DEFAULT_GRAPH_VERSION,
        ]);
        $helper = $fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
            $response = $fb->get('/me?fields=id,name,email,cover,gender,picture,link', $accessToken);
//            $requestPicture = $fb->get('/me/picture?redirect=false&height=100', $accessToken);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }
        // Logged in
        $me = $response->getGraphUser();
        $_SESSION['fb_access_token'] = (string)$accessToken;
        $data = array();
        if(UserModel::checkExistsEmailUser($me->getEmail()) > 0){
            $getInfoUserByEmail = UserModel::getInfoUserByEmail($me->getEmail());
            $data = [
                'facebook_id' => $me->getId(),
                'avatar' => $getInfoUserByEmail['avatar'],
                'name' => $me->getName(),
                'email' => $me->getEmail(),
            ];
        }else{
            $url = "https://graph.facebook.com/{$me->getId()}/picture";
            $data = file_get_contents($url);
            $fileName = "fb-profilepic-{$me->getId()}.jpg";
            $file = fopen(UPLOADS_USER . $fileName, 'w+');
            fputs($file, $data);
            fclose($file);
            $data = array(
                'id' => "",
                'avatar' => $fileName,
                'facebook_id' => $me->getId(),
                'name' => $me->getName(),
                'email' => $me->getEmail(),
                'ins_datetime' => date("Y-m-d H:i:s a"),
            );
            UserModel::insert('user', $data);
        }
        $_SESSION['user']['loginFB-success'] = LOGIN_FB_SUCCESSFUL;
        $this->render("detail", $data);
    }

    function logout()
    {
        session_destroy();
        header("Location: login");
    }

    function detail(){
        $data = UserModel::getInfoUserByEmail($_SESSION['user']['login']['email']);
        $this->render('detail', $data);
    }

    function search(){

        if (isset($_GET['reset'])) {
            header("Location: user/search");
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
        $total_record = UserModel::getTotalRow('user', $where);
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

    function create(){
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

            $checkLengthEmail = UserModel::checkLengthEmail($_POST['email']);
            $checkLengthName = UserModel::checkLengthName($_POST['name']);
            $checkLengthPassword = UserModel::checkLengthPassword($_POST['password']);

            $validEmail = UserModel::validateEmail($_POST['email']);
            $validName = UserModel::validateName($_POST['name']);
            $validPassword = UserModel::validatePassword($_POST['password']);
            $validImg = UserModel::validateImg();

            $data = array_merge($data, $checkLengthEmail, $checkLengthName, $checkLengthPassword, $validEmail, $validName, $validPassword, $validImg);

            // 3, Check thông tin EMAIL và PASSWORD
            if (UserModel::checkExistsEmailUser($_POST['email']) > 0) $data['error-email'] = ERROR_EMAIL_EXISTS;
            if ($_POST['password'] != $_POST['confirm-password']) $data['error-confirm-password'] = ERROR_CONFIRM_PASSWORD;

            /* 4, Upload file
            * - B1: Check đuôi file
            * - B2: Kiểm tra dung lượng ảnh
            * - B3: Chuyển vào thư mục lưu ảnh
            */

            // Tạo thư mục chứa ảnh
            $upload_file = UPLOADS_USER . $_FILES['avatar']['name'];

            //Insert dữ liệu
            $ins_id_admin = UserModel::getIdAdmin($_SESSION['loginAdmin']['email']);

            if (empty($data)) {
                $arr = array(
                    'avatar' => $_FILES['avatar']['name'],
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'password' => md5($_POST['password']),
                    'status' => $_POST['status'],
                    'ins_id' => $ins_id_admin['id'],
                    'ins_datetime' => date("Y-m-d H:i:s a"),
                );
                if (UserModel::insert('user', $arr)) {
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file);
                    $_SESSION['user']['upload'] = $upload_file;
                    $data['alert-success'] = INSERT_SUCCESSFUL;
                }
            }
        }
        $this->render('create', $data);
    }

    function edit(){
        $id = $_GET['id'];
        $data = UserModel::getInfoUserByID($id);
        $error = array();

        if(isset($_POST['save'])){
            $avatar = $_FILES['avatar']['name'];
            $name =  $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm-password'];
            $status = $_POST['status'];

            $validImg = UserModel::validateImg($avatar);
            $validName = UserModel::validateName($name);
            $validEmail = UserModel::validateEmail($email);
            $validPass = UserModel::validatePassword($password);
            $checkConfirmPass = UserModel::checkConfirmPassword($password, $confirm_password);

            if(!empty($avatar)) $error = array_merge($error, $validImg);
            else $avatar = $data['avatar'];

            if($name!= $data['name']) $error = array_merge($error, $validName);

            if($email != $data['email']){
                if(UserModel::checkExistsEmailUser($email) > 0) $error['error-email'] = ERROR_EMAIL_EXISTS;
                $error = array_merge($error, $validEmail);
            }

            if(!empty($password)) $error = array_merge($error, $validPass, $checkConfirmPass);
            else $password = $data['password'];

            $checkLengthEmail = AdminModel::checkLengthEmail($_POST['email']);
            $checkLengthName = AdminModel::checkLengthName($_POST['name']);
            $checkLengthPassword = AdminModel::checkLengthPassword($_POST['password']);

            $error = array_merge($error, $checkLengthEmail, $checkLengthName, $checkLengthPassword);

            if(empty($error)){
                $upd_id_user = UserModel::getInfoAdminByEmail($_SESSION['loginAdmin']['email']);
                $arr = array(
                    'avatar' => $avatar,
                    'name' => $name,
                    'email' => $email,
                    'password' => md5($password),
                    'status' => $status,
                    'upd_id' => $upd_id_user['id'],
                    'upd_datetime' => date("Y-m-d H:i:s a"),
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
        header("Location: search");
    }
}
?>