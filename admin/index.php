<?php
session_start();
ob_start();
include_once('../model/connect.php');
include_once('../model/admin/pagnation.model.php');

$_SESSION["render"] = new pagnation(5, 1, "products");

if (!isset($_SESSION['usernameAdmin'])) {
  header("location: loginAdmin.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/fonts/fonts.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../assets/fontawesome-free-6.5.1-web/css/all.min.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../css/admin/index.css?v=<?php echo time(); ?>">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script defer src="../js/admin/index.js?v=<?php echo time(); ?>"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
  <div class="topbar">
    <h1>AdminHub</h1>
    <div class="topbar__admin-info">
      <div class="topbar__admin-info__detail">
        <i class="fa-solid fa-user-shield"></i>
        <h2>
          <?php
            echo $_SESSION['usernameAdmin']
          ?>
        </h2>
      </div>
      <i class="fa-solid fa-caret-down"></i>
      <div class="topbar__admin-info--logout">
        <button class="btnLogoutAdmin">Đăng xuất</button>
      </div>
    </div>
  </div>
  <div class="sidebar">
    <div class="sidebar__logo">
      <img src="../assets/images/logoweb.jpg" alt="Logo">
    </div>
    <ul class="sidebar__items">
    </ul>

  </div>
  <div class="container">
    <!-- Render các page tương ứng -->
    <?php
    if (isset($_GET['page']) && $_GET['page'] != '') {
      $page = $_GET['page'];

      switch ($page) {
        case 'home':
          require_once('public/home.php');
          break;
        case 'product':
          require_once('public/product.php');
          break;
        case 'order':
          require_once('public/order.php');
          break;
        case 'account':
          require_once('public/account.php');
          break;
        case 'publisher':
          require_once('public/publisher.php');
          break;
        case 'author':
          require_once('public/author.php');
          break;
        case 'category':
          require_once('public/category.php');
          break;
        case 'supplier':
          require_once('public/supplier.php');
          break;
        case 'role':
          require_once('public/role.php');
          break;
        case 'selfDestruct':
          require_once('public/selfDestruct.php');
          break;
          case 'discount':
            require_once('public/discount.php');
            break;
            case 'receipt':
              require_once('public/receipt.php');
              break;
          // case 'account':
          // require_once('public/account.php');
          // break;
        default:
          // Xử lý trường hợp không khớp với bất kỳ trang nào
          require_once('notFound.php');
          break;
      }
    } else {
      // Trang chủ
      require_once('public/home.php');
    }
    ?>
  </div>
</body>
</html>
