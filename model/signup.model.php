<?php
require "../vendor/Mailer.php";
include_once('connect.php');
$database = new connectDB();

function checkLogin($username, $password)
{
    global $database;
    $sql = "SELECT * FROM accounts WHERE username = '$username'";
    $result = $database->query($sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $role_id = $row['role_id'];
        if ($role_id != '3') {
            $reponse = (object) array(
                "success" => false,
                "message" => "Tài khoản không đủ quyền truy cập!"
            );
            return $reponse;
        }

        $db_password = $row['password'];

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

function checkRegister($username, $email)
{
    global $database;
    $sqlCheckExistUsername = "SELECT * FROM accounts WHERE username = '$username'";
    $resultCheckExistUsername = $database->query($sqlCheckExistUsername);
    if (mysqli_num_rows($resultCheckExistUsername) > 0) {
        return (object) array(
            'success' => false,
            'existUsername' => true,
            'message' => "Hệ thống đã tồn tại username: $username"
        );
    }

    $sqlCheckExistEmail = "SELECT * FROM accounts WHERE email = '$email'";
    $resultCheckExistEmail = $database->query($sqlCheckExistEmail);
    if (mysqli_num_rows($resultCheckExistEmail) > 0) {
        return (object) array(
            'success' => false,
            'existEmail' => true,
            'message' => "Hệ thống đã tồn tại email: $email"
        );
    }

    return (object) array(
        'success' => true,
        'message' => "Thông tin hợp lệ, bạn có thể đăng ký"
    );
}

function registerNewAccount($username, $email, $fullname, $phoneNumber, $address, $password, $city, $district, $ward)
{
    global $database;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sqlInsertAccount = "INSERT INTO accounts (username, password, role_id, status, email)
                        VALUES ('$username', '$hashedPassword', 3, 1, '$email')";
    $sqlInsertUserInfo = "INSERT INTO delivery_infoes (user_id, fullname, phone_number, address, city, district, ward)
                          VALUES ('$username', '$fullname', '$phoneNumber', '$address', '$city', '$district', '$ward')";

    $resultInsertAccount = $database->execute($sqlInsertAccount);
    $resultInsertUserInfo = $database->execute($sqlInsertUserInfo);

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
    return (object) array(
        'success' => true,
        'message' => "Email hợp lệ để đặt lại mật khẩu"
    );
}

function setPassword($email, $password)
{
    global $database;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE accounts SET password = '$hashedPassword' WHERE email = '$email'";
    $result = $database->execute($sql);
    if ($result) {
        return (object) array(
            'success' => true,
            'message' => "Đặt lại mật khẩu thành công"
        );
    } else {
        return (object) array(
            'success' => false,
            'message' => "Đặt lại mật khẩu thất bại"
        );
    }
}
