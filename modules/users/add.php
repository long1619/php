<!-- chặn người dùng truy cập bằng cách gõ trên đường dẫn -->
<?php
if (!defined('_INCODE')) {
    die('come back......................');
}

layout('header');
layout('footer');
//-------- xử lí thêm người dùng
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
        // phải là email hợp lệ hay không
        if (!isEmail(trim($body['email']))) {
            $errors['email']['uniqued'] = 'Email không hợp lệ';
        } else {
            // kt có tồn tại trong Db hay không
            // $email = trim($body['email']);
            // $sql = "SELECT id FROM users WHERE email='$email'";

            // if (getRows($sql) > 0) {
            //     $errors['email']['uniqued'] = 'Địa chỉ Email đã tồn tại';
            // }
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

        $dataInsert = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'phone' => $body['phone'],
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),
            'status' => $body['status'],
            'createAt' => date('Y-m-d H:i:s'),
            'updateAt' => date('Y-m-d H:i:s'),

        ];
        $insertStatus = insertTable('users', $dataInsert);
        // echo ($activeToken);
        if ($insertStatus) {
            setflashData('msg', 'Thêm Mới Thành Công');
            setflashData('msg_type', 'success');
        } else {
            setflashData('msg', 'Vui Lòng Kt lại Dữ Liệu');
            setflashData('msg_type', 'danger');
        }
    } else {
        //   setflashData('msg', 'vui lòng kt dữ liệu đầu vào');
        // setflashData('msg_type', 'danger');
        // setflashData('old', $body);
        setflashData('error', $errors);
        // redirect('?modules=auth&action=register');
    }
}

$msg = getflashData('msg');
$msgtype = getflashData('msg_type');
$errors = getflashData('error');
// echo '<pre>';
// print_r($errors);
// echo '</pre>';
?>

<?php
getMsg($msg, $msgtype);
?>
<div style="margin: 60px">
    <h3 style="text-align: center; color:red">Thêm Người Dùng</h3>
    <form method="POST">

        <div class="row">
            <div class="col">

                <div class="form-group col-md-10">
                    <label for="inputPassword">Họ tên</label>
                    <input type="text" class="form-control" name="fullname" placeholder="Nhập Họ Tên">
                    <!-- hiển thị lỗi ra màn hình -->
                    <?php
                    echo (!empty($errors['fullname'])) ? '<span class="error">' . reset($errors['fullname']) . '</span>' : null;

                    ?>
                </div>

                <div class="form-group col-md-10">
                    <label for="inputPassword">Email</label>

                    <input type="text" class="form-control" name="email" placeholder="Nhập Email">

                    <?php
                    echo (!empty($errors['email'])) ? '<span class="error">' . reset($errors['email']) . '</span>' : null;

                    ?>
                </div>

                <div class="form-group col-md-10">
                    <label for="inputPassword">Số Điện Thoại</label>

                    <input type="text" class="form-control" name="phone" placeholder="Nhập Số Điện Thoại">
                    <?php
                    echo (!empty($errors['phone'])) ? '<span class="error">' . reset($errors['phone']) . '</span>' : null;

                    ?>

                </div>

            </div>
            <!---------------------------------->

            <div class="col">
                <div class="form-group col-md-10">
                    <label for="inputPassword">Mật Khẩu</label>

                    <input type="password" class="form-control" name="password" placeholder="Nhập Mật Khẩu">

                    <?php
                    echo (!empty($errors['password'])) ? '<span class="error">' . reset($errors['password']) . '</span>' : null;

                    ?>
                </div>
                <div class="form-group col-md-10">
                    <label for="inputPassword">Xác Nhận Mật Khẩu</label>

                    <input type="password" class="form-control" name="confirmpassword"
                        placeholder="Nhập Xác Nhận Mật Khẩu">

                    <?php
                    echo (!empty($errors['confirmpassword'])) ? '<span class="error">' . reset($errors['confirmpassword']) . '</span>' : null;

                    ?>
                </div>
                <div class="form-group col-md-10">
                    <label for="inputPassword">Trạng Thái</label>

                    <select name="status" id="" class="form-control">
                        <option value="0">Chưa Kích Hoạt</option>
                        <option value="1">Kích Hoạt</option>

                    </select>
                </div>

                <br>

            </div>

        </div>
        <div class="form-group text-center">

            <button type="submit" class="btn btn-success "> Thêm Người Dùng</button>
            <a class="nav-link" href="<?php echo  _WEB_HOST_ROOT . ' ?modules=users&action=list' ?>">Về Trang Chủ </a>

        </div>
    </form>

</div>