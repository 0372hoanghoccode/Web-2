<?php
$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__)  . $ds . '..') . $ds;
include_once("{$base_dir}connect.php");

  function isLoginSuccess($username, $password) {
    $database = new connectDB();
    if ($database->conn) {
      $sql = "SELECT * 
              FROM accounts a
              INNER JOIN roles r ON r.id = a.role_id
              WHERE username = '$username' AND (r.name = 'admin' OR r.name = 'staff')";
      $result = $database->query($sql);
      if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $db_password = $row['password'];
  
        // So sánh mật khẩu nhập vào với mật khẩu trong cơ sở dữ liệu
        if (password_verify($password, $db_password)) {
          $database->close();
          if ($row['status'] == 0) {
            $reponse = (object) array (
              "success" => false,
              "status" => $row['status'],
              "message" => "Tài khoản của bạn đã bị khoá!" 
            );
            return $reponse;
          } else {
            $reponse = (object) array (
              "success" => true,
              "status" => $row['status'],
              "message" => "Đăng nhập thành công!" 
            );
            return $reponse;
          }
        } else {
          $database->close();
          $reponse = (object) array (
            "success" => false,
            "status" => $row['status'],
            "message" => "Tài khoản hoặc mật khẩu không chính xác!" 
          );
          return $reponse;
        }
      } else {
        $reponse = (object) array (
          "success" => false,
          "message" => "Tài khoản không đủ quyền truy cập!" 
        );
        return $reponse;
      }
    } else {
      $database->close();
      $reponse = (object) array (
        "success" => false,
        "message" => "Server hiện không phản hồi!" 
      );
      return $reponse;
    }
  }

  function getRoleIdByUsernameModel($username) {
    $database = new connectDB();
    if ($database->conn) {
      $sql = "SELECT *
              FROM accounts
              WHERE username = '$username'";
      $result = $database->query($sql);
      $database->close();
      return $result;
    } else {
      $database->close();
      return false;
    }
  }
?>