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
        if (!isset($_POST['login'])) {
            $this->render('login');
        } else {
            empty($_POST['email']) ? $error['error-empty-email'] = ERROR_EMPTY_EMAIL : "";
            empty($_POST['password']) ? $error['error-empty-password'] = ERROR_EMPTY_PASSWORD : "";
            !$this->adminModel->checkLogin($_POST['email'], md5($_POST['password'])) ? $error['error-login'] = ERROR_LOGIN : "";
            if (!empty($error)) {
                $this->render('login', $error);
            } else {
                $_SESSION['admin']['login'] = [
                    'checkLogin' => 'adminLogin',
                    'email' => $_POST['email'],
                    'id' => $this->adminModel->getIdAdmin($_POST['email'])['id'],
                ];
                $getRole = $this->adminModel->getRoleAdmin($_SESSION['admin']['login']['email']);
                $getRole['role_type'] == ROLE_TYPE_SUPERADMIN ? header("Location: " . getUrl("management/search")) : header("Location: " . getUrl("user/search"));
                $_SESSION['admin']['role_type'] = $getRole['role_type'];
            }
        }
    }

    function logout()
    {
        $this->render("logout");
    }

    function search()
    {
        if (isset($_GET['reset'])) header("Location: " . getUrl("management/search"));

        $search = isset($_GET['search']) ? $_GET['search'] : "";
        $name = isset($_GET['name']) ? $_GET['name'] : "";
        $email = isset($_GET['email']) ? $_GET['email'] : "";

        //Pagging
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start = ($page - 1) * RECORD_PER_PAGE;
        $totalRecord = $this->adminModel->getTotalRow($name, $email);
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

        $data = $this->adminModel->getSearch($conditionSearch);

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

        if (isset($_POST['reset'])) header("Location: " . getUrl("management/create"));

        if (isset($_POST['save'])) {
            $dataPost = array_merge($_POST, ['avatar' => $_FILES['avatar']['name']]);

            $error = !empty(AdminValidate::validateCreateAdmin($dataPost)) ? AdminValidate::validateCreateAdmin($dataPost) : [];

            if (!$this->adminModel->checkExistsEmail($dataPost['email'])) $error['error-email'] = ERROR_EMAIL_EXISTS;
            if (!checkConfirmPassword($dataPost['password'], $dataPost['confirm-password'])) $error['error-confirm-password'] = ERROR_CONFIRM_PASSWORD;

            if (empty($error)) {
                $dataCreateAdmin = [
                    'avatar' => $dataPost['avatar'],
                    'name' => $dataPost['name'],
                    'email' => $dataPost['email'],
                    'password' => md5($dataPost['password']),
                    'role_type' => $dataPost['role_type'],
                ];
                if ($this->adminModel->insert($dataCreateAdmin)) {
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
        $id = $_GET['id'];
        $data = $this->adminModel->getInfoById($id);
        $error = array();

        if (isset($_POST['save'])) {
            $dataPost = array_merge($_POST, ['avatar' => $_FILES['avatar']['name']]);
            $error = AdminValidate::validateEditAdmin($dataPost);

            $avatar = ($dataPost['avatar'] == "" || !empty($error['error-avatar'])) ? $data['avatar'] : $dataPost['avatar'];

            $name = !empty($error['error-name']) ? $data['name'] : $dataPost['name'];

            $email = $data['email'];
            if(!empty($dataPost['email'])){
                if($data['email'] != $dataPost['email']) !$this->adminModel->checkExistsEmail($dataPost['email']) ? $error['error-email'] = ERROR_EMAIL_EXISTS : "";
                if(empty($error['error-email'])) $email = $dataPost['email'];
            }

            $password = $data['password'];
            if(!empty($dataPost['password'])){
                if(empty($error['error-password'])) {
                    !checkConfirmPassword($dataPost['password'], $dataPost['confirm-password']) ? $error['error-confirm-password'] = ERROR_CONFIRM_PASSWORD : "";
                    if (empty($error['error-confirm-password'])) $password = md5($dataPost['password']);
                }
            }

            $role_type = $dataPost['role_type'];

            $dataUpdate = array(
                'avatar' => $avatar,
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role_type' => $role_type,
            );

            if ($this->adminModel->update($dataUpdate, "`id` = '{$id}'")) {
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
        $id = $_GET['id'];
        if ($this->adminModel->delete("`id`={$id}")) {
            $_SESSION['alert']['delete-success'] = DELETE_SUCCESSFUL . " with ID = {$id}";
        }
        header("Location: " . getUrl("management/search"));
    }
}

?>