<?php

class UserModel extends BaseModel
{
    private $tableName = "user";

    public function getInfo($id)
    {
        return $this->conn->query("select * from $this->tableName ");
    }

    public function create($data) {
        $this->insert($data);
    }
    public function update() {}
    public function delete() {}
}