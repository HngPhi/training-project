<?php
require_once "BaseController.php";
require_once "models/AdminModel.php";

class AdminController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->folder = "admin";
        $this->model = new AdminModel();
    }

    function error()
    {
        $this->render('error');
    }

    function login()
    {
        $error = [];
        if (!isset($_POST['login'])) {
            $this->render('login');
        } else {
            if (empty($_POST['email'])) {
                $error['error-email'] = ERROR_EMPTY_EMAIL;
            }
            if (empty($_POST['password'])) {
                $error['error-password'] = ERROR_EMPTY_PASSWORD;
            }

            $checkLogin = [];
            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                $checkLogin = $this->model->getInfoAdminLogin($_POST['email'], md5($_POST['password']));
                if (empty($checkLogin)) {
                    $error['error-login'] = ERROR_LOGIN;
                }
            }

            if (!empty($error)) {
                $this->render('login', $error);
            } else {
                $_SESSION['admin']['login'] = [
                    'checkLogin' => 'adminLogin',
                    'email' => $_POST['email'],
                    'id' => $checkLogin['id'],
                    'role_type' => $checkLogin['role_type'],
                ];

                if ($checkLogin['role_type'] == ROLE_TYPE_SUPERADMIN) {
                    header("Location: " . getUrl("management/search"));
                } else {
                    header("Location: " . getUrl("user/search"));
                }
            }
        }
    }

    function logout()
    {
        $this->render("logout");
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

            $error = AdminValidate::validateCreateAdmin($dataPost, $checkExistsEmail);

            if (empty($error)) {
                $dataCreateAdmin = [
                    'avatar' => $dataPost['avatar'],
                    'name' => $dataPost['name'],
                    'email' => $dataPost['email'],
                    'password' => md5($dataPost['password']),
                    'role_type' => $dataPost['role_type'],
                ];

                if ($this->model->insert($dataCreateAdmin)) {
                    $uploadFile = UPLOADS_ADMIN . $dataPost['avatar'];
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);
                    $_SESSION['admin']['upload'] = $uploadFile;
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
            $arr = AdminValidate::validateEditAdmin($data, $dataPost, $checkExistsEmail);
            extract($arr);

            $dataUpdate = array(
                'avatar' => $data['avatar'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role_type' => $data['role_type'],
            );

            if ($this->model->updateById($dataUpdate, $id)) {
                $upload_file = UPLOADS_ADMIN . $_FILES['avatar']['name'];
                move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file);
            }

            if (empty($error)) {
                $_SESSION['alert']['update-success'] = UPDATE_SUCCESSFUL . " with ID = {$id}";
                header("Location: " . getUrl("management/search"));
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
        header("Location: " . getUrl("management/search"));
    }
}

?>