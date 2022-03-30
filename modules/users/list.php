<?php
// require_once 'include/functions.php';
layout('header-login');
layout('footer-login');
if (!isLogin()) {
  // nếu không tồn tại
  // redirect('?modules=auth&action=login');
}
//----xử lí lọc dữ liệu
$filter = '';
if (isGet()) {
  $body = getBody();
  // echo '<pre>';
  // print_r($body);
  // echo '</pre>';
  //---xử lí lọc status
  if (!empty($body['status'])) {
    $status = $body['status'];

    if ($status == 2) {
      $statusSql = 0; //----0 là chưa kích hoạt trong sql
    } else {
      $statusSql = 1;
    }

    if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
      $operator = 'AND';
    } else {
      $operator = 'WHERE';
    }

    $filter .= "WHERE status=.$statusSql";
  }
  //--- xử lí tìm kiếm
  if (!empty($body['keyword'])) {
    $keyword = $body['keyword'];
    // echo 'key đó là :' . $keyword;
    // echo '</br />';
    //kt where
    if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
      $operator = 'AND';
    } else {
      $operator = 'WHERE';
    }

    $filter .= " $operator fullname LIKE '%keyword%'";
  }
}

//------xử lí phân trang---------------------
//1. lấy ra tổng số id có
$allUser = getRows("SELECT id FROM users  $filter"); //vì có lọc nên bỏ   $filter vô
//2. mỗi trang có 3 bản ghi
$perpage = 3;
//3. Tính
$Maxpage = ceil($allUser / $perpage);

//4. xử lí hiện số trang page ----- page=1,page=2
if (!empty(getBody()['page'])) {
  $page = getBody()['page'];
  if ($page < 1 || $page > $Maxpage) {
    $page = 1;
  }
} else {
  $page = 1;
}
//5. tính toán ofset limit trong trang
/**
 * page 1 (0->3) -- tính (page-1)*$perpage =1-1=0*3=0
 * page 2 (3->hết)-- tính (page-1)*$perpage =2-1=1*3=3
 */
$offset = ($page - 1) * $perpage;
//lấy tất cả các bản ghi ------$filter
$listAll = getRaw("SELECT * FROM users  $filter ORDER BY createAt DESC LIMIT $offset,$perpage ");
//---- xử lí query string xử lí phân trang 
$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
  $queryString = $_SERVER['QUERY_STRING'];
  $queryString = str_replace('?modules=users', '', $queryString);
  // echo $queryString;
}
//---------------------------------------
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Menu</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo  _WEB_HOST_ROOT . ' ?modules=users&action=list' ?>">Trang Chủ </a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Thông Tin
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php echo  _WEB_HOST_ROOT . '?modules=auth&action=logout' ?>">Đăng
                        Xuất</a>


                </div>
            </li>
            <li class="nav-item">
                <!-- <a class="nav-link disabled" href="#">Disabled</a> -->
            </li>
        </ul>

    </div>
</nav>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 95%;
        margin-left: 25px;
    }

    td,
    th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;

    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }

    #qli {
        text-align: center;
        margin-bottom: 50px;

    }

    .form-group {
        width: 50%;
        margin-left: 20%;
    }

    .search {
        width: 80%;

    }
    </style>
</head>

<body>
    <form action="" method="GET">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <select name="status" class="form-control">
                        <option value="0">Chọn Trạng Thái</option>
                        <option value="1" <?php echo (!empty($status) && $status == 1 ? 'selected' : null) ?>>Kích
                            Hoạt</option>
                        <option value="2" <?php echo (!empty($status) && $status == 2 ? 'selected' : null) ?>>Chưa Kích
                            Hoạt</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <input type="search" class="form-control search " placeholder="Nhập từ khóa....." name="keyword"
                    value="<?php echo (!empty($keyword)) ? $keyword : null; ?>">
            </div>

            <div class="col-md-4">
                <button type="submit" class="btn btn-success  "> Tìm Kiếm</button>
                <a href="?modules=users&action=add" class="btn btn-success">Thêm Người Dùng</a>
            </div>
        </div>
        <!-- //để giữ nguyên url - k bị thay đổi khi submit  -->
        <input type="hidden" name="modules" value="users">
        <input type="hidden" name="action" value="list">
    </form>

    <!---------------------------------------------------------------->
    <div>
        <h2 id='qli'>Quản Lí Người Dùng</h2>

        <table>
            <tr>
                <th>STT</th>
                <th>Email</th>
                <th>Họ Tên</th>
                <th>SĐT</th>
                <th>Trạng Thái</th>
                <th>Sửa</th>
                <th>Xóa</th>
            </tr>
            <?php
      if (!empty($listAll)) {
        $count = 0;
      }

      foreach ($listAll as $key => $value) {
        $count++;
      ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $value['email']; ?></td>
                <td><?php echo $value['fullname'] ?></td>
                <td><?php echo $value['phone'] ?></td>
                <td><?php echo $value['status'] == 1 ? 'kích hoạt' : 'chưa kích hoạt' ?></td>
                <td><a href="<?php echo _WEB_HOST_ROOT . '?modules=users&action=edit&id=' . $value['id'] ?>"
                        class=" btn btn-warning btn-sm"> <i class="fa fa-edit"> </i></a>
                </td>

                <td>
                    <a href="<?php echo _WEB_HOST_ROOT . '?modules=users&action=delete&id=' . $value['id'] ?>"
                        onclick="return confirm('bạn có muốn xóa không')" class=" btn btn-danger btn-sm"> <i
                            class="fa fa-trash-o"> </i></a>
                </td>

            </tr>
    </div>
    <?php
      }
?>

    <!-- <td colspan="7">
<div class="alert alert-danger text-center">Không có dữ liệu</div>
</td> -->
    </table>
    <!-- phân Trang---------------------------- -->
    <nav aria-label="Page navigation example" class="text-center">
        <ul class="pagination">

            <!-- điều kiện----page>1-->
            <?php
    if ($page > 1) {
      $prevpage = $page - 1;
      echo '
<li class="page-item">
<a class="page-link" href="' . _WEB_HOST_ROOT . '?modules=users&action=list&page=' . $prevpage . '" aria-label="Trước">
<span aria-hidden="true">&laquo;</span>
</a>
</li>
';
    }
    ?>

            <?php
    for ($i = 1; $i <= $Maxpage; $i++) { ?>
            <!-- thêm class active xanh -->
            <li class="page-item <?php echo ($i == $page) ? 'active' : false; ?>">
                <a class="page-link" href="<?php echo _WEB_HOST_ROOT . '?modules=users&action=list&page=' . $i ?>">

                    <?php echo $i ?> </a>
            </li>
            <?php
    }
    ?>

            <?php
    if ($page < $Maxpage) {
      $nextPage = $page + 1;
      echo ' <li class="page-item">
<a class="page-link" href="' . _WEB_HOST_ROOT . '?modules=users&action=list&page=' . $nextPage . '" aria-label="Sau">
<span aria-hidden="true">&raquo;</span>
</a>
</li>';
    }
    ?>
        </ul>
    </nav>
</body>

</html>