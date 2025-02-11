<!DOCTYPE html>
<html lang="en">
<?php
//   session_start();
$_SESSION["render"] ->setTable("goodsreceipts");
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
    <link rel="stylesheet" href="../css/admin/receipt.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


</head>

<body>
<form class="admin__content--body__filter" id="product-filter-form">
        <h1>Lọc đơn nhập hàng</h1>
        <p>* Lưu ý: Định dạng dữ liệu ngày được hiển thị là dạng dd/mm/yyyy</p>
        <div class="admin__content--body__filter--gr1">
            <div class="body__filter--field body__filter--nameClient" id="">
                <p>Tên nhà cung cấp</p>
                <input id="supplierName" type="text" placeholder="Nhập tên nhà cung cấp" />
            </div>
            <div class="body__filter--field body__filter--idClient" id="">
                <p>Mã đơn nhập hàng</p>
                <input id="id" type="text" placeholder="Nhập mã đơn nhập hàng" />
            </div>
            <div class="body__filter--field body__filter--idClient" id="">
                <p>Người nhập hàng</p>
                <input id="staff_id" type="text" placeholder="Nhập tên người nhập hàng" />
            </div>
        </div>

        <div class="admin__content--body__filter--gr1">
        
            <div class="body__filter--field body__filter--datefrom" >
                <label>Từ ngày</label>
                <input type="date" id="date_start" />
                <p id="message_date_begin" class = "message"></p>

            </div>
            <div class="body__filter--field body__filter--dateto" >
                <label>Đến ngày</label>
                <input type="date" id="date_end" />
                <p id="message_date_end" class = "message"></p>

            </div>
        </div>
        <div class="admin__content--body__filter--gr1">
            <div class="body__filter--field body__filter--idClient" id="">
                <p>Lọc theo tổng tiền</p>
                <input id="price_start" type="text" placeholder="Từ" />
                <p id="message_price_begin" class = "message"></p>

            </div>
            <div class="body__filter--field body__filter--idClient" id="">
                <p>&#8203;</p>
                <input id="price_end" type="text" placeholder="Đến" />
                <p id="message_price_end" class = "message"></p>

            </div>

        </div>
        <div class="body__filter--actions">
            <button type="button" class="body__filter--action__add">Thêm đơn nhập hàng</button>
            <div>
                <button type="reset" class="body__filter--action__reset">Reset</button>
                <button class="body__filter--action__filter">Lọc</button>
            </div>
        </div>
    </form>

    <div id="addReiceptModal" class="modal">
  <div class="addModal-content">
  
    <span class="close">
                <i class="fa-solid fa-xmark"></i>
            </span>
            <div class="form">
                <!-- Code will be render here -->
                <!-- ... -->
            </div>
            <div class="form-actions">
      <button type="button" id="addButton" class="btn">Lưu</button>
    </div>
  </div>
</div>
<div id="editModal" class="modal" style="display: none;">
        <div class="editModal-content">
            <span class="close">
                <i class="fa-solid fa-xmark"></i>
            </span>
            <div class="form">
      <h2>Xem thông tin đơn nhập hàng</h2>
      <form id="form">
        <div class="input-field">
          <label for="idReceipt">Mã đơn nhập</label>
          <input type="text" id="id" readonly="">
        </div>
        <div class="input-field">
          <label for="supplierName">Tên nhà cung cấp</label>
          <input type="text" id="supplierName">
        </div>
        <div class="input-field">
          <label for="staff_id">Tên người nhập</label>
          <input type="email" id="staff_id">
        </div>
        <div class="input-field">
          <label for="total_price">Tổng giá</label>
          <input type="text" id="total_price" readonly="">
        </div>
        <div class="input-field">
          <label for="date_create">Ngày lập</label>
          <input type="text" id="date_create" readonly="">
        </div>
        <div class="book-table">
      <table id="Table">
       
      
      </table>
    </div>   
      </form>
    </div>
     
        </div>
    </div>
    <div id="sqlresult">
    </div>
    <!-- end -->
    <div class='result'></div>
    <!-- Start: Modal Edit -->
    
   
    </div>

    <script defer src="../js/admin/receipt.js?v=<?php echo time(); ?> "></script>

</body>

</html>