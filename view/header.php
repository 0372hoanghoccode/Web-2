<?php
  if (isset($_GET['page']) && $_GET['page']) {
    $page = $_GET['page'];
    if ($page == 'checkout') {
      if (!isset($_SESSION['cart-selected'])) {
        header('Location: index.php');
      }
    }
  } 
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
    rel="stylesheet"
    href="assets/fontawesome-free-6.5.1-web/css/all.min.css?v=<?php echo time(); ?>"
    />
    <link rel="stylesheet" href="css/fonts/fonts.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="css/headerfooter/headerfooter.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="css/headerfooter/header.reponsive.css?v=<?php echo time(); ?>" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script defer src="js/logout.js?v=<?php echo time(); ?>"></script>
    <script defer src="js/header.js?v=<?php echo time(); ?>"></script>
  </head>
  <body>
    <header>
     <div class="headerTop">
          <a href="index.php">
            <img
              src="https://cdn0.fahasa.com/skin/frontend/ma_vanese/fahasa/images/fahasa-logo.png"
              alt=""
            />
          </a>
        </div>
      <div class="header">
        <div class="headerLeft">
          <a href="index.php">
            <img
              src="https://cdn0.fahasa.com/skin/frontend/ma_vanese/fahasa/images/fahasa-logo.png"
              alt=""
            />
          </a>
        </div>
        <div class="headerCenter">
          <div class="search-container">
            <div class="input-wrapper">
              <div class="searchInputContainer">
                <input
                  type="text"
                  placeholder="Tìm kiếm sách ở đây nè..."
                  name="search"
                  id="searchInput"
                />
                <i class="fa-solid fa-circle-xmark hide"></i>
              </div>
              <button type="submit" id="searchButton">
                <i class="fa fa-search"></i>
              </button>
            </div>
            <div class="notification">
              <div class="notification-title hide">
                <h2>Kết quả tìm kiếm: </h2>
              </div>
              <div class="book-section">
                <div class="books">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="headerRight">
          <?php
            $hideCart = ""; 
            if ((!isset($_SESSION['cart']) || !$_SESSION['cart']) && (!isset($_SESSION['username']) || !$_SESSION['username'])) {
              $hideCart = "hide";
            }
          ?>

          <div class="cart <?=$hideCart?>">
            <a href="index.php?page=cart">
              <i class="fa-solid fa-cart-shopping"></i>
              <span>Giỏ hàng</span>
              
              <?php
                $cartQnt = 0;
                $hide = "";
                if (isset($_SESSION['cart']) && $_SESSION['cart']) {
                  $cartQnt = count($_SESSION['cart']);
                } else {
                  $hide = "hide";
                }
              ?>
              <span class="cart-qnt <?=$hide?>"><?=$cartQnt?></span>
            </a>
          </div>
          <div class="account">
            <i class="fa-solid fa-user"></i>
            <span>
            <?php 
              if (isset($_SESSION['username'])) {
                echo $_SESSION['username'];
              } else {
                echo "Tài khoản";
              }
            ?>
            </span>
            <!-- Đây là bẳng đăng kí,đăng nhập -->
            <?php
              if (!isset($_SESSION['username'])) {
                $isHide = "";
                if (isset($_GET['page']) && $_GET['page'] == 'signup') {
                  $isHide = "hide";
                }

                echo '
                <div class="account-options '.$isHide.'">
                  <a href="index.php?page=signup" class="btnDangNhapAccountOption">Đăng nhập</a>
                  <a href="index.php?page=signup&luachon=dangky" class="btnDangKyAccountOption">Đăng ký</a>
                </div>';
              } else {
                echo '
                <div class="account-options">
                  <a href="index.php?page=order" class="btnDonHang">Đơn hàng</a>
                  <button class="btnDangXuat">Đăng xuất</button>
                </div>';
              }
            ?>

            <div
              class="overlay"
              id="overlay"
              onclick="hideNotification()"
            ></div>

            <div class="notification-box" id="notificationBox">
              <span class="close-btn" onclick="hideNotification()">x</span>
              <div class="user-info">
                <div class="input-field">
                  <label for="newName">Họ và tên:</label>
                  <input type="text" id="newName" />
                </div>
                <div class="input-field">
                  <label for="newPhone">Số điện thoại:</label>
                  <input type="text" id="newPhone" />
                </div>
                <div class="input-field">
                  <label for="newAddress">Địa chỉ:</label>
                  <input type="text" id="newAddress" />
                </div>
              </div>
              <button class="save-btn" onclick="saveInfo()">Lưu</button>
            </div>
          </div>
        </div>
        
        <div class="headerRight--smallDevice">
          <i class="fa-solid fa-bars headerRightMenuBar"></i>
          <i class="fa-solid fa-xmark headerRightCloseBar hide"></i>
        </div>

      </div>
      <div class="headerRight--options hide">
      <?php
            $hideCart = ""; 
            if ((!isset($_SESSION['cart']) || !$_SESSION['cart']) && (!isset($_SESSION['username']) || !$_SESSION['username'])) {
              $hideCart = "hide";
            }
          ?>

          <div class="cart <?=$hideCart?>">
            <a href="index.php?page=cart">
              <i class="fa-solid fa-cart-shopping"></i>
              <span>Giỏ hàng</span>
              
              <?php
                $cartQnt = 0;
                $hide = "";
                if (isset($_SESSION['cart']) && $_SESSION['cart']) {
                  $cartQnt = count($_SESSION['cart']);
                } else {
                  $hide = "hide";
                }
              ?>
              <span class="cart-qnt <?=$hide?>"><?=$cartQnt?></span>
            </a>
          </div>
          <div class="account">
            <i class="fa-solid fa-user"></i>
            <span>
            <?php 
              if (isset($_SESSION['username'])) {
                echo $_SESSION['username'];
              } else {
                echo "Tài khoản";
              }
            ?>
            </span>
            <!-- Đây là bẳng đăng kí,đăng nhập -->
            <?php
              if (!isset($_SESSION['username'])) {
                $isHide = "";
                if (isset($_GET['page']) && $_GET['page'] == 'signup') {
                  $isHide = "hide";
                }

                echo '
                <div class="account-options '.$isHide.'">
                  <a href="index.php?page=signup" class="btnDangNhapAccountOption">Đăng nhập</a>
                  <a href="index.php?page=signup&luachon=dangky" class="btnDangKyAccountOption">Đăng ký</a>
                </div>';
              } else {
                echo '
                <div class="account-options">
                  <a href="index.php?page=order" class="btnDonHang">Đơn hàng</a>
                  <button class="btnDangXuat">Đăng xuất</button>
                </div>';
              }
            ?>

            <div
              class="overlay"
              id="overlay"
              onclick="hideNotification()"
            ></div>

            <!-- <div class="notification-box" id="notificationBox">
              <span class="close-btn" onclick="hideNotification()">x</span>
              <div class="user-info">
                <div class="input-field">
                  <label for="newName">Họ và tên:</label>
                  <input type="text" id="newName" />
                </div>
                <div class="input-field">
                  <label for="newPhone">Số điện thoại:</label>
                  <input type="text" id="newPhone" />
                </div>
                <div class="input-field">
                  <label for="newAddress">Địa chỉ:</label>
                  <input type="text" id="newAddress" />
                </div>
              </div>
              <button class="save-btn" onclick="saveInfo()">Lưu</button>
            </div> -->
          </div>
      </div>
    </header>
  </body>
</html>
