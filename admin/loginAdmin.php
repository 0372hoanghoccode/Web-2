<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login admin</title>
    <link rel="stylesheet" href="../css/fonts/fonts.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../assets/fontawesome-free-6.5.1-web/css/all.min.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../css/admin/adminLogin.css?v=<?php echo time(); ?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script defer src="../js/admin/adminLogin.js?v=<?php echo time(); ?>"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
    <div class="backGround">
        <div class="container">
            <div class="adminLoginTitle">
                Đăng nhập vào AdminHub
            </div>

            <!-- Login form -->
            <div id="loginForm">
                <!-- <h2>Form Đăng nhập</h2> -->
                <!-- Your login form goes here -->
                    <div class="form-row">
                        <label for="username">Username</label>
                        <input type="text" class="username" placeholder="Nhập username">
                    </div>
                    <div class="form-row">
                        <label for="psw">Mật khẩu</label>
                        <div class="passwordContainer">
                            <input type="password" placeholder="Nhập Mật khẩu" class="password" required>
                            <button class="loginPasswordView">
                                <i class="fa-solid fa-eye-slash noView-loginPassword"></i>
                                <i class="fa-solid fa-eye view-loginPassword hide"></i>
                            </button>
                        </div>
                    </div>
                   <button class="btnSubmit btnDangNhap" >Đăng nhập</button>
            </div>
        </div>
      </div>
</body>
</html>