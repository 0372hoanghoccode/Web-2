<!DOCTYPE html>
<html lang="en">
<?php
//   session_start();
$_SESSION["render"] ->setTable("products");
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="../css/fonts/fonts.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../assets/fontawesome-free-6.5.1-web/css/all.min.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../css/admin/filter.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/account.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/product.css?v=<?php echo time(); ?> " />
</head>

<body>

    <form class="admin__content--body__filter" id="product-filter-form">
        <h1>Lọc sản phẩm</h1>
        <p>* Lưu ý: Định dạng dữ liệu ngày được hiển thị là dạng dd/mm/yyyy</p>
        <div class="admin__content--body__filter--gr1">
            <div class="body__filter--field body__filter--nameClient" id="">
                <p>Tên Sản phẩm</p>
                <input id="productName" type="text" placeholder="Nhập tên sản phẩm" />
            </div>
            <div class="body__filter--field body__filter--idClient" id="">
                <p>Mã Sản Phẩm</p>
                <input id="productId" type="text" placeholder="Nhập mã sản phẩm" />
                <p id="message_productId" class = "message"></p>
            </div>
            <div class="body__filter--field body__filter--status" id="">
                <p>Loại sản phẩm</p>
                <select name="category" id="categorySelect">
                </select>
            </div>
        </div>

        <div class="admin__content--body__filter--gr1">
            <div class="body__filter--field body__filter--status" id="">
                <p>Loại ngày</p>
                <select name="cateDate" id="cateDateSelect">
                    <option value="">Chọn loại ngày</option>
                    <option value="create_date">Ngày tạo</option>
                    <option value="update_date">Ngày cập nhật</option>
                </select>
            </div>
            <div class="body__filter--field body__filter--datefrom" >
                <label>Từ ngày</label>
                <input type="date" id="date_start" />
                <p id="message_begin" class = "message"></p>
            </div>
            <div class="body__filter--field body__filter--dateto" >
                <label>Đến ngày</label>
                <input type="date" id="date_end" />
                <p id="message_end" class = "message"></p>
            </div>
        </div>
        <div class="admin__content--body__filter--gr1">
            <div class="body__filter--field body__filter--idClient" id="">
                <p>Lọc theo giá</p>
                <input id="price_start" type="text" placeholder="Nhập giá bắt đầu" />
                <p id="message_price_start" class = "message"></p>
            </div>
            <div class="body__filter--field body__filter--idClient" id="">
                <p>&#8203;</p>
                <input id="price_end" type="text" placeholder="Nhập giá kết thúc" />
                <p id="message_price_end" class = "message"></p>
            </div>
            <div class="body__filter--field body__filter--status" id="">
                <p>Trạng thái:</p>
                <select name="status" id="status">
                    <option value="1" selected>Đang kinh doanh</option>
                    <option value="2">Chưa kinh doanh</option>
                    <option value="0">Ngừng kinh doanh</option>
                    <option value="-1">Tất cả</option>
                </select>
            </div>
        </div>
        <div class="body__filter--actions">
            <button type="button" class="body__filter--action__add">Thêm sản phẩm</button>
            <div>
                <button type="reset" class="body__filter--action__reset">Reset</button>
                <button class="body__filter--action__filter">Lọc</button>
            </div>
        </div>
    </form>
    <div id="sqlresult">
    </div>
    <!-- end -->
    <div class='result'></div>
    <div id="modal"></div>
    <div id="multiselect"></div>



    <script defer src="../js/admin/product.js?v=<?php echo time(); ?> "></script>
</body>

</html>
