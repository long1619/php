<?php  
if(isLogin()){
    //token
    $token=getSession('loginToken');
    deleteTable ('logintoken',"token='$token'");
    removeSession('loginToken');
    redirect('?modules=auth&action=login');
}

?> 