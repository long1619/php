<?php
require_once _WEB_PATH_TEMPLATE . '/layouts/header.php';
require_once _WEB_PATH_TEMPLATE . '/layouts/footer.php';
// require_once 'templates/layouts/header-login.php';
layout('header-login'); // hàm viết bên file functions.php
// require_once 'templates/layouts/footer-login.php';

$data = [
    'pagetitle' => 'Đăng Nhập Hệ Thống',
];
layout('header-login', $data);
//----kt trạng thái đăng nhập--hứng thông báo active


if (isLogin()) {
    redirect('?modules=auth&action=login');
}
//-----------------KT ĐĂNG NHẬP
if (isPost()) {
    $body = getBody();
    if (!empty(trim($body['email'])) && (!empty(trim($body['password'])))) {
        //kt đăng nhập
        $email = $body['email'];
        $password = $body['password'];
        // echo $email . '</br>';
        // echo $password . '</br>';

        // tạo truy vấn - lấy thông tin user theo email
        $userQuery = firstRaw("SELECT id,password FROM users WHERE email ='$email'");
        print_r($userQuery) . '</br>';;
        //---------------email------------//
        if (!empty($userQuery)) {
            $passwordHash = $userQuery['password'];
            $userId = $userQuery['id'];

            //----------mật khẩu-----------------//
            if (password_verify($password, $passwordHash)) {
                // echo ' test ok';
                //tạo login tokenLogin
                $tokenLogin = sha1(uniqid() . time());
                //insert dữ liệu vào bảng login token
                $dataInsert = [
                    'userId' => $userId,
                    'token' => $tokenLogin,
                    'createAt' => date('Y-m-d H:i:s') //thua dau cach sau createAt
                ];

                $insertStatustoken = insertTable('logintoken', $dataInsert);

                // nếu insert thành công---
                if ($insertStatustoken) {
                    //lưu login token vào session
                    setSession('loginToken', $tokenLogin);
                    //chuyển hướng tới trang quản lí người dùng
                    redirect('?modules=users&action=list');
                } else {
                    setflashData('msg', 'Lỗi hệ thống, bạn không thể đăng nhập vào lúc này');
                    //  setflashData('msg_type', 'danger');
                }
                //----------------------
            } else {
                setflashData('msg', 'Mật khẩu không chính xác');
                setflashData('msg_type', 'danger');
            }
            //     //----------------------
        } else {
            setflashData('msg', 'Email không tồn tại');
            setflashData('msg_type', 'danger');
            redirect('?modules=auth&action=login');
        }

        //ngược lại khi k nhập -------
    } else {
        setflashData('msg', 'vui lòng nhập email và mật khẩu');
        setflashData('msg_type', 'danger');
        redirect('?modules=auth&action=login');
    }
}
$msg = getflashData('msg');
$msgType = getflashData('msg_type');
?>
<div class="col-6" style="margin :20px auto">
    <h3 class="text-center"> Đăng Nhập Hệ Thống</h3>
    <?php
    getMsg($msg, $msgType);
    ?>
    <form action="" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" placeholder="Nhập email" name="email">
        </div>
        <div class="form-group">
            <label for="pwd">Mật Khẩu:</label>
            <input type="password" class="form-control" placeholder="Nhập Mật Khẩu" name="password">
        </div>

        <button type="submit" class="btn btn-info btn-block">Submit</button>

        <p class="text-center" style="margin:20px"> <a href="?modules=auth&action=forgot">Quên Mật Khẩu </a> </p>
        <p class="text-center" style="margin:20px"><a href="?modules=auth&action=register">Đăng Kí Tài Khoản </a></p>
    </form>
</div>

<?php
layout('footer-login');

?>