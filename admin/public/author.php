<!DOCTYPE html>
<html lang="en">
<?php
//   session_start();
$_SESSION["render"] ->setTable("authors");
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
    <link rel="stylesheet" href="../css/admin/author.css?v=<?php echo time(); ?> " />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">



</head>

<body>
    <form class="admin__content--body__filter">
        <h1>Lọc thông tin tác giả</h1>
        <div class="admin__content--body__filter--gr1">
            <div class="body__filter--field body__filter--nameClient filter_author" >
                <p>Tên tác giả</p>
                <input type="text" id="authorName" placeholder="Nhập tên tác giả" />
            </div>
            <div class="body__filter--field body__filter--idClient filter_author" >
                <p>Mã tác giả</p>
                <input type="text" id="authorId" placeholder="Nhập mã tác giả" />
            </div>
            <div class="body__filter--field body__filter--idClient filter_author" >
                <p>Email tác giả</p>
                <input type="text" id="authorEmail" placeholder="Nhập email tác giả" />
            </div>
            <div class="body__filter--field body__filter--idClient filter_author" id="">
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
        <button type="button" class="body__filter--action__add">Thêm tác giả</button>
            <div>
                <button type="reset" class="body__filter--action__reset">Reset</button>
                <button class="body__filter--action__filter">Lọc</button>
            </div>
        </div>
        
    </form>

    <div id="addAuthorModal" class="modal">
  <div class="addModal-content">
  
    <span class="close">
                <i class="fa-solid fa-xmark"></i>
            </span>
            <div class="form">
                <!-- Code will be render here -->
                <!-- ... -->
            </div>
            <div class="form-actions">
      <button class="addAuthorButton d-none">Thêm tác giả</button>
      <button type="button" id="addButton">Lưu</button>
    </div>
  </div>
</div>
    <div id="sqlresult">
    </div>
    <!-- end -->
    <div class='result'></div>
    <!-- Start: Modal Edit -->
    <div id="editModal" class="modal">
        <div class="editModal-content">
            <span class="close">
                <i class="fa-solid fa-xmark"></i>
            </span>
            <div class="form">
              
            </div>
            <div class="form-actions">
      <button class="editAuthorButton d-none">Chỉnh thông tin tác giả</button>
      <button type="button" id="saveButton" class="btn">Lưu</button>
    </div>
        </div>
    </div>
    <div class='deleteModal' id='deleteModal'> 
    <div class="deleteModal-content">
            <span class="close">
                <i class="fa-solid fa-xmark"></i>
            </span>
            <div class="form">
               
            </div>
            <div class="form-actions">
        <button type="submit"  id="del-confirm" class="btn">Xác nhận</button>
        <button type="button" class="del-cancel">Hủy bỏ</button>
      </div>
        </div> 
    </div>

    <script defer src="../js/admin/author.js?v=<?php echo time(); ?> "></script>

</body>

</html>