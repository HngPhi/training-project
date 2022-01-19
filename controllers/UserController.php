<?php
require_once "BaseController.php";
require_once "models/UserModel.php";
require_once('vendor/autoload.php');

class UserController extends BaseController
{
    private $userModel;

    function __construct()
    {
        $this->folder = "user";
        $this->userModel = new UserModel();
    }

    function error()
    {
        $this->render('error');
    }

    function login()
    {
        $error = array();
        if (!isset($_POST['login'])) {
            $this->render('login');
        } else {
            empty($_POST['email']) ? $error['error-empty-email'] = ERROR_EMPTY_EMAIL : "";
            empty($_POST['password']) ? $error['error-empty-password'] = ERROR_EMPTY_PASSWORD : "";
            !$this->userModel->checkLogin($_POST['email'], md5($_POST['password'])) ? $error['error-login'] = ERROR_LOGIN : "";
            if (!empty($error)) {
                $this->render('login', $error);
            } else {
                $_SESSION['user']['login'] = [
                    'checkLogin' => 'userLogin',
                    'email' => $_POST['email'],
                ];
                header("Location: " . getUrl("user/profile"));
            }
        }
    }

    function loginViaFB()
    {
        $fb = new Facebook\Facebook([
            'app_id' => APP_ID,
            'app_secret' => APP_SECRET,
            'default_graph_version' => DEFAULT_GRAPH_VERSION,
        ]);
        $helper = $fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
            $response = $fb->get('/me?fields=id,name,email,cover,gender,picture,link', $accessToken);
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
        $_SESSION['user']['login'] = [
            'checkLogin' => 'userLogin',
        ];
        $id = $this->userModel->getIdUserByEmail($me->getEmail())['id'];
        if ($this->userModel->checkExistsEmailUser($me->getEmail()) > 0) {
            $getInfoUserByEmail = $this->userModel->getInfoUserByEmail($me->getEmail());
            $data = [
                'id' => $id,
                'avatar' => $getInfoUserByEmail['avatar'],
                'name' => $me->getName(),
                'email' => $me->getEmail(),
            ];
        } else {
            $url = "https://graph.facebook.com/{$me->getId()}/picture";
            $data = file_get_contents($url);
            $fileName = "fb-profilepic-{$me->getId()}.jpg";
            $file = fopen(UPLOADS_USER . $fileName, 'w+');
            fputs($file, $data);
            fclose($file);
            $data = array(
                'id' => $id,
                'avatar' => $fileName,
                'facebook_id' => $me->getId(),
                'name' => $me->getName(),
                'email' => $me->getEmail(),
                'ins_datetime' => date("Y-m-d H:i:s a"),
            );
            $this->userModel->insert('user', $data);
        }
        $_SESSION['user']['loginFB-success'] = LOGIN_FB_SUCCESSFUL;
        $this->render('detail', $data);
    }

    function logout()
    {
        $this->render('logout');
    }

    function detail()
    {
        $data = $this->userModel->getInfoUserByEmail($_SESSION['user']['login']['email']);
        $this->render('detail', $data);
    }

    function search()
    {

        if (isset($_GET['reset'])) {
            header("Location: " . getUrl("user/search"));
        }

        $email = isset($_GET['email']) ? $_GET['email'] : "";
        $name = isset($_GET['name']) ? $_GET['name'] : "";
        $search = isset($_GET['search']) ? $_GET['search'] : "";
        $addUrlSearch = "?email={$email}&name={$name}&search={$search}";

        /**
         * Pagging
         * 5 tham số:
         * -- $record_per_page: Số bản ghi mỗi trang
         * -- $total_record: Tổng số bản ghi
         * -- $total_page: Tổng số trang
         * -- $start: Chỉ số bản ghi bắt đầu mỗi trang
         * -- $page: Chỉ số trang hiện tại
         */

        $where = "WHERE `email` LIKE '%{$email}%' AND `name` LIKE '%{$name}%' AND `del_flag` = " . ACTIVED;

        $recordPerPage = RECORD_PER_PAGE;
        $totalRecord = $this->userModel->getTotalRow($where);
        $totalPage = ceil($totalRecord / $recordPerPage);
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start = ($page - 1) * $recordPerPage;
        $previous = $page;
        $next = $page;

        if ($page > 1) $previous = $page - 1;
        if ($page < $totalPage) $next = $page + 1;

        /**
         * Sort
         */
        $getSort = isset($_GET['sort']) ? $_GET['sort'] : "";
        $sort = ($getSort == "DESC") ? "ASC" : "DESC";

        $column = isset($_GET['column']) ? $_GET['column'] : "id";
        $addUrlPagging = $addUrlSearch . "&column=" . $column . "&sort=" . $getSort;

        /**
         * SQL
         */
        $orderBy = "ORDER BY `{$column}` {$getSort}";
        $limit = "LIMIT $start, $recordPerPage";

        $data = $this->userModel->getInfoSearch($where, $orderBy, $limit);
        if (empty($data)) $data = NO_EXISTS_USER;

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
            header("Location: " . getUrl("user/create"));
        }

        if (isset($_POST['save'])) {
            /**
             * 1, Empty - Length
             * 2, Validate
             * 3, Check email - password
             * 4, Upload file
             * 5, Save
             */

            // 1, ...
            $_FILES['avatar']['name'] == "" ? $data['error-avatar'] = ERROR_EMPTY_AVATAR : "";
            empty($_POST['email']) ? $data['error-email'] = ERROR_EMPTY_EMAIL : "";
            empty($_POST['name']) ? $data['error-name'] = ERROR_EMPTY_NAME : "";
            empty($_POST['password']) ? $data['error-password'] = ERROR_EMPTY_PASSWORD : "";
            empty($_POST['confirm-password']) ? $data['error-confirm-password'] = ERROR_EMPTY_CONFIRM_PASSWORD : "";

            $this->userModel->checkLength($_POST['email'], MINIMUM_LENGTH_EMAIL, MAXIMUM_LENGTH_EMAIL) ? "" : $data['error-email'] = ERROR_LENGTH_EMAIL;
            $this->userModel->checkLength($_POST['name'], MINIMUM_LENGTH_NAME, MAXIMUM_LENGTH_NAME) ? "" : $data['error-name'] = ERROR_LENGTH_NAME;
            $this->userModel->checkLength($_POST['password'], MINIMUM_LENGTH_PASSWORD, MAXIMUM_LENGTH_PASSWORD) ? "" : $data['error-password'] = ERROR_LENGTH_PASSWORD;

            // 2, ...
            $this->userModel->validateName($_POST['name']) ? "" : $error['error-name'] = ERROR_VALID_NAME;
            $this->userModel->validateEmail($_POST['name']) ? "" : $error['error-name'] = ERROR_VALID_NAME;
            $this->userModel->validatePassword($_POST['name']) ? "" : $error['error-name'] = ERROR_VALID_NAME;

            $validImg = $this->userModel->validateImg();
            $data = array_merge($data, $validImg);

            // 3, ...
            if ($this->userModel->checkExistsEmailUser($_POST['email']) > 0) $data['error-email'] = ERROR_EMAIL_EXISTS;
            if ($_POST['password'] != $_POST['confirm-password']) $data['error-confirm-password'] = ERROR_CONFIRM_PASSWORD;

            /* 4, Upload file
            * - B1: Check đuôi file
            * - B2: Kiểm tra dung lượng ảnh
            * - B3: Chuyển vào thư mục lưu ảnh
            */

            // Tạo thư mục chứa ảnh
            $upload_file = UPLOADS_USER . $_FILES['avatar']['name'];

            //Insert dữ liệu
            if (empty($data)) {
                $arr = array(
                    'avatar' => $_FILES['avatar']['name'],
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'password' => md5($_POST['password']),
                    'status' => $_POST['status'],
                );
                if ($this->userModel->insert($arr)) {
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file);
                    $_SESSION['user']['upload'] = $upload_file;
                    $data['alert-success'] = INSERT_SUCCESSFUL;
                } else {
                    echo "FAIL";
                }
            }
        }
        $this->render('create', $data);
    }

    function edit()
    {
        $id = $_GET['id'];
        $data = $this->userModel->getInfoUserById($id);
        $error = array();

        if (isset($_POST['save'])) {
            $avatar = $_FILES['avatar']['name'];
            $status = $_POST['status'];

            $validImg = $this->userModel->validateImg($avatar);
            !empty($avatar) ? $error = array_merge($error, $validImg) : $avatar = $data['avatar'];

            //Name
            if(empty($_POST['name'])){
                $error['error-name'] = ERROR_EMPTY_NAME;
            }else{
                $this->userModel->checkLength($_POST['name'], MINIMUM_LENGTH_NAME, MAXIMUM_LENGTH_NAME) ? "" : $error['error-name'] = ERROR_LENGTH_NAME;
                $this->userModel->validateName($_POST['name']) ? "" : $error['error-name'] = ERROR_VALID_NAME;
            }
            $name = !empty($error['error-name']) ? $data['name'] : $_POST['name'];

            //Email
            if (empty($_POST['email'])) {
                $error['error-email'] = ERROR_EMPTY_EMAIL;
            }else{
                if($_POST['email'] != $data['email']){
                    $this->userModel->checkExistsEmailUser($_POST['email']) > 0 ? $error['error-email'] = ERROR_EMAIL_EXISTS : "";
                    $this->userModel->checkLength($_POST['email'], MINIMUM_LENGTH_EMAIL, MAXIMUM_LENGTH_EMAIL) ? "" : $error['error-email'] = ERROR_LENGTH_EMAIL;
                    $this->userModel->validateEmail($_POST['email']) ? "" : $error['error-email'] = ERROR_VALID_EMAIL;
                }
            }
            $email = !empty($error['error-email']) ? $data['email'] : $_POST['email'];

            //Password
            $password = $data['password'];
            if(!empty($_POST['password'])){
                $this->userModel->checkLength($_POST['password'], MINIMUM_LENGTH_PASSWORD, MAXIMUM_LENGTH_PASSWORD) ? "" : $error['error-password'] = ERROR_LENGTH_PASSWORD;
                $this->userModel->validatePassword($_POST['password']) ? "" : $error['error-password'] = ERROR_VALID_PASSWORD;
                if(empty($error['error-password'])){
                    $this->userModel->checkConfirmPassword($_POST['password'], $_POST['confirm-password']) ? $password = md5($_POST['password']) : $error['error-confirm-password'] = ERROR_CONFIRM_PASSWORD;
                }
            }

            $arr = array(
                'avatar' => $avatar,
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'status' => $status,
            );

            $upload_file = UPLOADS_USER . $_FILES['avatar']['name'];

            if ($this->userModel->update($arr, "`id` = '{$id}'")) {
                move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file);
            }

            if(empty($error)){
                $_SESSION['alert']['update-success'] = UPDATE_SUCCESSFUL . " with ID = {$id}";
                header("Location: " . getUrl("user/search"));
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
        if ($this->userModel->delete("`id`={$id}")) ;
        $_SESSION['alert']['delete-success'] = DELETE_SUCCESSFUL . " with ID = {$id}";
        header("Location: " . getUrl("user/search"));
    }
}

?>