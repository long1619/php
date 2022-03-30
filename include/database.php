<?php
require_once 'include/connect.php';

function query($sql, $data = [], $statemtStatus = false)
{   
     global $conn;
    $query = false;

    try {
        // tạo statemt
        $statemt = $conn->prepare($sql);
        // mảng data
        if (empty($data)) { 
            $query = $statemt->execute(); //chạy
        } else {
          
            $query = $statemt->execute($data); //chạy
        }

    } catch (Exception $exception) {
        require_once 'modules/error/database.php'; // import file báo lỗi db\
        die();
    }
    //-----------------------------------------------------
    if ($statemtStatus && $query) {
        return $statemt;
    } 
        return $query;

}

//-----------------dataInsert truyền vào là mảng
function insertTable($table, $dataInsert)
{

    $keyarr = array_keys($dataInsert);
    $fieldstr = implode(',', $keyarr);
    $valuestr = ':'. implode(', :',$keyarr);
    //-----
    $sql = 'INSERT INTO '.$table.'('.$fieldstr.') VALUES ('.$valuestr.')';
  
    return query($sql, $dataInsert);
    // echo $sql;
    // die();
} 

//hàm sửa
function updateTable($table, $dataUpdate, $conditon ='')
{
    $updateStr = '';
    foreach ($dataUpdate as $key=> $value) {
        $updateStr.=$key.'=:'.$key.', ';
    }
    //cắt dấu , thừa ở đuôi
    $updateStr = rtrim($updateStr, ', '); 
    //trường hợp nếu có id-----
    if (!empty($conditon)) {
        $sql = 'UPDATE '.$table.' SET '.$updateStr.' WHERE '.$conditon;
    } else {
        $sql = 'UPDATE '.$table.' SET '.$updateStr;
    }
    echo $sql;

    return query($sql, $dataUpdate);

} 

    //hàm xóa
    function deleteTable($table, $conditon = '')
    {
        $sql = 'DELETE FROM '.$table.' WHERE '.$conditon;
        return query($sql);
    }

    // lấy tất cả dữ liệu từ câu lệnh sql
    function getRaw($sql)
    {
        $statemt = query($sql, [], true);
        //check có phải object hay k
        if (is_object($statemt)) {
            $dataFetch = $statemt->fetchAll(PDO::FETCH_ASSOC);
            return $dataFetch;
        }
        return false;

    }
    // lấy dữ liệu 1 bản ghi đầu tiên
    function firstRaw($sql)
    {
        $statemt = query($sql, [], true);
        //check có phải object hay k
        if (is_object($statemt)) {
            $dataFetch = $statemt->fetch(PDO::FETCH_ASSOC);
            return $dataFetch;
        }
        return false;

    }
    //--------lấy dữ liệu theo field table-----------------
    function get($table, $field = '*', $conditon = '')
    {
        $sql = "SELECT '.$field.'FROM '.$table.'";

        if (!empty($conditon)) {
            $sql .= 'WHERE' . $conditon;
        }
        return getRaw($sql);
    }
    //-------------------------
    function first($table, $field = '*', $conditon = '')
    {
        $sql = "SELECT '.$field.'FROM '.$table.'";

        if (!empty($conditon)) {
            $sql .= 'WHERE' . $conditon;
        }
        return getRaw($sql);
    }
//------------------------------------------------
function getRows($sql){
    $statemt=query($sql,[],true);
    if(!empty($statemt)){
        return $statemt->rowCount();
    }
}
