<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  include_once('../../model/admin/index.model.php');
  include_once('../../model/admin/function_detail.model.php');

  if (isset($_POST['isLogout']) && $_POST['isLogout']) {
    try {
      unset($_SESSION['usernameAdmin']);
      echo true;
    } catch (\Throwable $th) {
      echo false;
    }
  }

  if (isset($_POST['isRender']) && $_POST['isRender']) {
    $username = $_SESSION['usernameAdmin'];

    $account = getRoleIdByUsernameModel($username);
    $account = $account->fetch_assoc();
    $roleId = intval($account['role_id']);

    $functionDetails = getAllFunctionDetailsByRolerIdModel($roleId);
    
    if ($functionDetails) {
      $functionDetails = $functionDetails->fetch_all(MYSQLI_ASSOC);
      // $account = $account->fetch_assoc();
      echo json_encode($functionDetails);
    } else {
      echo false;
    }
  }

  if (isset($_POST['isAutoUpdateData']) && $_POST['isAutoUpdateData']) {
    $totalIncome = getTotalIncome();
    $totalIncome = $totalIncome->fetch_assoc();

    $totalOrders = getTotalOrders();
    $totalOrders = $totalOrders->fetch_assoc();

    $totalProducts = getTotalProducts();
    $totalProducts = $totalProducts->fetch_assoc();

    $totalAccounts = getTotalAccounts();
    $totalAccounts = $totalAccounts->fetch_assoc();

    if ($totalIncome && $totalOrders && $totalProducts && $totalAccounts) {
      $result = array(
          'totalIncome' => $totalIncome['total_income'],
          'totalOrders' => $totalOrders['order_count'],
          'totalProducts' => $totalProducts['product_count'],
          'totalAccounts' => $totalAccounts['member_count']
      );

      echo json_encode($result);
    } else {
        echo json_encode(false);
    }
  }
  if(isset($_POST['getStats']) && $_POST['getStats'] && !empty($_POST["date_start"])&& !empty($_POST["date_end"])){
    echo getStats($_POST["date_start"],$_POST["date_end"]);
  }
  if(isset($_POST['getStatDetails']) && $_POST['getStatDetails']&& !empty($_POST["category_id"]) && !empty($_POST["date_start"])&& !empty($_POST["date_end"])){
    if(empty($_POST["orderby"]))echo getStatDetails($_POST["category_id"],$_POST["date_start"],$_POST["date_end"],"id","ASC");
    else echo getStatDetails($_POST["category_id"],$_POST["date_start"],$_POST["date_end"],$_POST["orderby"],$_POST["order_type"]);
  }
  if(isset($_POST['getBestSellers']) && $_POST['getBestSellers'] && !empty($_POST["date_start"])&& !empty($_POST["date_end"])){
    getBestSellers($_POST["date_start"],$_POST["date_end"]);
  }
  if(isset($_POST['checkFunction']) && $_POST['checkFunction'] && isset($_POST['function_id'])){
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    echo checkFunction($_SESSION['usernameAdmin'],$_POST['function_id']);
  }
?>