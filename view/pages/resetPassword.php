<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/signup/signup.css?v=<?php echo time(); ?>">
    <!-- <script defer src="js/home.js?v=<?php echo time(); ?>"></script> -->
</head>

<body>
    <div class="backGround">
        <div class="container">
            <div id="emailInput" class="form-container active">
                <h1 style="text-align: center;">Nhập Email của bạn</h1>
                <form action="" method="POST">
                    <div class="form-row">
                        <label for="email">Email</label>
                        <input type="text" placeholder="Nhập email" name="email" id="email" required>
                        <p class="errMessage errMessageEmail"></p>
                    </div>
                    <input class="btnSubmit btnEmail" type="submit" value="Xác nhận" />
                </form>
                <div class="result"></div>
            </div>
            <div id="verifyCodeInput" class="form-container">
                <form action="" method="POST">
                    <div class="form-row">
                        <h1 style="margin-bottom:15px;">Nhập mã xác nhận</h1>
                        <h4 class="verify_code_msg"></h4>
                        <input style="text-align: center;" type="text" id="verify_code" maxlength="6">
                    </div>
                    <input class="btnSubmit btnSendCode" type="submit" value="Xác nhận" />
                    <p class="new_verify_code fakelink">Không nhận được mã xác nhận?</p>
                </form>
                <div class="result"></div>

            </div>
            <div id="passwordInput" class="form-container">
                <h1 style="text-align: center;">Đặt lại mật khẩu</h1>
                <form action="" method="POST">
                    <div class="form-row form-row-48">
                        <label for="psw">Mật khẩu</label>
                        <div class="registerPasswordContainer">
                            <input type="password" placeholder="Nhập Mật khẩu" name="password" id="registerPassword" required>
                            <button class="registerPasswordView">
                                <i class="fa-solid fa-eye-slash noView-registerPassword"></i>
                                <i class="fa-solid fa-eye view-registerPassword hide"></i>
                            </button>
                        </div>
                        <p class="errMessage errMessagePasswordRegister"></p>
                    </div>
                    <div class="form-row form-row-48">
                        <label for="psw-repeat">Lặp lại Mật khẩu</label>
                        <div class="registerConfirmPasswordContainer">
                            <input type="password" placeholder="Nhập lại mật khẩu" name="psw-repeat" id="registerConfirmPassword" required>
                            <button class="registerConfirmPasswordView">
                                <i class="fa-solid fa-eye-slash noView-registerConfirmPassword"></i>
                                <i class="fa-solid fa-eye view-registerConfirmPassword hide"></i>
                            </button>
                        </div>
                        <p class="errMessage errMessageConfirmPasswordRegister"></p>
                    </div>
                    <input class="btnSubmit btnPassword" type="submit" value="Xác nhận" />
                </form>
                <div class="result"></div>
            </div>
        </div>

    </div>
    <div class="verify_code_background hidden">
        <div class="verify_code_confirm_container">
            <h1 class="title">Nhập mã xác nhận</h1>
            <h4 class="verify_code_msg"></h4>
            <input type="text" id="verify_code" maxlength="6">
            <button type="button" class="btnSubmit btnSendCode">Xác Nhận</button>
            <p class="new_verify_code fakelink">Không nhận được mã xác nhận?</p>
        </div>
    </div>
    <div class="reload hidden">
        <img src="assets\images\reload.gif" alt="">
    </div>
    <!-- <div class="toast-overlay"></div>
    <div class="toast">
        <div class="toast-header">
            <i class="fa-solid fa-circle-check success-icon"></i>
            <p class="toast-header__message">Thông báo</p>
        </div>
        <div class="toast-content">
            <p class="toast-message">Bạn đã đăng ký thành công!</p>
        </div>
        <a href="index.php?page=signup" class="toast-next">Tiếp tục</a>
    </div> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script defer src="js/resetPassword.js?v=<?php echo time(); ?>"></script>
</body>

</html>