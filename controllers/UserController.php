<?php
require_once "BaseController.php";
require_once "models/UserModel.php";
require_once 'vendor/autoload.php';

class UserController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->folder = "user";
        $this->model = new UserModel();
    }

    function error()
    {
        $this->render('error');
    }

    function login()
    {
        $error = array();
        if (!isset($_POST)) {
            $this->render('login');
        } else {
            $email = isset($_POST['email']) ? $_POST['email'] : "";
            $password = isset($_POST['password']) ? $_POST['password'] : "";

            if (empty($email)) {
                $error['error-email'] = ERROR_EMPTY_EMAIL;
            }
            if (empty($password)) {
                $error['error-password'] = ERROR_EMPTY_PASSWORD;
            }

            $checkLogin = $this->model->checkUserLogin($email, md5($password));

            if (!empty($email) && !empty($password)) {
                if (!$checkLogin) {
                    $error['error-login'] = ERROR_LOGIN;
                }
            }

            if (!empty($error)) {
                return $this->render('login', $error);
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
        $id = $this->model->getIdByEmail($me->getEmail())['id'];
        if ($this->model->checkExistsEmail($me->getEmail()) > 0) {
            $getInfoByEmail = $this->model->getInfoByEmail($me->getEmail());
            $data = [
                'id' => $id,
                'avatar' => $getInfoByEmail['avatar'],
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
            $this->model->insert($data);
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
        $data = $this->model->getInfoByEmail($_SESSION['user']['login']['email']);
        $this->render('detail', $data);
    }

    function search()
    {
        $conditionSearch = $_GET;
        $data = $this->model->getSearch($conditionSearch);
        $this->render('search', $data);
    }

    function create()
    {
        $error = [];

        if (isset($_POST['save'])) {
            $dataPost = array_merge($_POST, ['avatar' => $_FILES['avatar']['name']]);
            $checkExistsEmail = $this->model->checkExistsEmail($dataPost['email']);

            $error = UserValidate::validateCreateUser($dataPost, $checkExistsEmail);

            if (empty($error)) {
                $dataCreateUser = [
                    'avatar' => $dataPost['avatar'],
                    'name' => $dataPost['name'],
                    'email' => $dataPost['email'],
                    'password' => md5($dataPost['password']),
                    'status' => $dataPost['status'],
                ];

                if ($this->model->insert($dataCreateUser)) {
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
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $data = $this->model->getInfoById($id);

        $error = [];

        if (isset($_POST['save'])) {
            $dataPost = array_merge($_POST, ['avatar' => $_FILES['avatar']['name']]);
            $checkExistsEmail = $this->model->checkExistsEmail($dataPost['email']);

            $arr = UserValidate::validateEditUser($data, $dataPost, $checkExistsEmail);
            extract($arr);

            $dataUpdate = array(
                'avatar' => $data['avatar'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'status' => $data['status'],
            );

            if ($this->model->updateById($dataUpdate, $id)) {
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
        $id = "";
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        if ($this->model->deleteById($id)) {
            $_SESSION['alert']['delete-success'] = DELETE_SUCCESSFUL . " with ID = {$id}";
        }
        header("Location: " . getUrl("user/search"));
    }
}

?>