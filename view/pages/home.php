<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="css/fonts/fonts.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/pageHome/pageHome.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/product/product.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/pageHome/pageHome.reponsive.css?v=<?php echo time(); ?>">
    <script defer src="js/product.js?v=<?php echo time(); ?>"></script>
</head>

<body>
    <div class="container" style="margin: 40px auto 0;">
        <div class="silder-01">
            <div class="left-sider">
                <div class="thumb-img" href="#"><img src="assets/images/thumbnail/TanViet_Silver_0424_Ver1_Slide_840x320_1.jpg" alt=""></div>
            </div>
            <div class="right-slider">
                <div class="thumb-img" href="#"><img src="assets/images/thumbnail/392x156_zalopay_t4.jpg" alt=""></div>
                <div class="thumb-img" href="#"><img src="assets/images/thumbnail/392x156_vnpay_t4.jpg" alt=""></div>
            </div>
        </div>

        <div class="silder-02">
            <div class="thumb-img" href="#"><img src="assets/images/thumbnail/Week2_Coupon_T424_SmallBanner_310x210.png" alt=""></div>
            <div class="thumb-img" href="#"><img src="assets/images/thumbnail/BannerNgoaiVanT0424_Smallbanner_310x210.jpg" alt=""></div>
            <div class="thumb-img" href="#"><img src="assets/images/thumbnail/MayTinh_SmallBanner_T4_310x210.png" alt=""></div>
            <div class="thumb-img" href="#"><img src="assets/images/thumbnail/MinhLongT04_SmallBanner_310x210.png" alt=""></div>
        </div>

        <!-- NỘI DUNG BÁN HÀNG -->
        <?php
            include_once('controller/category.controller.php');
            include_once('controller/product.controller.php');
            // echo $_SESSION['username'];
            $category_id = 1;
            
            $categories = getCategoryList();
            $category = $categories[1];
                echo '
                <div class="genre">
                    <div class="genre-name">SÁCH MỚI RA MẮT</div>
                <div class="product-list">';

                $products = getNewProducts(1);
                $index = 0;
              foreach ($products as $product) {
    if ($index == 4) break;
    $index++;
    $price_formatted = number_format($product['price'], '0', ',', '.') . 'đ';
    //$notAllowed = "";

    // Bỏ kiểm tra số lượng để luôn cho phép thêm vào giỏ hàng
    // if($product["quantity"] <= -55) $notAllowed = "notAllowed";

    echo '
    <div class="product-item--wrapper">
      <div class="product-item">
        <div class="product-img">
          <div class="product-action">
            <div class="product-action--wrapper">
              <a href="index.php?page=product_detail&pid=' . $product['id'] . '" class="product-action--btn product-action__detail">Chi tiết</a>
              <input type="hidden" class="productId" value="' . $product['id'] . '"/>
              <button class="product-action--btn product-action__addToCart">Thêm vào giỏ</button>
            </div>
          </div>
          <div class="img-resize">
            <img src="' . $product['image_path'] . '" alt="' . $product['name'] . '" />
          </div>
        </div>
        <a href="index.php?page=product_detail&pid=' . $product['id'] . '" >
          <div class="product-detail">
            <p class="product-title">' . $product['name'] . '</p>
            <p class="product-price">' . $price_formatted . '</p>
          </div>
        </a>
      </div>
    </div>';
}

                echo '
                    </div>
                    <div class="see-more" >
                        <a href="index.php" class="see-more-btn">Xem thêm </a>
                    </div>
                </div>';
        ?>
        <!-- END -->
    </div>
</body>
</html> -->