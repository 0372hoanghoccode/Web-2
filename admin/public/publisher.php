<!DOCTYPE html>
<html lang="en">
<?php
//   session_start();
$_SESSION["render"]->setTable("publishers");
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
  <link rel="stylesheet" href="../css/admin/publisher.css?v=<?php echo time(); ?> " />
</head>

<body>
  <form class="admin__content--body__filter">
    <h1>Lọc thông tin nhà xuất bản</h1>
    <div class="admin__content--body__filter--gr1">
      <div class="body__filter--field body__filter--nameClient">
        <p>Tên nhà xuất bản</p>
        <input id="publisherName" type="text" placeholder="Nhập tên nhà xuất bản" />
      </div>
      <div class="body__filter--field body__filter--idClient">
        <p>Mã nhà xuất bản</p>
        <input id="publisherId" type="number" placeholder="Nhập mã nhà xuất bản" />
      </div>
      <div class="body__filter--field body__filter--idClient">
        <p>Email nhà xuất bản</p>
        <input id="publisherEmail"  type="text" placeholder="Nhập email nhà xuất bản" />
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
      <button class="body__filter--action__add">Thêm nhà xuất bản</button>
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
  <!-- Start: Modal Edit -->
  <div id="modal"></div>
  <!-- End: Modal Edit -->
  <script defer src="../js/admin/publisher.js?v=<?php echo time(); ?> "></script>

</body>

</html>