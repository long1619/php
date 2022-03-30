<?php
layout('header-login');
layout('footer-login');
$token = getBody()['token'];

// xử lí xong ---update status và xóa token
if (!empty($token)) {
    $tokenQuery = firstRaw("SELECT id,fullname, email FROM users WHERE activeToken='$token'");
    print_r( $tokenQuery); 
    // echo '<br>';
 
//     //kt tokenQuery 
    if (!empty($tokenQuery)) {
        $userId = $tokenQuery['id'];
        // echo $userId;
        //update dữ liệu 
        $dataUpdate = [
            'status' => 1,
            'activeToken' => null

        ];

        //thực hiện update
        $updateStatus = updateTable('users', $dataUpdate , "id=$userId");
        //---kt $updateStatus
        if (!empty($dataUpdate)) {
            setflashData('msg', 'kích hoạt tài khoản thành công');
            setflashData('msg_type', 'success');
            // //tạo link login
            $loginLink= _WEB_HOST_ROOT.'?modules=auth&action=login';
            $Subject.= 'kích hoạt tài khoản thành công';
            $content.= 'chúc mừng'.$tokenQuery['fullname'].'đã kích hoạt tài khoản thành công';

            $content.= 'Trân trọng cảm ơn ';
        } else {
            setflashData('msg', 'kích hoạt tài khoản không thành công,vui lòng liên hệ quản trị viên');
            setflashData('msg_type', 'danger');
        }
        redirect('?modules=auth&action=login');
    } else {
        getMsg('liên kết không tồn tại hoặc đã hết hạn', 'danger');
    }

// khi token k tồn tại
} else {
    getMsg('Không Tồn Tại Token', 'danger');
}

