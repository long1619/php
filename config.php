<?php  
// cấu hình module mặc đính
const _MODULE_DEFAULT ='home';
const _ACTION_DEFAULT ='list'; //nguyên nhân là chỗ này, file là list. nhưng khai báo là lists (không load được file)
// ngăn chặn truy cập trực tiếp vào file
const _INCODE =true;
 
// thiết lập host                 //-địa chỉ trang chủ
define('_WEB_HOST_ROOT','http://'.$_SERVER['HTTP_HOST'].'/UserManager');
define('_WEB_HOST_TEMPLATE', _WEB_HOST_ROOT.'/templates'); // link -----link js,css(đường dẫn tuyệt đối)    

//thiết lập path-- đường dẫn 
define('_WEB_PATH_ROOT',__DIR__); // DIR lấy thư mục chứa file hiện tại 
define('_WEB_PATH_TEMPLATE',_WEB_PATH_ROOT.'/templates');

// thiết lập kết nối database-------------------
const _HOST ='localhost';
const _USER='root';
const _PASS='';
const _DB='import_test';
const _DRIVER='mysql';

?>