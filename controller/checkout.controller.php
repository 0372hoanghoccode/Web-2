<?php
  session_start();
  include_once("../model/product.model.php");
  include_once("../model/product_detail.model.php");
  include_once("../model/order.model.php");
  include_once("../model/order_detail.model.php");

  if (isset($_POST['isCheckout']) && $_POST['isCheckout']) {
    $cartSelected = $_SESSION['cart-selected'];

    $isEnoughAll = true;
    $listProductsNotEnoughQuantity = [];
    foreach ($cartSelected as $product) {
      if (!checkProductEnoughQuantity($product['productId'], $product['amount'])) {
        $isEnoughAll = false;
        $listProductsNotEnoughQuantity[] = $product['productId'];
      }
    }

    // Nếu như không đủ 
    if (!$isEnoughAll) {
      $listDetailProductsNotEnough = [];
      foreach ($listProductsNotEnoughQuantity as $productId) {
        $productDetail = getProductDetailByIdModel($productId);
        if ($productDetail) {
          $productDetail = $productDetail->fetch_assoc();
          $listDetailProductsNotEnough[] = $productDetail;
        }
      }
      
      unset($_SESSION['cart-selected']);
      $response = [
        'successEnoughAll' => false, 
        'products' => $listDetailProductsNotEnough,
        'message' => "Hệ thống không cung cấp đủ số lượng sản phẩm, vui lòng chọn lại!"
      ];
      echo json_encode($response);
    } else {
      // Nếu như đã đủ thì thêm dữ liệu vào db bảng `orders`
      $deliveryInfoId = $_POST['deliveryInfoId'];
      $customerId = $_SESSION['username'];
      $totalPrice = $_POST['totalPrice'];
      $discountCode = "'".$_POST['discountCode']."'";
      
      if ($discountCode == "''") {
        $discountCode = 'null';
      }

      if ($orderId = addNewOrder($deliveryInfoId, $totalPrice, $discountCode)) {
        foreach ($cartSelected as $product) {
          $productDetail = getProductDetailByIdModel($product['productId']);
          if ($productDetail) {
            $productDetail = $productDetail->fetch_assoc();
            addNewOrderDetail($orderId, $product['productId'], $product['amount'], $productDetail['price']);
            updateQuantityProductByIdModel($product['productId'], -$product['amount']);
          }
        }

        // Xoá những `id` có trong cart vì đã xác nhận thanh toán
        foreach ($_SESSION['cart-selected'] as $item) {
          $productId = $item['productId'];

          $_SESSION['cart'] = array_filter($_SESSION['cart'], function($cartItem) use ($productId) {
            return $cartItem['id'] != $productId;
          });
        }
        unset($_SESSION['cart-selected']);

        $response = [
          'successAddNewOrder' => true,
          'message' => "Hệ thống đã thêm đơn hàng thành công!"
        ];
        echo json_encode($response);
      } else {
        $response = [
          'successAddNewOrder' => false, 
          'message' => "Hệ thống bị lỗi khi thêm đơn hàng, vui lòng thử lại sau!"."INSERT INTO orders (customer_id, staff_id, delivery_info_id, date_create, total_price, status_id, discount_code)
          VALUES ('$customerId', null, $deliveryInfoId, NOW(), $totalPrice, 1, '$discountCode);"
        ];
        echo json_encode($response);
      }
    }
  }
?>