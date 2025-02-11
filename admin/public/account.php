<!DOCTYPE html>
<html lang="en">
<?php
//   session_start();
$_SESSION["render"] ->setTable("accounts");
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home page</title>
    <link rel="stylesheet" href="../css/fonts/fonts.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../assets/fontawesome-free-6.5.1-web/css/all.min.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../css/admin/product.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/filter.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/account.css?v=<?php echo time(); ?> " />
    <script defer src="../js/admin/account.js?v=<?php echo time(); ?> "></script>
</head>

<body>
    <form class="admin__content--body__filter">
        <h1>Lọc thông tin người dùng</h1>

        <div class="admin__content--body__filter--gr1">
            <div class="body__filter--field body__filter--idClient" >
                <p>Mã khách hàng</p>
                <input type="text" placeholder="Nhập mã khách hàng" id="userIdClient"/>
            </div>
            <div class="body__filter--field body__filter--roleClient" >
                <p>Loại tài khoản</p>
                <select id="userRoleClient">
                    <option value="" selected>Tất cả</option>
                    <option value="3" >Người dùng</option>
                    <option value="1">Quản trị viên</option>
                    <option value="2">Nhân viên</option>
                </select>
            </div>

            <div class="body__filter--field body__filter--statusClient" >
                <p>Trạng Thái</p>
                <select id="userStatus">
                <option value="active">Hoạt động</option>
                    <option value="inactive">Không hoạt động</option>
                    <option value="">Tất cả</option>
                </select>
            </div>
            
        </div>

        <!-- <div class="admin__content--body__filter--gr1">
            <div class="body__filter--field body__filter--dateClient" id="userBeginDate">
                <label for="userDate">Từ ngày:</label>
                <input type="date" id="beginDate" />
            </div>

            <div class="body__filter--field body__filter--dateClient" id="userEndDate">
                <label for="userDate">Đến ngày:</label>
                <input type="date" id="endDate" />
            </div>
        </div> -->
        <div class="body__filter--actions">
            <button class="body__filter--action__add">Thêm tài khoản</button>
            <div>
                <button type="reset" class="body__filter--action__reset">Reset</button>
                <button class="body__filter--action__filter">Lọc</button>
            </div>
        </div>
    </form>
    <div id="sqlresult"></div>
    <div class="result"></div>
    <!-- Start: Modal Edit -->
    <div id="modal" class="modal">
        <div class="modal-content">

        </div>
    </div>
    <!-- End: Modal Edit -->
</body>
</html>