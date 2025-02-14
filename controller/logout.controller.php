<?php
  session_start();
  ob_start();

  if (isset($_POST['logoutRequest']) && $_POST['logoutRequest'] == true) {
    if (isset($_SESSION['usernameAdmin'])) {
      $usernameAdmin = $_SESSION['usernameAdmin'];
    }

    session_unset();

    if (isset($usernameAdmin)) {
      $_SESSION['usernameAdmin'] = $usernameAdmin;
    }
  }
?>
