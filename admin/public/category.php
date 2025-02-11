
<!DOCTYPE html>
<html lang="en">
<?php
//   session_start();
$_SESSION["render"] ->setTable("categories");
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý thể loại</title>
    <link rel="stylesheet" href="../css/fonts/fonts.css<?php echo time(); ?>" />
    <link rel="stylesheet" href="../assets/fontawesome-free-6.5.1-web/css/all.min.css<?php echo time(); ?>" />
    <link rel="stylesheet" href="../css/admin/product.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/category.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/filter.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/account.css?v=<?php echo time(); ?> " />
    <script defer src="/admin.js/productAdmin.js"></script>
</head>

<body>

    <form class="admin__content--body__filter" id="product-filter-form">
        <h1>Lọc thể loại</h1>
        <div class="admin__content--body__filter--gr1">
            <div class="body__filter--field body__filter--nameClient filter_category" id="">
                <p>Tên thể loại</p>
                <input id="categoryName" type="text" placeholder="Nhập tên thể loại" />
            </div>
            <div class="body__filter--field body__filter--idClient filter_category" id="">
                <p>Mã thể loại</p>
                <input id="categoryId" type="text" placeholder="Nhập mã thể loại" />
            </div>
            <div class="body__filter--field body__filter--idClient filter_category" id="">
                <p>Trạng thái</p>
                <select name="status" id="statusSelect">
                    <option value="active" selected>Hoạt động</option>
                    <option value="inactive">Không hoạt động</option>
                    <option value="">Tất cả</option>
                </select>
            </div>
        </div>
        <p id="message"></p>

      

        <div class="body__filter--actions">
            <button class="body__filter--action__add">Thêm thể loại</button>
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
    
    <script defer src="../js/admin/category.js?v=<?php echo time(); ?> "></script>
</body>

</html>