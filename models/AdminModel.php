<?php
require_once "models/BaseModel.php";

class AdminModel extends BaseModel
{
    public $table;
    public $db;

    function __construct()
    {
        $this->table = "admin";
        $this->db = DB::getInstance();
    }

    public function getSearch($conditions = [])
    {
        $where = "`del_flag`=" . ACTIVED;
        $name = "";
        $email = "";
        if (!empty($conditions['name'])) {
            $where .= " AND name like '%{$conditions['name']}%' ";
            $name = $conditions['name'];
        }
        if (!empty($conditions['email'])) {
            $where .= " AND email like '%{$conditions['email']}%'";
            $email = $conditions['email'];
        }

        $column = isset($conditions['column']) ? $conditions['column'] : "id";
        $sort = isset($conditions['sort']) ? $conditions['sort'] : "";
        $orderBy = "ORDER BY `{$column}` {$sort}";

        $page = isset($conditions['page']) ? $conditions['page'] : "1";
        $start = ($page - 1) * RECORD_PER_PAGE;
        $totalPage = ceil($this->getTotalRow($name, $email) / RECORD_PER_PAGE);
        $limit = "limit {$start}, " . RECORD_PER_PAGE;

        $query = "SELECT `id`, `name`, `email`, `avatar`, `role_type` from {$this->table} where $where $orderBy $limit";

        $data = $this->db->query($query)->fetchAll();

        return [
            'data' => $data,
            'totalPage' => $totalPage,
        ];
    }

    public function getInfoById($id)
    {
        return $this->db->query("SELECT `id`, `avatar`, `name`, `email`, `password`, `role_type` FROM `{$this->table}` WHERE `id` = '{$id}' AND `del_flag` = " . ACTIVED)->fetch();
    }

    public function getRoleAdmin($email)
    {
        return $this->db->query("SELECT `role_type` FROM `{$this->table}` WHERE `email` = '{$email}' AND `del_flag` = " . ACTIVED)->fetch();
//        return $this->getQuery(['role_type'], ['email' => $email]);
    }

    public function getAdminId($email)
    {
        return $this->db->query("SELECT `id` FROM `admin` WHERE `email` LIKE '{$email}' AND `del_flag` = " . ACTIVED)->fetch();

//        return $this->getQuery(['id'], ['email' => $email]);
    }

    public function checkAdminLogin($email, $password)
    {
        return $this->db->query("SELECT `id`, `email`, `role_type` FROM `{$this->table}` WHERE `email` LIKE '{$email}' AND `password` LIKE '{$password}' AND `del_flag` = " . ACTIVED)->fetch();
    }
}