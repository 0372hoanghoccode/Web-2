<!DOCTYPE html>
<html lang="en">
<?php
//   session_start();
$_SESSION["render"]->setTable("orders");
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="../css/fonts/fonts.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../assets/fontawesome-free-6.5.1-web/css/all.min.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../css/admin/product.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="../css/admin/filter.css?v=<?php echo time(); ?> ">
    <link rel="stylesheet" href="../css/admin/order.css?v=<?php echo time(); ?> ">
</head>

<body>

    <form class="admin__content--body__filter">
        <h1>Lọc đơn hàng</h1>
        <p>* Lưu ý: Định dạng dữ liệu ngày tạo đơn được hiển thị là dạng dd/mm/yyyy</p>
        <div class="admin__content--body__filter--gr1">
            <div class="body__filter--field body__filter--idOrder">
                <p>Mã đơn hàng</p>
                <input id="idOrder" type="text" placeholder="Nhập mã đơn hàng" />
                <p id="message_idOrder" class = "message"></p>
            </div>
            <div class="body__filter--field body__filter--idClient">
                <p>Mã khách hàng</p>
                <input id="idCus" type="text" placeholder="Nhập mã khách hàng" />
                <p id="message_idCus" class = "message"></p>
            </div>

            <div class="body__filter--field body__filter--idClient">
                <p>Mã nhân viên</p>
                <input id="idStaff" type="text" placeholder="Nhập mã khách hàng" />
                <p id="message_idStaff" class = "message"></p>
            </div>
                <div class="body__filter--field body__filter--address">
        <p>Địa chỉ giao</p>
        <input id="address" type="text" placeholder="Nhập địa chỉ giao hàng" />
        <p id="message_address" class="message"></p>
    </div>
        </div>

        <div class="admin__content--body__filter--gr1">
            <div class="body__filter--field w-30 body__filter--status">
                <p>Trạng thái</p>
                <select name="" id="statusSelect">
                    <option value="all">Tất cả</option>
                    <option value="1">Chờ duyệt</option>
                    <option value="2">Đã Duyệt</option>
                    <option value="3">Đã Hủy</option>
                    <option value="4">Đang giao</option>
                    <option value="5">Đã giao</option>
                </select>
            </div>
            <div class="body__filter--field w-30 body__filter--orderDate">
                <label>Từ ngày:</label>
                <input id="date_begin" type="date" />
                <p id="message_begin" class = "message"></p>
            </div>

            <div class="body__filter--field w-30 body__filter--orderDate">
                <label>Đến ngày:</label>
                <input id="date_end" type="date" />
                <p id="message_end" class = "message"></p>

            </div>
        </div>
        <div class="body__filter--actions d-flex-right">
            <div>
                <button type="reset" class="body__filter--action__reset">Reset</button>
                <button type="button" class="body__filter--action__filter">Lọc</button>
            </div>
        </div>
    </form>

    <!-- end -->
    <div class="result"></div>


    <!-- Modal  -->
    <div class="order-modal hidden">
        <button class="close-modal">&times;</button>
        <div class="modal-content">
            <div class="modal-header">
                <h2>Chi tiết hóa đơn</h2>
            </div>
            <table class="orderdetail-table">
                <thead>
                    <tr>
                        <th scope="col">Stt</th>
                        <th scope="col">Tên Sản Phẩm</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Số Lượng</th>
                        <th scope="col">Thành Tiền</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <div class="del-btn-container">
                <!-- <input type="button" value="Hủy đơn hàng" class="cancel-order">
                <input type="button" value="Duyệt đơn hàng" class="confirm-order"> -->
            </div>
        </div>
    </div>
    <div class="overlay hidden"></div>

    <script defer src="../js/admin/order.js?v=<?php echo time(); ?>"></script>
</body>

</html>