
<!DOCTYPE html>
<html lang="en">
<?php
//   session_start();
$_SESSION["render"] ->setTable("discounts");
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý mã giảm giá</title>
    <link rel="stylesheet" href="../css/fonts/fonts.css<?php echo time(); ?>" />
    <link rel="stylesheet" href="../assets/fontawesome-free-6.5.1-web/css/all.min.css<?php echo time(); ?>" />
    <link rel="stylesheet" href="../css/admin/product.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/category.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/discount.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/filter.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/account.css?v=<?php echo time(); ?> " />
    <script defer src="/admin.js/productAdmin.js"></script>
</head>

<body>

    <form class="admin__content--body__filter" id="product-filter-form">
        <h1>Lọc mã giảm giá</h1>
        <div class="admin__content--body__filter--gr1">
            <div class="body__filter--field body__filter--nameClient filter_category" id="">
                <p>Tên mã giảm giá</p>
                <input id="discountName" type="text" placeholder="Nhập tên mã giảm giá" />
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
      

      

        <div class="body__filter--actions">
        <button type="button" class="body__filter--action__add">Thêm mã giảm giá</button>

            <div>
                <button type="reset" class="body__filter--action__reset">Reset</button>
                <button type="submit" class="body__filter--action__filter">Lọc</button>

            </div>
        </div>
    </form>
    <div id="sqlresult">
    </div>
    <div class="result"></div>
    <div id="modal"></div>
    
    <script defer src="../js/admin/discount.js?v=<?php echo time(); ?> "></script>
</body>

</html>