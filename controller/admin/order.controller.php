<?php
include_once('../../model/connect.php');
include_once('../../model/admin/order.model.php');
if (isset($_POST['function'])) {
  $function = $_POST['function'];
  switch ($function) {
    case 'delete':
      delete();
      break;
    case 'order_details':
      order_details();
      break;
    case 'order_status':
      order_status();
      break;
  }
}
function delete()
{
  if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $delete_result = order_delete($id);
    echo $delete_result-> message;
  }
}
function order_details()
{
  if (isset($_POST['id'])) {
    echo order_render($_POST['id']);
  }
}
function order_status()
{
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  if (isset($_POST['id']) && isset($_POST['status'] )&& isset($_SESSION['usernameAdmin'])) {
    echo $_SESSION['usernameAdmin'];
    echo order_change_status($_POST['id'],$_POST['status'],$_SESSION['usernameAdmin']);
  }
}

function getOrderStatusByOrderId($id) {
  $orderStatus =  getOrderStatusByOrderIdModel($id);
  if ($orderStatus) {
    $orderStatus = $orderStatus->fetch_assoc();
    return $orderStatus['status_id'];
  } else {
    return false;
  }
}