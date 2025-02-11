<!DOCTYPE html>
<html lang="en">
<?php
//   session_start();
$_SESSION["render"] ->setTable("functions");
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home page</title>
    <link rel="stylesheet" href="../css/fonts/fonts.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../assets/fontawesome-free-6.5.1-web/css/all.min.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../css/admin/product.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/filter.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/author.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/account.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/supplier.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/role.css?v=<?php echo time(); ?> " />

</head>

<body>
    <form class="admin__content--body__filter">
        <h1>Lọc thông tin Quyền</h1>
        <div class="admin__content--body__filter--gr1">
            <div class="body__filter--field body__filter--nameClient" >
                <p>Tên quyền</p>
                <input type="text" id="roleName" placeholder="Nhập tên quyền" />
            </div>
            <div class="body__filter--field body__filter--idClient" >
                <p>Mã Quyền</p>
                <input type="text" id="roleId" placeholder="Nhập mã quyền" />
            </div>
            <!-- <div class="body__filter--field body__filter--idClient filter_category" id="">
                <p>Trạng thái</p>
                <select name="status" id="statusSelect">
                    <option value="active">Hoạt động</option>
                    <option value="inactive">Không hoạt động</option>
                    <option value="">Tất cả</option>
                </select>
            </div> -->
           
        </div>
        <p id="message"></p>

        <div class="body__filter--actions">
        <button type="button" class="body__filter--action__add">Sửa quyền nhân viên</button>
            <div>
                <button type="reset" class="body__filter--action__reset">Reset</button>
                <button class="body__filter--action__filter">Lọc</button>
            </div>
        </div>
    </form>
    <div id="sqlresult">
    </div>
    <div class="result"></div>
    <div id="modal"></div>
    
    <script defer src="../js/admin/role.js?v=<?php echo time(); ?> "></script>

</body>

</html>