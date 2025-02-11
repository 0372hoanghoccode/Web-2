<?php
  if (!isset($_SESSION['username'])) {
    echo "<script>alert('Vui lòng đăng nhập!')</script>";
    echo "<script>window.location.href = 'index.php?page=signup'</script>";
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đơn hàng của bạn</title>
    <link
      rel="stylesheet"
      href="assets/fontawesome-free-6.5.1-web/css/all.min.css"
    />
    <link rel="stylesheet" href="css/fonts/fonts.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="css/order/order.css?v=<?php echo time(); ?>" />
    <script defer src="js/order.js?v=<?php echo time(); ?>"></script>
  </head>
  <body>
    <?php
        if (isset($_SESSION['username']) && $_SESSION['username']) {
          include_once('controller/order.controller.php');
          include_once('controller/product_detail.controller.php');
          include_once('controller/order_detail.controller.php');
          
          $username = $_SESSION['username'];
          $orders = getAllOrdersByUsername($username);
          
          // Nếu có order thì render, không thì báo không
          if (count($orders) > 0) {
            echo '<div class="order-wrapper">';
            echo '<h2>Đơn hàng của bạn</h2>';
            foreach ($orders as $order) {
              $isHideCancelOrder = '';
              if ($order['order_status_id'] == 5 || $order['order_status_id'] == 3 || $order['order_status_id'] == 4) {
                $isHideCancelOrder = 'hide';
              }

              $totalPriceOfOrder = 0;
              $timestamp = strtotime($order['date_create']);
              $day = date('d', $timestamp);
              $month = date('m', $timestamp);
              $year = date('Y', $timestamp);
              
              echo '
                <div class="order">
                  <input type="hidden" class="orderId" value="'.$order['id'].'"/>
                  <div class="order-header">
                    <div class="order-header-content">
                      <div>Ngày tạo: <strong>'.$day.' tháng '.$month.', '.$year.'</strong></div>
                      <div>Trạng thái đơn hàng: <strong>'.$order['order_status'].'</strong></div>
                    </div>
                  </div>
                  <div class="order-title">
                    <div class="order-title-content">
                      <div class="title-img">Hình ảnh</div>
                      <div class="title-info">Thông tin</div>
                      <div class="title-totalprice">Thành tiền</div>
                    </div>
                  </div>';
              
              $orderDetails = getAllOrderDetailByOrderId($order['id']);
              $countOrderDetail = 0; 
              foreach ($orderDetails as $orderDetail) {
                $countOrderDetail++;
                $formatPrice = number_format($orderDetail['price'], 0, ',', '.');
                $totalPrice = $orderDetail['quantity'] * $orderDetail['price'];
                $totalPriceOfOrder += $totalPrice;
                $formatTotalPrice = number_format($totalPrice, 0, ',', '.');
                
                 //Chỉ in ra 3 cái
                if ($countOrderDetail <= 3) {
                  echo '              
                  <div class="order-item-wrapper">
                    <div class="order-item">
                      <div class="img-product-order">
                        <div class="product-image">
                          <img
                            src="'.$orderDetail['image_path'].'"
                            alt="'.$orderDetail['product_name'].'"
                          />
                        </div>
                      </div>
                      <div class="info-product-order">
                        <h2 class="product-name">'.$orderDetail['product_name'].'</h2>
                        <div class="price-original">
                          <div class="order-price">
                            <div class="order-item-price">
                              <div>
                                <span class="price">Đơn giá: '.$formatPrice.' ₫</span>
                              </div>
                            </div>
                            <span class="qty-order">Số lượng: '.$orderDetail['quantity'].'</span>
                          </div>
                        </div>
                      </div>
                      <div class="number-product-order">
                        <div class="order-total-price">
                          <span class="order-price">
                            <span class="price">'.$formatTotalPrice.' ₫</span>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>';
                }
              }
  
              $formatTotalPriceOfOrder = number_format($totalPriceOfOrder, 0, ',', '.');
              $formatTotalPriceAfterDiscount = number_format($order['total_price'], 0, ',', '.');
              if ($order['discount_code'] == null) {
                echo '
                  <div class="order-bottom">
                    <div class="order-bottom-totalprice">
                      <p>Tổng tiền:</p>
                      <strong>'.$formatTotalPriceOfOrder.' đ</strong>
                    </div>
                    <div class="order-bottom-actions">
                      <button class="btnXemChiTiet">Xem chi tiết</button>
                      <button class="btnHuyDon '.$isHideCancelOrder.'">Huỷ đơn</button>
                    </div>
                  </div>
                </div>';
              } else {
                echo '
                  <div class="order-bottom">
                    <div class="order-bottom-totalprice">
                      <p>Tổng tiền:</p>
                      <strong class="beforePrice">'.$formatTotalPriceOfOrder.' đ</strong>
                      <strong>'.$formatTotalPriceAfterDiscount.' đ</strong>
                    </div>
                    <div class="order-bottom-actions">
                      <button class="btnXemChiTiet">Xem chi tiết</button>
                      <button class="btnHuyDon '.$isHideCancelOrder.'">Huỷ đơn</button>
                    </div>
                  </div>
                </div>';
              }
              
            }
            echo '</div>';
          } else {
            echo '
            <div class="order-empty-wrapper">
              <div class="order-empty">
                <div style="text-align: center">
                  <div class="icon">
                    <!-- <img src="assets/image/order/ico_emptyorder.svg" alt="" /> -->
                  </div>
                  <p>Chưa có đơn hàng, hãy tạo lên đơn ngay hôm nay!</p>
                  <button class="button-ordering">
                    <a href="index.php?page=cart">Tạo đơn hàng</a>
                  </button>
                </div>
              </div>
            </div>';
          }
        } else {
          header('location: index.php');
        }
      ?>
    <div class="overlayDetailOrder hide"></div>

    <div class="modal hide">
      
    </div>
  </body>
</html>
