<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// Import connectDB
include_once('model/connect.php');
// Header
require_once("view/header.php");
// Content
if (isset($_GET['page']) && $_GET['page'] != '') {
  $page = $_GET['page'];

  switch ($page) {
    // Các trang khác
    case 'cart':
      require_once('view/pages/cart.php');
      break;
    case 'checkout':
      require_once('view/pages/checkout.php');
      break;
    case 'signup':
      require_once('view/pages/signup.php');
      break;
    case 'product_detail':
      require_once('view/pages/product_detail.php');
      break;
    case 'order':
      require_once('view/pages/order.php');
      break;
    case 'resetPassword':
      require_once('view/pages/resetPassword.php');
      break;
    default:
      // Xử lý trường hợp không khớp với bất kỳ trang nào
      require_once('view/pages/notFound.php');
      break;
  }
} else {
  // Tải trang sản phẩm làm trang chủ
  if (isset($_GET['search']) && !empty($_GET['search'])) {
    $_SESSION['search_keyword'] = $_GET['search']; // Store search keyword in session
  }
  require_once('view/pages/product.php');
}

// Footer
require_once("view/footer.php");
