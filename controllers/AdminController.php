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
        if (isset($_GET['reset'])) {
            header("Location: " . getUrl("management/search"));
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
        $totalRecord = $this->adminModel->getTotalRow($where);
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

        $data = $this->adminModel->getInfoSearch($where, $orderBy, $limit);
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
            header("Location: create");
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

            $this->adminModel->checkLength($_POST['email'], MINIMUM_LENGTH_EMAIL, MAXIMUM_LENGTH_EMAIL) ? "" : $data['error-email'] = ERROR_LENGTH_EMAIL;
            $this->adminModel->checkLength($_POST['name'], MINIMUM_LENGTH_NAME, MAXIMUM_LENGTH_NAME) ? "" : $data['error-name'] = ERROR_LENGTH_NAME;
            $this->adminModel->checkLength($_POST['password'], MINIMUM_LENGTH_PASSWORD, MAXIMUM_LENGTH_PASSWORD) ? "" : $data['error-password'] = ERROR_LENGTH_PASSWORD;

            //2, ...
            $validImg = $this->adminModel->validateImg();
            $this->adminModel->validateName($_POST['name']) ? "" : $error['error-name'] = ERROR_VALID_NAME;
            $this->adminModel->validateEmail($_POST['name']) ? "" : $error['error-name'] = ERROR_VALID_NAME;
            $this->adminModel->validatePassword($_POST['name']) ? "" : $error['error-name'] = ERROR_VALID_NAME;

            $data = array_merge($data, $validImg);

            //3, ...
            $this->adminModel->checkExistsEmailAdmin($_POST['email']) > 0 ? $data['error-email'] = ERROR_EMAIL_EXISTS : "";
            $this->adminModel->checkConfirmPassword($_POST['password'], $_POST['confirm-password']) ? "" : $data['error-confirm-password'] = ERROR_CONFIRM_PASSWORD;


            /* 4, Upload file
            * - B1: Check đuôi file
            * - B2: Kiểm tra dung lượng ảnh
            * - B3: Chuyển vào thư mục lưu ảnh
            */

            // Tạo thư mục chứa ảnh
            $uploadFile = UPLOADS_ADMIN . $_FILES['avatar']['name'];

            //Insert dữ liệu
            if (empty($data)) {
                $arr = array(
                    'avatar' => $_FILES['avatar']['name'],
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'password' => md5($_POST['password']),
                    'role_type' => $_POST['role_type'],
                );
                if ($this->adminModel->insert($arr)) {
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);
                    $_SESSION['admin']['upload'] = $uploadFile;
                    $data['alert-success'] = INSERT_SUCCESSFUL;
                }
            }
        }
        $this->render('create', $data);
    }

    function edit()
    {
        $id = $_GET['id'];
        $data = $this->adminModel->getInfoAdminById($id);
        $error = array();

        if (isset($_POST['save'])) {
            $avatar = $_FILES['avatar']['name'];
            $roleType = $_POST['role_type'];

            $validImg = $this->adminModel->validateImg($avatar);
            !empty($avatar) ? $error = array_merge($error, $validImg) : $avatar = $data['avatar'];

            //Name
            if (empty($_POST['name'])) {
                $error['error-name'] = ERROR_EMPTY_NAME;
            } else {
                $this->adminModel->checkLength($_POST['name'], MINIMUM_LENGTH_NAME, MAXIMUM_LENGTH_NAME) ? "" : $error['error-name'] = ERROR_LENGTH_NAME;
                $this->adminModel->validateName($_POST['name']) ? "" : $error['error-name'] = ERROR_VALID_NAME;
            }
            $name = !empty($error['error-name']) ? $data['name'] : $_POST['name'];

            //Email
            if (empty($_POST['email'])) {
                $error['error-email'] = ERROR_EMPTY_EMAIL;
            } else {
                if ($_POST['email'] != $data['email']) {
                    $this->adminModel->checkExistsEmailAdmin($_POST['email']) > 0 ? $error['error-email'] = ERROR_EMAIL_EXISTS : "";
                    $this->adminModel->checkLength($_POST['email'], MINIMUM_LENGTH_EMAIL, MAXIMUM_LENGTH_EMAIL) ? "" : $error['error-email'] = ERROR_LENGTH_EMAIL;
                    $this->adminModel->validateEmail($_POST['email']) ? "" : $error['error-email'] = ERROR_VALID_EMAIL;
                }
            }
            $email = !empty($error['error-email']) ? $data['email'] : $_POST['email'];

            //Password
            $password = $data['password'];
            if (!empty($_POST['password'])) {
                $this->adminModel->checkLength($_POST['password'], MINIMUM_LENGTH_PASSWORD, MAXIMUM_LENGTH_PASSWORD) ? "" : $error['error-password'] = ERROR_LENGTH_PASSWORD;
                $this->adminModel->validatePassword($_POST['password']) ? "" : $error['error-password'] = ERROR_VALID_PASSWORD;
                if (empty($error['error-password'])) {
                    $this->adminModel->checkConfirmPassword($_POST['password'], $_POST['confirm-password']) ? $password = md5($_POST['password']) : $error['error-confirm-password'] = ERROR_CONFIRM_PASSWORD;
                }
            }

            $arr = array(
                'avatar' => $avatar,
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role_type' => $roleType,
            );

            $upload_file = UPLOADS_ADMIN . $_FILES['avatar']['name'];

            if ($this->adminModel->update($arr, "`id` = '{$id}'")) {
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