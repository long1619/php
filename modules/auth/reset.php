<?php
layout('header-login');
layout('footer-login');

$token = getBody()['token'];


if (!empty($token)) {
    $tokenQuery = firstRaw("SELECT id, email FROM users WHERE forgotToken='$token'");

    //kt tokenQuery--------/*
    if (!empty($tokenQuery)) {
        $userId = $tokenQuery['id'];
        $email=$tokenQuery['email'];
        //----------------------
        if (isPost()) {
            // redirect('?module=auth&action=reset&token='.$token);
            $body = getBody(); // lấy tất cả dữ liệu
            $errors = []; // lưu trữ lỗi
            //--kt password - phải nhập và phải lớn hơn 8 kí tự
            if (empty(trim($body['password']))) {
                $errors['password']['required'] = 'password bắt buộc phải nhập';
            }
            if (strlen(trim($body['password'])) < 8) {
                $errors['password']['min'] = 'password phải nhập hơn 8 kí tự';
            }
            //----confirmpassword -- phải nhập và giống trường mật khẩu
            if (empty(trim($body['confirmpassword']))) {
                $errors['confirmpassword']['required'] = 'Xác nhận mật khẩu bắt buộc phải nhập';
            } else {
                if (trim($body['password']) != trim($body['confirmpassword'])) {
                    $errors['confirmpassword']['match'] = 'Xác nhận mật khẩu không hợp lệ vui lòng nhập lại';
                }
            }

            //-------//
            if (empty($errors)) {
                $passwordHash = password_hash($body['password'], PASSWORD_DEFAULT);
                $dataUpdate = [
                    'password' => $passwordHash,
                    'forgotToken'=>null,
                    'updateAt'=>date('Y-m-d H:i:s')
                ];
                $updateStatus=updateTable('users',$dataUpdate,"id=$userId");
                //-------------//
                if($updateStatus){
                    //----------------------------
              setflashData('msg', 'Thay đổi mật khẩu thành công');
              setflashData('msg_type', 'success');
              // Gửi email khi thông báo đổi xong
              $Subject = 'Bạn vừa đổi mật khẩu';
              $content = 'Chúc mừng bạn đã đổi mật khẩu thành công ';

              sendMail($email,$Subject,$content);
          redirect('?module=auth&action=reset&token='.$token);
              }else{
                  setflashData('msg', 'Lỗi hệ thống, bạn không thể thay đổi mật khẩu');
                  setflashData('msg_type', 'danger');
              }  
                //------------//
            } else {
                setflashData('msg_type', 'danger');
                setflashData('error', $errors);
            }

            //-------//
        }

    } else {
        getMsg('Liên kết k tồn tại hoặc đã hết hạn', 'danger');
    }
    //----------------------
} else {
    getMsg('Link đã hết hạn', 'danger');
}
$msg = getflashData('msg');
$msgtype = getflashData('msg_type');
$errors = getflashData('error');
?>

<div class="col-6"style="margin :20px auto">
<h3 class="text-center"> Đặt lại mật khẩu</h3>
<?php
getMsg($msg, $msgtype);
?>
<form action=""method="POST">
    <div class="form-group">
      <label for="email">Mật Khẩu:</label>
      <input type="text" class="form-control"  placeholder="Nhập Mật Khẩu" name="password">
      <!-- hiển thị lỗi ra màn hình -->
<?php
echo (!empty($errors['password'])) ? '<span class="error">' . reset($errors['password']) . '</span>' : null;

?>
    </div>
    <div class="form-group">
      <label for="pwd">Nhập lại Mật Khẩu:</label>
      <input type="text" class="form-control" placeholder="Nhập lại Mật Khẩu" name="confirmpassword">
 <!-- hiển thị lỗi ra màn hình -->
<?php
echo (!empty($errors['confirmpassword'])) ? '<span class="error">' . reset($errors['confirmpassword']) . '</span>' : null;

?>
    </div>

    <button type="submit" class="btn btn-info btn-block">Xác Nhận</button>
<input id="my-input" type="hidden" name="forgot" value="<?php echo $token ?>">
     <p class="text-center" style="margin:20px"> <a href ="?modules=auth&action=forgot" >Quên Mật Khẩu </a> </p>
     <p class="text-center"style="margin:20px"><a href ="?modules=auth&action=register" >Đăng Kí Tài Khoản </a></p>
  </form>
</div>

