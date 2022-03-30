<?php
//use mail
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// require_once 'modules=auth&action=login';
//gldvfoebxnrpdsiu  - mật khẩu ứng dụng gmail
//-----------------------------------------MAIL--------------------------
function sendMail($to, $Subject, $content)
{

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = 'duclong1619@gmail.com'; //SMTP username
        $mail->Password = 'gldvfoebxnrpdsiu'; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        //Recipients
        $mail->setFrom('duclong1619@gmail.com', 'LongMail');
        $mail->addAddress($to); //Add a recipient
        //Content-- nội dung Email
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = $Subject;
        $mail->Body = $content;
        //--------------------------
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ),
        );
        return $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
//--------------------------------------------------------------------
function layout($layoutname = 'header', $data = [])
{
    //check file có tồn tại hay không
    if (file_exists('templates/layouts/' . $layoutname . '.php')) {
        require_once 'templates/layouts/' . $layoutname . '.php';
    }
    // if (file_exists(_WEB_PATH_TEMPLATE . '/layouts/' . $layoutname . '.php')) {
    //     require_once _WEB_PATH_TEMPLATE . '/layouts/' . $layoutname . '.php';
    // }
}
//-----------------------------//
//kt phương thức post
function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Chỗ này 2 dấu bằng nhé, lúc trước em để 1 dáu =
        return true;
    }
    return false;
}

//---kt phương thức get
function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') { //Chỗ này 2 dấu bằng nhé, lúc trước em để 1 dáu =
        return true;
    }
    return false;
}

//----lấy giá trị phương thức get và post

function getBody()
{
    // if (isGet()){
    //     return $_GET;
    // }
    $bodyarr = [];
    if (isGet()) {
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $key = strip_tags($key); //loại bỏ thẻ html vd: id<span>ooo</span> => kết quả là idspan
                if (is_array($value)) {
                    // nếu là mảng {
                    $bodyarr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $bodyarr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }

    //----------------------------------------------------
    if (isPost()) {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                //kt key
                $key = strip_tags($key);
                //kt mảng----
                if (is_array($value)) {
                    $bodyarr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $bodyarr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }
    return $bodyarr;
}

//---------------------------------------------------------------------
//kt validate
function isEmail($email)
{
    $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}
//----
function isNumberInt($number, $range = [])
{
    if (!empty($range)) {
        $options = ['options' => $range];
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT, $options);
    } else {
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
    }

    return $checkNumber;
}
//-----------
function isFloat($number, $range = [])
{
    if (!empty($range)) {
        $options = ['options' => $range];
        $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT, $options);
    } else {
        $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT);
    }

    return $checkNumber;
}
//-------------check sđt
function isPhone($phone)
{
    $checkfirstZezo = false;
    // check phone có số 0 đứng đầu hay không
    if ($phone['0'] == '0') {
        $checkfirstZezo = true;
        $phone = substr($phone, 1); // vị trí số 1
    }
    $checklastZezo = false; // check xem có phải số nguyên hay không
    if (isNumberInt($phone) && strlen($phone) == 9) {
        $checklastZezo = true;
    }
    if ($checkfirstZezo && $checklastZezo) {
        return true;
    }
    return false;
}
// hàm chuyển hướng
function redirect($path = "index.php")
{
    header("Location:$path");
    exit;
}
//----------hàm tạo thông báo
function getMsg($msg, $type = 'success')
{
    if (!empty($msg)) {
        echo '<div class ="alert alert-' . $type . '">';
        echo $msg;
        echo '</div>';
    }
}
//-------------
function isLogin()
{
    //----kt trạng thái đăng nhập--hứng thông báo active
    $checkLogin = false;
    if (getSession('loginToken')) {
        $tokenLogin = getSession('loginToken');
        $queryToken = firstRaw("SELECT userId  FROM logintoken WHERE token='$tokenLogin'");
        //------
        if (!empty($queryToken)) {
            // nếu tồn tại
            $checkLogin = true;
        } else {
            removeSession('loginToken');
        }
    }
    return $checkLogin;
}