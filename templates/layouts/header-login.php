<!DOCTYPE html>
<html lang="en">

<head>
    <!-- title là dữ liệu động---khai báo bên file login và đẩy sang file header- -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo !empty($data['pagetitle']) ? $data['pagetitle'] : 'Đăng Nhập Hệ Thống' ?> </title>
    <link rel="stylesheet" href=" <?php echo _WEB_HOST_TEMPLATE ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/style.css">
</head>

<body>