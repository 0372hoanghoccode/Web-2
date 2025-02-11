<?php
  $autoReload = false;
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
  <title>Giỏ hàng</title>
  <link rel="stylesheet" href="assets/fontawesome-free-6.5.1-web/css/all.min.css" />
  <link rel="stylesheet" href="css/fonts/fonts.css?v=<?php echo time(); ?>" />
  <link rel="stylesheet" href="css/cart/cart.css?v=<?php echo time(); ?>" />
  <link rel="stylesheet" href="css/cart/cart_responsive.css?v=<?php echo time(); ?>" />
  <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script defer src="js/cart.js?v=<?php echo time(); ?>"></script>
</head>

<body>
  <?php
    $show = "";
    $cartQnt = 0;

    if (isset($_SESSION['cart']) && ($_SESSION['cart'])) {
      $show = "has-items";
      $cartQnt = count($_SESSION['cart']);
    } else {
      $show = "no-items";  
    }
  ?>

  <div class="container">
    <div class="page-title <?=$show?>">
      <div class="page-title-content">
        <h1>Giỏ Hàng</h1>
        <span class="cart-title-numbers">(<?=$cartQnt?> sản phẩm)</span>
      </div>
    </div>
    

    <div class="cart-content <?php echo $show?>">
      <div class="cart-empty-wrapper">
        <div class="cart-empty">
          <div style="text-align: center">
            <div class="icon">
              <!-- <img src="assets/image/cart/ico_emptycart.svg" alt="" /> -->
            </div>
            <p>Chưa có sản phẩm trong giỏ hàng của bạn</p>
            <button class="button-shopping" title="Mua sắm ngay">
              <a href="index.php?page=product">Mua sắm ngay</a>
            </button>
          </div>
        </div>
      </div>

      <!-- <div class="cart-hasItem-wrapper"></div> -->
      <div class="cart-items-wrapper">
        <div>
          <div class="header-cart-item">
            <div class="checkbox-all-product">
              <input type="checkbox" id="checkbox-all-product"  />
            </div>
            <div>
              <span>Chọn tất cả
                ( 
                <span class="num-items-checkbox">0</span>
                sản phẩm)
              </span>
            </div>
            <div>Số lượng</div>
            <div>Thành tiền</div>
            <div></div>
          </div>
          <div class="cart-item-wrapper">
            <?php
              if (isset($_SESSION['cart']) && ($_SESSION['cart'])) {
                include_once('controller/product_detail.controller.php');

                $totalProducts = count($_SESSION['cart']);
                $counter = 0;

                foreach($_SESSION['cart'] as $product) {
                  $counter++;
                  $closeDatabase = false;

                  if ($counter == $totalProducts) {
                    $closeDatabase = true;
                  }         
                  
                  $productDetail = getProductDetailById($product['id'], $closeDatabase);
                  
                  // Nếu sản phẩm có status = 0 thì không render và xoá khỏi cart
                  if ($productDetail['status'] == 0) {
                  
                    foreach ($_SESSION['cart'] as $key => $cartProduct) {
                      if ($product['id'] == $cartProduct['id']) {
                        unset($_SESSION['cart'][$key]);
                      }
                    }

                    $autoReload = true;
                    continue;
                  }
                  
                  $formatPrice = number_format($productDetail['price'], 0, ',', '.').' ₫';

                  $totalPrice = $productDetail['price'] * $product['amount'];
                  $formatTotalPrice =  number_format($totalPrice, 0, ',', '.').' ₫';

                  $hidden = "hidden";
                  if($productDetail['quantity'] <= 0){
                    $hidden = "";
                  }
                  echo '
                  <div class="not-selectable-container">
                  <div class="cart-item">
                    <div class="checked-product-cart">
                      <input type="checkbox" class="checkbox-add-cart" value="'.$product['id'].'" />
                    </div>
                    <div class="img-product-cart">
                      <div class="product-image">
                        <img src="'.$productDetail['image_path'].'" alt="'.$productDetail['product_name'].'" />
                      </div>
                    </div>
                    <div class="group-product-info">
                      <div class="info-product-cart">
                        <div>
                          <h2 class="product-name">
                            <a href="index.php?page=product_detail&pid='.$product['id'].'">'.$productDetail['product_name'].'</a>
                          </h2>
                        </div>
                        <div class="price-original">
                          <div class="cart-price">
                            <div class="cart-item-price">
                              <div>
                                <span class="price">'.$formatPrice.'</span>
                                <input type="hidden" class="price-hidden" value="'.$productDetail['price'].'"/>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div>
                        <div>(Còn '.$productDetail['quantity'].' sản phẩm)</div>
                      </div>
                      </div>
                      <div class="number-product-cart">
                        <div class="product-view-quantity-box">
                          <div class="product-view-quantity-box-block">
                            <a href="#!" class="btn-substract-qty">
                              -
                            </a>
                            <input type="text" class="qty-cart" value="'.$product['amount'].'" readonly />
                            <a href="#!" class="btn-add-qty" data-max-quantity="'.$productDetail['quantity'].'">
                              +
                            </a>
                          </div>
                        </div>
                        <div class="cart-total-price">
                          <span class="cart-price">
                            <span class="price">'.$formatTotalPrice.'</span>
                          </span>
                        </div>
                      </div>
                    </div>
                    <form action="controller/cart.controller.php" method="post" class="btn-remove-cart">
                      <input type="hidden" name="product_id" value="'.$product['id'].'">
                      <input type="hidden" name="product-action__removeFromCart" value="Xoá"></input>
                      <button class="btn-remove-desktop-cart">
                        <i class="fa-regular fa-trash-can"></i>
                      </button>
                    </form>
                  <div class="not-selectable '.$hidden.'">Hết hàng</div>
                  </div>
                  </div>';
                }

                if ($autoReload) {
                  echo '<script>window.location.reload();</script>';
                  $autoReload = false;
                }
              }
            ?>
          </div>
        </div>
      </div>

      <div class="cart-summary">
        <h2 style="
              font-size: 17px;
              border-bottom: 1px solid #ccc;
              text-align: left;
              font-weight: 300;
            ">
          Thành Tiền
        </h2>
        <div class="cart-total">
          <p>Tổng cộng: 0 ₫</p>
        </div>
        <button class="checkout-button">Thanh toán</button>
      </div>
    </div>

    <div class="hidden-desktop">
      <div class="total-button-mobile">
        <div class="total-mobile"></div>
        <div class="button-mobile"></div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function () {
      $('.checkout-button').click(function (e) { 
        e.preventDefault();

        const selectedProducts = [];

        $('.checkbox-add-cart').each(function () {
          if (this.checked) {
            const parentEle = $(this).closest('.cart-item')[0];
            const productId = $(this).attr('value');
            const amount = parentEle.querySelector('.qty-cart').value;
            selectedProducts.push({productId, amount});
          }
        })

        if (selectedProducts.length == 0) {
          alert("Vui lòng chọn sản phẩm cần thanh toán!");
          return;
        }

        $.ajax({
          type: "post",
          url: "controller/cart.controller.php",
          dataType: "html",
          data: {
            abate: true,
            selectedProducts
            
          }
        }).done(function(result) {
          window.location.href = "index.php?page=checkout";
        })
      });
    });
  </script>
</body>
</html>