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
        if(!$result) die("Câu lệnh không đúng");
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