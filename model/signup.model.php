<?php
require "../vendor/Mailer.php";
include_once('connect.php');
$database = new connectDB();


function checkLogin($username, $password)
{
  global $database;
  $sql = "SELECT *
              FROM accounts
              WHERE username = '$username'";
  $result = $database->query($sql);

  // Kiểm tra xem có tồn tại không?
  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // Chặn không cho tài khoản admin, nhân viên đăng nhập mua hàng
    $role_id = $row['role_id'];
    if ($role_id != '3') {
      $reponse = (object) array(
        "success" => false,
        "message" => "Tài khoản không đủ quyền truy cập!"
      );
      return $reponse;
    }

    $db_password = $row['password'];

    // So sánh mật khẩu nhập vào với mật khẩu trong cơ sở dữ liệu
    if (password_verify($password, $db_password)) {
      $database->close();
      if ($row['status'] == 0) {
        $reponse = (object) array(
          "success" => false,
          "status" => $row["status"],
          "message" => "Tài khoản của bạn đã bị khoá!"
        );
        return $reponse;
      } else {
        $reponse = (object) array(
          "success" => true,
          "status" => $row["status"],
          "message" => "Đăng nhập thành công!"
        );
        return $reponse;
      }
    } else {
      $database->close();
      $reponse = (object) array(
        "success" => false,
        "message" => "Tài khoản hoặc mật khẩu không chính xác!"
      );
      return $reponse;
    }
  } else {
    $database->close();
    $reponse = (object) array(
      "success" => false,
      "message" => "Hệ thống không tồn tại tài khoản này!"
    );
    return $reponse;
  }
}

function send_code($email)
{
  $mailer = new Mailer();
  $database = new connectDB();
  $code = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
  $msg = "Your mail verification code is: $code";
  $title = "Boocify Verification Code";
  $sql = "SELECT * FROM verify_code where email='$email'";
  $result = $database->query($sql);
  if (mysqli_num_rows($result) > 0) {
    $sql = "UPDATE verify_code SET code = '$code',time_send=NOW() WHERE email = '$email'";
  } else $sql = "INSERT INTO verify_code (email, code, time_send) VALUES ('$email', '$code', NOW())";
  $database->execute($sql);

  // use wordwrap() if lines are longer than 70 characters
  $msg = wordwrap($msg, 70);

  // send email
  $mailrs = $mailer->sendMail($email, $title, $msg);
  // $mail  = mail($email, $title, $msg);
  $database->close();
  if ($mailrs) {
    return true;
  } else return false;
}
function checkRegister($username, $email)
{
  global $database;
  // Kiểm tra username có tồn tại hay chưa
  $sqlCheckExistUsername = "SELECT * FROM accounts WHERE username = '$username'";
  $resultCheckExistUsername = $database->query($sqlCheckExistUsername);
  if (mysqli_num_rows($resultCheckExistUsername) > 0) {
    return (object) array(
      'success' => false,
      'existUsername' => true,
      'message' => "Hệ thống đã tồn tại username: $username"
    );
  }
  // Kiểm tra username có tồn tại hay chưa
  $sqlCheckExistEmail = "SELECT * FROM accounts WHERE email = '$email'";
  $resultCheckExistEmail = $database->query($sqlCheckExistEmail);
  if (mysqli_num_rows($resultCheckExistEmail) > 0) {
    return (object) array(
      'success' => false,
      'existEmail' => true,
      'message' => "Hệ thống đã tồn tại email: $email"
    );
  }
  if (send_code($email)) {
    return (object) array(
      'success' => true,
      'message' => "Hãy kiểm tra email của bạn để nhập mã xác nhận, mã sẽ hết hạn sau 5 phút"
    );
  } else {
    return (object) array(
      'success' => false,
      'message' => "Đã xảy ra lỗi khi gửi mã đến Email $email của bạn"
    );
  }
}
function registerNewAccount($username, $email, $fullname, $phoneNumber, $address, $password, $city, $district, $ward)
{
  global $database;
  // Nếu như username chưa tồn tại, thì tạo tài khoản
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $sqlInsertAccount = "INSERT INTO accounts (username, password, role_id, status,email)
                      VALUES ('$username', '$hashedPassword', 3, 1,'$email')";
  $sqlInsertUserInfo = "INSERT INTO delivery_infoes (user_id, fullname, phone_number, address, city, district, ward)
                            VALUES ('$username', '$fullname', '$phoneNumber', '$address', '$city', '$district', '$ward')";

  $resultInsertAccount = $database->execute($sqlInsertAccount);
  $resultInsertUserInfo = $database->execute($sqlInsertUserInfo);

  // Nếu như insert thành công vào database
  if ($resultInsertAccount && $resultInsertUserInfo) {
    return (object) array(
      'success' => true,
      'message' => "Đăng ký thành công"
    );
  } else {
    return (object) array(
      'success' => false,
      'message' => "Đã xảy ra lỗi khi đăng ký"
    );
  }
}
function getVerifyCode($email, $time)
{
  $database = new connectDB();
  $sql_time = "SELECT *,TIME_TO_SEC(TIMEDIFF('$time',time_send)) time FROM verify_code WHERE email='$email'";
  $result = $database->query($sql_time);
  $result = mysqli_fetch_array($result);
  $database->close();
  return $result;
}
function checkVerifyCode($email, $code, $time)
{
  $result = getVerifyCode($email, $time);
  if ($result['time'] > 300) {
    send_code($email);
      return (object) array(
      'success' => false,
      'message' => "Mã đã hết hạn! Xin nhập mã mới"
    );
  }
  if($code == $result['code']){
    return (object) array(
      'success' => true,
      'message' => "Đăng ký thành công"
    );
  }else return (object) array(
    'success' => false,
    'message' => "Mã xác nhận không chính xác, xin hãy nhập lại"
  );

}
function sendNewVerifyCode($email, $time)
{
  $result = getVerifyCode($email, $time);
  if ($result['time'] <= 120) echo "Bạn phải đợi ít nhất 2 phút trước khi lấy mã mới";
  else {
    send_code($email);
    echo "Đã gửi mã xác nhận mới";
  }
}


function checkEmail($email)
{
  global $database;
  $sqlCheckExistEmail = "SELECT * FROM accounts WHERE email = '$email'";
  $resultCheckExistEmail = $database->query($sqlCheckExistEmail);
  if (mysqli_num_rows($resultCheckExistEmail) == 0) {
    return (object) array(
      'success' => false,
      'existEmail' => false,
      'message' => "Email $email chưa được đăng ký"
    );
  }
  // return (object) array(
  //   'success' => true,
  //   'message' => "Email "
  // );
  if (send_code($email)) {
    return (object) array(
      'success' => true,
      'message' => "Hãy kiểm tra email của bạn để nhập mã xác nhận, mã sẽ hết hạn sau 5 phút"
    );
  } else {
    return (object) array(
      'success' => false,
      'message' => "Đã xảy ra lỗi khi gửi mã đến Email $email của bạn"
    );
  }
}

function setPassword($email,$password)
{
  $database = new connectDB();
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  $sql = "UPDATE accounts SET password = '$hashedPassword' WHERE email = '$email'";
  $database->execute($sql);
  $database->close();
}
