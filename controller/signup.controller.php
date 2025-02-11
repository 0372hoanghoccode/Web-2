<?php
// Include model
include_once('../model/signup.model.php');
session_start();
// Kiểm tra nếu có dữ liệu được gửi từ Ajax (LOGIN)
if (isset($_POST['usernameLogin']) && isset($_POST['passwordLogin'])) {
  $user = $_POST['usernameLogin'];
  $pass = $_POST['passwordLogin'];

  // Kiểm tra thông tin đăng nhập
  $loginResult = checkLogin($user, $pass);
  if ($loginResult->success && $loginResult->status == 1) {
    $_SESSION['username'] = $user;
  }
  echo json_encode($loginResult);
}

// Kiểm tra nếu có dữ liệu gửi từ Ajax (REGISTER)
if (isset($_POST['function'])) {
  $function = $_POST['function'];
  switch ($function) {
    case 'sendNewCode':
      sendNewCode();
      break;
    case 'checkCode':
      checkCode();
      break;
    case 'checkAccountExist':
      checkAccountExist();
      break;
    case 'registerAccount':
      registerAccount();
      break;
    case 'checkEmailExist':
      checkEmailExist();
      break;
    case 'resetPassword':
      resetPassword();
      break;
  }
}
function checkAccountExist()
{
  if (
    isset($_POST['usernameRegister']) &&
    isset($_POST['emailRegister'])

  ) {
    $username = $_POST['usernameRegister'];
    $email = $_POST['emailRegister'];

    $registerResult = checkRegister($username, $email);
    echo json_encode($registerResult);
  }
}
function checkCode()
{
  if (
    isset($_POST['verify_code']) &&
    isset($_POST['verify_time']) &&
    isset($_POST['emailRegister'])
  ) {
    $code = $_POST['verify_code'];
    $time = $_POST['verify_time'];
    $email = $_POST['emailRegister'];

    $registerResult = checkVerifyCode($email, $code, $time);
    // $registerResult = registerNewAccount($username, $email,$fullname, $phoneNumber, $address, $password, $city, $district, $ward);
    echo json_encode($registerResult);
  }
}
function sendNewCode()
{
  if (
    isset($_POST['verify_time']) &&
    isset($_POST['email'])
  ) {
    $time = $_POST['verify_time'];
    $email = $_POST['email'];
    sendNewVerifyCode($email, $time);
  }
}
function registerAccount()
{
  if (
    isset($_POST['emailRegister']) &&
    isset($_POST['usernameRegister']) &&
    isset($_POST['fullnameRegister']) &&
    isset($_POST['phoneNumberRegister']) &&
    isset($_POST['addressRegister']) &&
    isset($_POST['passwordRegister']) &&
    isset($_POST['confirmPasswordRegister'])
  ) {
    $email = $_POST['emailRegister'];
    $username = $_POST['usernameRegister'];
    $fullname = $_POST['fullnameRegister'];
    $phoneNumber = $_POST['phoneNumberRegister'];
    $address = $_POST['addressRegister'];
    $password = $_POST['passwordRegister'];
    $confirmPassword = $_POST['confirmPasswordRegister'];
    $city = $_POST['cityRegister'];
    $district = $_POST['districtRegister'];
    $ward = $_POST['wardRegister'];

    // $registerResult = checkVerifyCode($email,$code,$time);
    registerNewAccount($username, $email, $fullname, $phoneNumber, $address, $password, $city, $district, $ward);
    // echo json_encode($registerResult);

  }
}
function resetPassword()
{
  if (
    isset($_POST['email']) &&
    isset($_POST['password']) &&
    isset($_POST['confirmPassword'])
  ) {
    $email = $_POST['email'];
    $password = $_POST['password'];


    // $registerResult = checkVerifyCode($email,$code,$time);
    setPassword($email, $password);
    // echo json_encode($registerResult);

  }
}

function checkEmailExist(){
  if(isset($_POST['email'])){
    $registerResult = checkEmail($_POST['email']);
    echo json_encode($registerResult);
  }
}