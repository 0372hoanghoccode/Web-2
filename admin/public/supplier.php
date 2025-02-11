<!DOCTYPE html>
<html lang="en">
<?php
//   session_start();
$_SESSION["render"] ->setTable("suppliers");
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


</head>

<body>
    <form class="admin__content--body__filter">
        <h1>Lọc thông tin nhà cung cấp</h1>
        <div class="admin__content--body__filter--gr1">
            <div class="body__filter--field body__filter--nameClient" >
                <p>Tên nhà cung cấp</p>
                <input type="text" id="supplierName" placeholder="Nhập tên tác giả" />
            </div>
            <div class="body__filter--field body__filter--idClient" >
                <p>Mã Nhà cung cấp</p>
                <input type="text" id="supplierId" placeholder="Nhập mã tác giả" />
                
            </div>
            
            <div class="body__filter--field body__filter--idClient filter_category" id="">
                <p>Trạng thái</p>
                <select name="status" id="statusSelect">
                    <option value="active">Hoạt động</option>
                    <option value="inactive">Không hoạt động</option>
                    <option value="">Tất cả</option>
                </select>
            </div>
           
        </div>
        <p id="message"></p>


        <div class="body__filter--actions">
        <button type="button" class="body__filter--action__add">Thêm nhà cung cấp</button>
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
    
    <script defer src="../js/admin/supplier.js?v=<?php echo time(); ?> "></script>

</body>

</html>