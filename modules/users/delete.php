
<?php
// layout('header');
// layout('footer');
$body = getBody();
if (!empty($body['id'])) {
    $userId = $body['id']; // lấy id
    //kt id có tồn tại trong DB k
    $userDetialRows = getRows("SELECT id FROM users WHERE id=$userId");

    if ($userDetialRows > 0) {
        //thực hiện xóa-------1. xóa khóa ngoại bảng login token
        $deleteLoginToken = deleteTable('logintoken', "id=  $userId ");
        if ($deleteLoginToken) {
            //2.xóa bảng user
            $deleteUser = deleteTable('users', "id=  $userId ");
            if($deleteUser){
                setflashData('msg', 'Xóa người dùng thành công');
                setflashData('msg_type', 'success');
                redirect('?modules=users&action=list');

            }else{
                setflashData('msg', 'Xóa Thất Bại');
                setflashData('msg_type', 'danger'); 
            }
        }
    } else {
        setflashData('msg', 'Người dùng không tồn tại');
        setflashData('msg_type', 'danger');
    }
} else {
    setflashData('msg', 'không tồn tại');
    setflashData('msg_type', 'danger');
}
redirect('?modules=users&action=list');
?>