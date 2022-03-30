<?php
// hàm này viết bên file functions là đường dẫn
layout('header-login');
layout('footer-login');
//---------------------------------------

if (isPost()) {

    $body = getBody(); // lấy tất cả dữ liệu
    $errors = []; // lưu trữ lỗi

    //---validate form---- họ tên/ bắt buộc phải nhập và lớn hơn 5 kí tự
    if (empty(trim($body['fullname']))) {

        $errors['fullname']['required'] = "Họ tên bắt buộc phải nhập";
    } else {
        if (strlen(trim($body['fullname'])) < 5) {
            $errors['fullname']['required'] = 'Phải nhập hơn 5 kí tự';
        }
    }

    //-----------phone
    if (empty(trim($body['phone']))) {
        $errors['phone']['required'] = 'Số điện thoại bắt buộc phải nhập';
    } else {
        if (!isPhone(trim($body['phone']))) {
            $errors['phone']['isphone'] = 'Số điện thoại k hợp lệ';
        }
    }
    //------------email -phải nhập, đúng định dạng , không được trùng
    if (empty(trim($body['email']))) {
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    } else {
        if (!isEmail(trim($body['email']))) {
            $errors['email']['uniqued'] = 'Email không hợp lệ';
        } else {
            // kt có tồn tại trong Db hay không
            $email = trim($body['email']);
            $sql = "SELECT id FROM users WHERE email='$email'";
            if(getRows($sql)>0){
                $errors['email']['uniqued'] = 'Địa chỉ Email đã tồn tại';
            }
        }
    }
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
//----------------kt mảng error
    if (empty($errors)) {
        $activeToken = sha1(uniqid().time());
        $dataInsert = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'phone' => $body['phone'],
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),

            'activeToken' => $activeToken,

            'createAt' => date('Y-m-d H:i:s'),
            'updateAt'=>date('Y-m-d H:i:s')

        ];
        $insertStatus = insertTable('users', $dataInsert);
// echo ($activeToken);
        if ($insertStatus) {
            //tạo link active
            $linkActive = _WEB_HOST_ROOT.'?modules=auth&action=active&token='.$activeToken;
            // echo $linkActive;
            //-------------thiết lập gửi gmail-----------------------
            $Subject = $body['fullname'].' vui lòng kích hoạt tài khoảnnnn';
            $content = 'Chào bạn: '.$body['fullname']. '</br>';
            $content = 'Vui lòng click vào link dưới đây để kích hoạt tài khoản' . '</br>';
            // $content = $linkActive . '</br>';
            $content = 'Trân trọng cảm ơn '.'</br>'.$linkActive;
  //Tiến hành gửi Mail
            $sendStatus = sendMail($body['email'], $Subject, $content);

// echo $linkActive;
            if($sendStatus){
                  //----------------------------
            setflashData('msg', 'Đăng kí tài khoản Thành công.Vui lòng kt email để kích hoạt tài khoản');
            setflashData('msg_type', 'success');
            }else{
                setflashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
                setflashData('msg_type', 'danger');
            }    
        }
    } else {
        //   setflashData('msg', 'vui lòng kt dữ liệu đầu vào');
        setflashData('msg_type', 'danger');
        // setflashData('old', $body);
        setflashData('error', $errors);
        // redirect('?modules=auth&action=register');
    }
} 

$msg = getflashData('msg');
$msgtype = getflashData('msg_type');
$errors = getflashData('error');
// $old=getflashData('old'); // giữ lại value khi các trường nhập đúng hoặc sai
?>

        <div class="col-6"style="margin :20px auto">
        <h3 class="text-center"> Đăng Kí Tài Khoản</h3>
    <form action=""method="POST">
<div class="form-group">
<?php
getMsg($msg, $msgtype); 
?>

      <label for="name">Họ Tên:</label>
      <input type="text" class="form-control"  placeholder="Nhập Họ Tên" name="fullname"
      value="">
<!-- hiển thị lỗi ra màn hình -->
<?php
echo (!empty($errors['fullname'])) ? '<span class="error">' . reset($errors['fullname']) . '</span>' : null;

?>
    </div>
    <div class="form-group">
      <label for="phone">Số Điện Thoại:</label>
      <input type="text" class="form-control" id="phone" placeholder="Nhập phone" name="phone"value="" >
<!-- hiển thị lỗi ra màn hình -->
<?php
echo (!empty($errors['phone'])) ? '<span class="error">' . reset($errors['phone']) . '</span>' : null;

?>
   
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Nhập email" name="email"value="" >
<!-- hiển thị lỗi ra màn hình -->
<?php
echo (!empty($errors['email'])) ? '<span class="error">' . reset($errors['email']) . '</span>' : null;

?>
   
    </div>
    <div class="form-group">
      <label for="pwd">Mật Khẩu:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Nhập Mật Khẩu" name="password"
      value="" >
<!-- hiển thị lỗi ra màn hình -->
<?php
echo (!empty($errors['password'])) ? '<span class="error">' . reset($errors['password']) . '</span>' : null;

?>
   
    </div>
    <div class="form-group">
      <label for="pwd">Nhập Lại Mật Khẩu:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Nhập Lại Mật Khẩu" name="confirmpassword"
      value="" >
<!-- hiển thị lỗi ra màn hình -->
<?php
echo (!empty($errors['confirmpassword'])) ? '<span class="error">' . reset($errors['confirmpassword']) . '</span>' : null;

?>
   
    </div>
    <button type="submit" class="btn btn-info btn-block">Đăng Kí</button>

     <p class="text-center"style="margin:20px"><a href ="?modules=auth&action=login" >Đăng Nhập Hệ Thống </a></p>
  </form>
</div>
