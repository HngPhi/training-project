<?php
require_once "BaseController.php";
require_once "models/UserModel.php";
require_once 'vendor/autoload.php';

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
            $this->userModel->insert($data);
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
        if (isset($_GET['reset'])) header("Location: " . getUrl("user/search"));

        $search = isset($_GET['search']) ? $_GET['search'] : "";
        $name = isset($_GET['name']) ? $_GET['name'] : "";
        $email = isset($_GET['email']) ? $_GET['email'] : "";

        //Pagging
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start = ($page - 1) * RECORD_PER_PAGE;
        $totalRecord = $this->userModel->getTotalRowUser($name, $email);
        $totalPage = ceil($totalRecord / RECORD_PER_PAGE);

        //Sort
        $column = isset($_GET['column']) ? $_GET['column'] : "id";
        $getSort = isset($_GET['sort']) ? $_GET['sort'] : "";
        $sort = ($getSort == "DESC") ? "ASC" : "DESC";

        $conditionSearch = [
            'name' => $name,
            'email' => $email,
            'start' => $start,
            'sort' => $getSort,
            'column' => $column,
        ];

        $data = $this->userModel->searchUser($conditionSearch);

        $addUrlSearch = "?email={$email}&name={$name}&search={$search}";
        $addUrlPagging = $addUrlSearch . "&column=" . $column . "&sort=" . $getSort;

        $arr = [
            'data' => $data,
            'totalPage' => $totalPage,
            'page' => $page,
            'sort' => $sort,
            'addUrlSearch' => $addUrlSearch,
            'addUrlPagging' => $addUrlPagging,
        ];

        $this->render('search', $arr);
    }

    function create()
    {
        $error = array();

        if (isset($_POST['reset'])) header("Location: " . getUrl("user/create"));

        if (isset($_POST['save'])) {
            $dataPost = array_merge($_POST, ['avatar' => $_FILES['avatar']['name']]);
            $error = !empty(UserValidate::validateCreateUser($dataPost)) ? UserValidate::validateCreateUser($dataPost) : [];

            if (!$this->userModel->checkExistsEmailUser($dataPost['email'])) $error['error-email'] = ERROR_EMAIL_EXISTS;
            if (!checkConfirmPassword($dataPost['password'], $dataPost['confirm-password'])) $error['error-confirm-password'] = ERROR_CONFIRM_PASSWORD;

            if (empty($error)) {
                $dataCreateUser = [
                    'avatar' => $dataPost['avatar'],
                    'name' => $dataPost['name'],
                    'email' => $dataPost['email'],
                    'password' => md5($dataPost['password']),
                    'status' => $dataPost['status'],
                ];
                if ($this->userModel->insert($dataCreateUser)) {
                    $uploadFile = UPLOADS_USER . $dataPost['avatar'];
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);
                    $_SESSION['user']['upload'] = $uploadFile;
                    $_SESSION['alert']['success'] = INSERT_SUCCESSFUL;
                }
            }
        }
        $this->render('create', $error);
    }

    function edit()
    {
        $id = $_GET['id'];
        $data = $this->userModel->getInfoUserById($id);
        $error = array();

        if (isset($_POST['save'])) {
            $dataPost = array_merge($_POST, ['avatar' => $_FILES['avatar']['name']]);
            $error = UserValidate::validateEditUser($dataPost);
            $avatar = ($dataPost['avatar'] == "" || !empty($error['error-avatar'])) ? $data['avatar'] : $dataPost['avatar'];
            $name = !empty($error['error-name']) ? $data['name'] : $dataPost['name'];
            $email = !empty($error['error-email']) ? $data['email'] : $dataPost['email'];
            $password = $data['password'];
            if(!empty($dataPost['password'])){
                if(empty($error['error-password'])) {
                    !checkConfirmPassword($dataPost['password'], $dataPost['confirm-password']) ? $error['error-confirm-password'] = ERROR_CONFIRM_PASSWORD : "";
                    if (empty($error['error-confirm-password'])) $password = md5($dataPost['password']);
                }
            }
            $status = $dataPost['status'];

            $dataUpdate = array(
                'avatar' => $avatar,
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'status' => $status,
            );

            if ($this->userModel->update($dataUpdate, "`id` = '{$id}'")) {
                $upload_file = UPLOADS_USER . $_FILES['avatar']['name'];
                move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file);
            }

            if (empty($error)) {
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