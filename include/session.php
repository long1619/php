<?php
// hàm gán session-------------------
function setSession($key, $value)
{
    if (!empty(session_id())){ // kiểm tra id {
    $_SESSION[$key] = $value;
}
// ngược lại
return false;
}

// hàm đọc session----------------------
    function getSession($key = '')
{
        if (empty($key)) {
            return $_SESSION;
        } else {}
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return false; //không lấy được
    }

// hàm xóa session------------------
    function removeSession($key = '')
{
        if (empty($key)) {
            session_destroy();
            return true;
        } else {
            if (isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
                return true;
            }
        }
        return false;
    }
//----------------------------------------------------
//hàm gán flash data
    function setflashData($key, $value)
{
        $key = 'flash_'.$key;
        return setSession($key, $value);
    }
//hàm đọc flash data
    function getflashData($key)
{
        $key = 'flash_'.$key;
        $data = getSession($key);
        removeSession($key);
        return $data;
    }
