<?php
session_start();
include_once("../model/product_detail.model.php");

if (isset($_POST["product-action__addToCart"]) && $_POST['product-action__addToCart']) {
  if (!isset($_SESSION['username'])) {
    $result = array(
      'success' => false,
      'message' => "Vui lòng đăng nhập trước khi chọn thêm sản phẩm!",
    );
    echo json_encode($result);
    return;
  }

  $productId = $_POST['productId'];
  $amount = 1;

  if (isset($_POST['amount']) && $_POST['amount']) {
    $amount = $_POST['amount'];
  }

  // Khởi tạo session cart
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Kiểm tra sản phẩm đã tồn tại trong cart chưa
  $isExistProduct = false;
  // $isAmountExceed = true;

  // $product_quantity = getProductDetailByIdModel($productId)->fetch_assoc();
  // $product_quantity = $product_quantity['quantity'];

  foreach ($_SESSION['cart'] as $key => $product) {
    if ($product['id'] == $productId) {
      // if ($_SESSION['cart'][$key]['amount'] + $amount <= $product_quantity) {
      //   $_SESSION['cart'][$key]['amount'] += $amount;
      //   $isAmountExceed = false;
      // }
      $_SESSION['cart'][$key]['amount'] += $amount; // luôn cho phép cộng thêm
      $isExistProduct = true;
      break;
    }
  }

  // Nếu chưa tồn tại thì add vào cart
  if (!$isExistProduct) {
    // if($amount <= $product_quantity){
      $product = [
        'id' => $productId,
        'amount' => $amount
      ];
      $_SESSION['cart'][] = $product;
      // $isAmountExceed = false;
    // }
  }

  // Đếm số lượng sản phẩm trong giỏ hàng
  $result = array(
    'success' => true,
    'message' => "Đã thêm sản phẩm thành công!",
    'quantity' => count($_SESSION['cart'])
  );

  // if ($isAmountExceed){
  //   $result['success'] = false;
  //   $result['message'] = "Thêm sản phẩm thất bại vì vượt quá số lượng tồn kho!";
  // }

  echo json_encode($result);
}

// Xoá sản phẩm trong giỏ hàng
if (isset($_POST['product-action__removeFromCart']) && $_POST['product-action__removeFromCart']) {
  $productIdToRemove = $_POST['product_id'];

  foreach ($_SESSION['cart'] as $key => $product) {
    if ($productIdToRemove == $product['id']) {
      unset($_SESSION['cart'][$key]);
    }
  }

  header("location: ../index.php?page=cart");
}

// Xử lý lưu các sản phẩm cần thanh toán
if (isset($_POST['abate']) && $_POST['abate']) {
  if (!isset($_SESSION['cart-selected'])) {
    $_SESSION['cart-selected'] = [];
  }
  else {
    unset($_SESSION['cart-selected']);
  }

  foreach ($_POST['selectedProducts'] as $product) {
    $_SESSION['cart-selected'][] = $product;
  }
}

// Xử lý cập nhật số lượng sản phẩm
if (isset($_POST["product-action__updateAmount"]) && $_POST['product-action__updateAmount']) {
  $productId = $_POST['productId'];
  $amount = $_POST['amount'];

  foreach ($_SESSION['cart'] as $key => $product) {
    if ($product['id'] == $productId) {
      $_SESSION['cart'][$key]['amount'] = $amount;
      break;
    }
  }
}
