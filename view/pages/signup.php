<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Sign Up</title>
    <link rel="stylesheet" href="css/signup/signup.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="backGround">
        <div class="container">
            <div class="button-container">
                <button onclick="showForm('loginForm', this)" class="button btnTabDangNhap active">Đăng nhập</button>
                <button onclick="showForm('signupForm', this)" class="button btnTabDangKy">Đăng ký</button>
            </div>

            <!-- Login form -->
            <div id="loginForm" class="form-container active">
                <form action="" method="POST">
                    <div class="form-row">
                        <label for="username">Username</label>
                        <input type="text" placeholder="Nhập tên tài khoản" name="username" id="loginUsername" required>
                        <p class="errMessage errMessageUsername"></p>
                    </div>
                    <div class="form-row">
                        <label for="psw">Mật khẩu</label>
                        <div class="loginPasswordContainer">
                            <input type="password" placeholder="Nhập Mật khẩu" name="password" id="loginPassword" required>
                            <button class="loginPasswordView">
                                <i class="fa-solid fa-eye-slash noView-loginPassword"></i>
                                <i class="fa-solid fa-eye view-loginPassword hide"></i>
                            </button>
                        </div>
                        <p class="errMessage errMessagePassword"></p>
                    </div>
                    <input class="btnSubmit btnDangNhap" type="submit" value="Đăng nhập" />
                    <p class="resetPassword"><a class="fakelink" href="?page=resetPassword">Quên mật khẩu?</a></p>
                </form>
                <div class="result"></div>
            </div>

            <!-- Sign-up form -->
            <div id="signupForm" class="form-container">
                <form action="" method="POST">
                    <div class="form-row form-row-48">
                        <label for="username">Username</label>
                        <input type="text" placeholder="Nhập tên tài khoản" id="registerUsername" name="username" required>
                        <p class="errMessage errMessageUsernameRegister"></p>
                    </div>
                    <div class="form-row form-row-48">
                        <label for="email">Email</label>
                        <input type="text" placeholder="Nhập Email" id="registerEmail" name="email" required>
                        <p class="errMessage errMessageEmailRegister"></p>
                    </div>
                    <div class="form-row">
                        <label for="name">Họ và tên</label>
                        <input type="text" placeholder="Nhập Họ và tên" id="registerFullname" name="name" required>
                        <p class="errMessage errMessageFullnameRegister"></p>
                    </div>
                    <div class="form-row">
                        <label for="number">Số điện thoại (VD: 0931814480)</label>
                        <input type="text" placeholder="Nhập Số điện thoại" id="registerPhoneNumber" name="number" required>
                        <p class="errMessage errMessagePhoneNumberRegister"></p>
                    </div>
                    <div class="form-row">
                        <label for="tinhthanhpho">Tỉnh/thành phố</label>
                        <select id="tinhthanhpho"></select>
                        <p class="errMessage errMessageCityRegister"></p>
                    </div>
                    <div class="form-row form-row-48">
                        <label for="quanhuyen">Quận/huyện</label>
                        <select id="quanhuyen"></select>
                        <p class="errMessage errMessageDistrictRegister"></p>
                    </div>
                    <div class="form-row form-row-48">
                        <label for="phuongxa">Phường/xã</label>
                        <select id="phuongxa"></select>
                        <p class="errMessage errMessageWardRegister"></p>
                    </div>
                    <div class="form-row">
                        <label for="address">Số nhà và tên đường (VD: 273 An Dương Vương)</label>
                        <input type="text" placeholder="Nhập số nhà và tên đường" id="registerAddress" name="address" required>
                        <p class="errMessage errMessageAddressRegister"></p>
                    </div>
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
                    <input class="btnSubmit btnDangKy" type="submit" value="Đăng ký" />
                </form>
            </div>
        </div>
    </div>
    <div class="reload hidden">
        <img src="assets/images/reload.gif" alt="">
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script defer src="js/signup.js?v=<?php echo time(); ?>"></script>
</body>
</html>
