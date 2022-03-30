<?php

//---mậy khẩu ứng dụng gửi mail---icjqpehvgqepjupr
//--------------------------tất cả mọi thứ đều chạy qua index-- ?module= ...&action=...
// tạo session
session_start();
//------------MAIL
require 'include/phpMailer/Exception.php';
require 'include/phpMailer/PHPMailer.php';
require 'include/phpMailer/SMTP.php';
require_once 'config.php';
require_once 'include/functions.php';
require_once 'include/connect.php';
require_once 'include/session.php';
require_once 'include/database.php';

// khai báo bên  file config
$modules = _MODULE_DEFAULT;
$action = _ACTION_DEFAULT;

if (!empty($_GET['modules'])) {
    if (is_string($_GET['modules'])) {
        $modules = trim($_GET['modules']);
        // echo $modules;
    }
}
//--------------------------------------
if (!empty($_GET['action'])) {
    if (is_string($_GET['action'])) {
        $action = trim($_GET['action']);
    }
}

// // echo $module; 
// // echo '<br>';
// echo $action ;
//------------kt đường  dẫn file
$path = 'modules/' . $modules . '/' . $action . '.php';

// echo $path;
if (file_exists($path)) {
    require_once $path;
} else {
    require_once 'modules/error/404.php';
}