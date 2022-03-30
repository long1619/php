<?php

require_once _WEB_PATH_TEMPLATE . '/layouts/header.php';
require_once _WEB_PATH_TEMPLATE . '/layouts/footer.php';
// require_once 'templates/layouts/header-login.php';
layout('header-login'); // hàm viết bên file functions.php
// layout('header-login', $data);
// require_once 'templates/layouts/footer-login.php';
// $data = [
//     'pagetitle' => 'Đặt lại mật khẩu'
// ];
$msg = getflashData('msg');
$msgType = getflashData('msg_type');

//-----------------KT ĐĂNG NHẬP
if (isPost()) {
    $body = getBody();
    if (!empty($body['email'])) {
        $email = $body['email'];
        //tạo query
        $queryUser = firstRaw("SELECT id FROM users WHERE email='$email'");

        // kt queryUser
        if (!empty($queryUser)) { 
            $userId = $queryUser['id'];
            //tạo forgot token
            $forgotToken = sha1(uniqid() . time());
            //update
            $dataUpDate = [
                'forgotToken' => $forgotToken
            ];
            $updateStatus = updateTable('users', $dataUpDate, "id=$userId");
            //-------kt $upDateStatus có ok hay k
            if ($updateStatus) {
                //thiết lập gửi mail
                //***tạo link reset */
                $linkReset =  _WEB_HOST_ROOT . '?modules=auth&action=reset&token='. $forgotToken;

                $Subject = 'Yêu cầu khôi phục mật khẩu';
                $content = 'Chào bạn '. $email . '</br>';
                $content = 'chúng tôi nhận được yêu cầu khôi phục mật khẩu từ bạn, Vui lòng click vào link sau' . '</br>';
                $content .= $linkReset . '</br>';
                $content .= 'Trân trọng cảm ơn ';
                // Tiến hành gửi mail
                $sendStatus = sendMail($email, $Subject, $content);
                //----kt sendmail
                if ($sendStatus) {
                    setflashData('msg', 'Vui lòng kt email để có HD đặt lại MK');
                    setflashData('msg_type', 'success');
                } else {
                    setflashData('msg', 'Lỗi hệ thống, bạn khong thể sử dụng chức năng này');
                    setflashData('msg_type', 'danger');
                }
                //-----------------------
            } else {
                setflashData('msg', 'Lỗi hệ thống, bạn khong thể sử dụng chức năng này');
                setflashData('msg_type', 'danger');
            }
// -----------------
        } else {
            setflashData('msg', 'Địa chỉ Email không tồn tại');
            setflashData('msg_type', 'danger');
        }
        //-----------------//
    } else {
        setflashData('msg', 'Vui lòng nhập Email');
        setflashData('msg_type', 'danger');
    }
    // redirect('?modules=auth&action=forgot');
}
// -----------------
?>
<div class="col-6"style="margin :20px auto">
<h3 class="text-center"> Đặt lại mật khẩu </h3>
<?php
getMsg($msg, $msgType);
?>
<form action=""method="POST">
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control"  placeholder="Nhập email" name="email">
    </div>

    <button type="submit" class="btn btn-info btn-block">Submit</button>
  </form>
</div>



<?php

layout('footer-login');

?>