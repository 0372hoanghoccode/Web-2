<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  include_once('../../model/admin/loginAdmin.model.php');

  if (isset($_POST['isLogin']) && isset($_POST['isLogin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $response = isLoginSuccess($username, $password);
    if ($response->success && $response->status == 1) {
      $_SESSION['usernameAdmin'] = $username;
    }
    echo json_encode($response);
  }
?>