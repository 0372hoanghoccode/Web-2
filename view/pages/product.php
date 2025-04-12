<?php
include_once("controller/category.controller.php");
include_once("controller/product.controller.php");
$items_per_page = 8;
$current_page = 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sản phẩm</title>
  <link rel="stylesheet" href="assets/fontawesome-free-6.5.1-web/css/all.min.css" />
  <link rel="stylesheet" href="css/fonts/fonts.css?v=<?php echo time(); ?>" />
  <link rel="stylesheet" href="css/product/product.css?v=<?php echo time(); ?>" />
  <link rel="stylesheet" href="css/product/product.reponsive.css?v=<?php echo time(); ?>" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script defer src="js/product.js?v=<?php echo time(); ?>"></script>
</head>

<body>
  <div class="container">
    <!-- Start: Banner section -->
    <div class="banner-section">
      <img src="assets/images/thumbnail/banner-col.webp" alt="banner-col" />
    </div>
    <!-- End: Banner section -->

    <!-- Start: Collection section -->
    <div class="collection-section">
      <!-- Start: Sidebar items -->
      <div class="sidebar-items">
        <div class="sidebar-item">
          <div class="sidebar-item__title">
            <h2>Thể loại</h2>
            <a href="index.php" class="reset_theloai"><i class="fa-solid fa-rotate-right"></i> Reset</a>
          </div>
          <ul class="sidebar-item__list">
            <?php
            $categories = getCategoryList();
            foreach ($categories as $category) {
              echo '
                <li>
                  <input type="checkbox" id="theloai_' . $category['id'] . '" data="' . $category['id'] . '" name="theloai"/><label for="theloai_' . $category['id'] . '">' . $category['name'] . '</label>
                </li>';
            }
            ?>
          </ul>
        </div>
        <div class="sidebar-item">
          <div class="sidebar-item__title">
            <h2>Giá bán</h2>
            <!-- <button class="reset_giaban"><i class="fa-solid fa-rotate-right"></i> Reset</button> -->
          </div>
          <ul class="sidebar-item__list">
            <li>
              <input type="checkbox" name="giaban" id="giaban_duoi50" data="duoi50" /><label for="giaban_duoi50">Dưới 50,000đ</label>
            </li>
            <li>
              <input type="checkbox" name="giaban" id="giaban_tu50duoi100" data="tu50duoi100" /><label for="giaban_tu50duoi100">50,000đ - 100,000đ</label>
            </li>
            <li>
              <input type="checkbox" name="giaban" id="giaban_tu100duoi200" data="tu100duoi200" /><label for="giaban_tu100duoi200">100,000đ - 200,000đ</label>
            </li>
            <li>
              <input type="checkbox" name="giaban" id="giaban_tren200" data="tren200" /><label for="giaban_tren200">Trên 200,000đ</label>
            </li>
          </ul>
        </div>
      </div>
      <!-- End: Sidebar items -->

      <!-- Start: Main collection -->
      <div class="main-collection">
        <div class="result"></div>
      </div>
      <!-- End: Main collection -->
    </div>
    <!-- End: Collection section -->
  </div>
</body>

</html>
