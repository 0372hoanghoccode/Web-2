<?php
  include_once('model/order_detail.model.php');

  function getAllOrderDetailByOrderId($orderId) {
    $orderDetails = getAllOrderDetailByOrderIdModel($orderId);
    if ($orderDetails) {
      $result = $orderDetails->fetch_all(MYSQLI_ASSOC);
      return $result;
    } else {
      return false;
    }
  }
?>