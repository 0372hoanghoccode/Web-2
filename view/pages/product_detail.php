<?php
  include_once('controller/product_detail.controller.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="css/product_detail/product_detail.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="css/product_detail/product_detail.reponsive.css?v=<?php echo time(); ?>" />
    <script defer src="js/product_detail.js?v=<?php echo time(); ?>"></script>
  </head>
  <body>
    <!-- Start: Detail product -->
    <div class="modal">
      <div class="prev_page">
        <a href="index.php">
          <i class="fa-solid fa-left-long"></i>Trở về
        </a>
      </div>
      <div class="modal-container">
        <div class="modal-content">
          <?php
            if (isset($_GET['pid']) && (($_GET['pid']) != '')) {
              $productId = $_GET['pid'];
              $isAllowed = "";

              $productDetail = getProductDetailById($productId, true);

              if (!$productDetail) {
                echo '<div style="font-size: 24px; text-align: center; font-weight: bold;">CHÚNG TÔI ĐÃ CỐ GẮNG HẾT SỨC, NHƯNG KHÔNG THỂ TÌM THẤY CHI TIẾT SẢN PHẨM, VUI LÒNG THỬ LẠI SAU!</div>';
                echo "</div>";
                echo "</div>";
                echo "</div>";
                return;
              }

              $priceFormat = number_format($productDetail['price'], 0, ',', '.').' ₫';
              $quantityStatus;
              if ($productDetail['quantity'] > 0) {
                $quantityStatus = "(Còn ".$productDetail['quantity']." sản phẩm)";
              } else {
                $quantityStatus = "(Hết hàng)";
                $isAllowed = "notAllowed";
              }

              $authorNames = $productDetail['author_names'];
              if ($authorNames == null) {
                $authorNames = 'Đang cập nhật...';
              }

              $categoryNames = $productDetail['category_names'];
              if ($categoryNames == null) {
                $categoryNames = 'Đang cập nhật...';
              }

              $publisherName = $productDetail['publisher_name'];
              if ($publisherName == null) {
                $publisherName = 'Đang cập nhật...';
              }

              echo '
              <div class="modal-content__model-left">
                <img src="'.$productDetail['image_path'].'" alt="'.$productDetail['product_name'].'" />
              </div>
              <div class="modal-content__model-right">
                <h2 class="modal-title">'.$productDetail['product_name'].'</h2>
                <div class="modal-detail">
                  <p class="modal-author">Tác giả: <strong>'.$authorNames.'</strong></p>
                  <p class="modal-category">Thể loại: <strong>'.$categoryNames.'</strong></p>
                </div>
                <h4 class="modal-publisher">Nhà xuất bản: <strong>'.$publisherName.'</strong></h4>
                <span class="modal-price"><p>Giá: </p>'.$priceFormat.'</span>
                <div class="modal-qnt">
                  <div class="modal-qnt-select">
                    <input type="button" value="-" class="modal-qnt__descrease" />
                    <input
                      type="text"
                      value="1"
                      name="amount"
                      class="modal-qnt__value"
                      readonly
                    />
                    <input type="hidden" value="'.$productDetail['quantity'].'" class="modal-qnt__value-max"/>
                    <input type="button" value="+" class="modal-qnt__increase" />
                  </div>
                  <div class="modal-qnt-number">
                    '.$quantityStatus.'
                  </div>
                </div>
                <div class="modal-actions">
                  <button class="modal-btn themVaoGio '.$isAllowed.'">Thêm vào giỏ</button>
                  <button class="modal-btn muaNgay '.$isAllowed.'">Mua ngay</button>
                </div>
              </div>';
            } else {
              echo "CHÚNG TÔI ĐÃ CỐ GẮNG HẾT SỨC, NHƯNG KHÔNG THỂ TÌM THẤY CHI TIẾT SẢN PHẨM, VUI LÒNG THỬ LẠI SAU!";
            }
          ?>
        </div>
      </div>
    </div>
    <!-- End: Detail product -->
  </body>
</html>
