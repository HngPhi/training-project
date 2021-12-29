<?php
    // Connect DB
    function db_connect(){
        global $conn;
        $conn = mysqli_connect("localhost", "root", "", "basephp");
        if (!$conn) {
            die("Kết nối không thành công" . mysqli_connect_error());
        }
    }

    // Thực thi truy vấn
    function db_query($query_string){
        global $conn;
        $result = mysqli_query($conn, $query_string);
        if (!$result) { echo "Câu lệnh không đúng"; }
        return $result;
    }

    // Lấy ra 1 mảng trong CSDL
    function db_fetch_array($query_string){
        $result = array();
        $mysqli_result = db_query($query_string);
        while($row = mysqli_fetch_assoc($mysqli_result)){
            $result[] = $row;
        }
        mysqli_free_result($mysqli_result);
        return $result;
    }

    // Lấy một dòng trong CSDL
    function db_fetch_row($query_string) {
        global $conn;
        $result = array();
        $mysqli_result = db_query($query_string);
        $result = mysqli_fetch_assoc($mysqli_result);
        mysqli_free_result($mysqli_result);
        return $result;
    }

    //Lấy số bản ghi
    function db_num_rows($query_string) {
        global $conn;
        $mysqli_result = db_query($query_string);
        return mysqli_num_rows($mysqli_result);
    }

    //Thêm bản ghi
    function db_insert($table, $data){
        global $conn;
        //Lấy ra hết các key trong $data
        $fields = "(". implode(',', array_keys($data)) .")";
        $values = "";
        foreach($data as $key => $value){
//            if($value == "") $values .= "NULL, ";
//            else $values .= "'".real_escape_string($value)."', ";
            $values .= "'".real_escape_string($value)."', ";
        }
        $values = substr($values, 0, -2);
        $string = "INSERT INTO $table $fields VALUES ($values)";
        $query = db_query($string);
        return $query;
    }

    //Loại bỏ kí tự đặc biệt trong chuỗi cho câu lệnh truy vấn
    function real_escape_string($string){
        global $conn;
        return mysqli_real_escape_string($conn, $string);
    }