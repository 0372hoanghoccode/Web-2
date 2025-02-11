<?php
  if (isset($_POST['modelPath'])) {
    include_once($_POST['modelPath'].'/order.model.php');
    include_once($_POST['modelPath'].'/delivery_info.model.php');
    include_once($_POST['modelPath'].'/order_detail.model.php');
    include_once($_POST['modelPath'].'/product.model.php');
  } else {
    include_once('model/order.model.php');
    include_once('model/delivery_info.model.php');
    include_once('model/order_detail.model.php');
    include_once('model/product.model.php');
  }

  function getAllOrdersByUsername($username) {
    $orders = getAllOrdersByUsernameModel($username);
    if ($orders) {
      $orders = $orders->fetch_all(MYSQLI_ASSOC);
      return $orders; 
    } else {
      return false;
    }
  }

  if (isset($_POST['xemChiTiet']) && $_POST['xemChiTiet']) {
    $orderId = $_POST['orderId'];

    $order = getDetailOrderByOrderIdModel($orderId);
    $order = $order->fetch_assoc();

    $orderDetails = getAllOrderDetailByOrderIdModel($orderId);
    $orderDetails = $orderDetails->fetch_all(MYSQLI_ASSOC);
    
    $deliveryInfo = getDetailDeliveryInfoById($order['delivery_info_id']);
    $deliveryInfo = $deliveryInfo->fetch_assoc();

    if ($order && $orderDetails && $deliveryInfo) {
      $reponse = (object) array (
        "order" => $order,
        "orderDetails" => $orderDetails,
        "deliveryInfo" => $deliveryInfo
      );
      echo json_encode($reponse); 
    } else {
      echo false;
    }
  }

  if (isset($_POST['huyDonHang']) && $_POST['huyDonHang']) {
    $orderId = $_POST['orderId'];

    // Kiểm tra xem đơn hàng có ở trạng thái huỷ hay không?
    $order = getDetailOrderByOrderIdModel($orderId);
    $order = $order->fetch_all(MYSQLI_ASSOC);
    $orderStatus = $order[0]['order_status_id'];

    if ($orderStatus != 1 && $orderStatus != 2) {
      $reponse = (object) array (
        "success" => false,
        "message" => "Đơn hàng này hiện tại không được huỷ nữa"
      );
      echo json_encode($reponse);
      return;
    } 

    $orderDetails = getAllOrderDetailByOrderIdModel($orderId);
    $orderDetails = $orderDetails->fetch_all(MYSQLI_ASSOC);
    
    foreach ($orderDetails as $orderDetail) {
      // Thay đổi đơn hàng thành status_id = 3 (đã huỷ)
      if (deleteOrderByOrderId($orderId) && updateQuantityProductByIdModel($orderDetail['product_id'], $orderDetail['quantity'])) {
        $reponse = (object) array (
          "success" => true,
          "message" => "Hệ thống đã xoá thành công đơn hàng"
        );
        echo json_encode($reponse);
      } else {
        $reponse = (object) array (
          "success" => false,
          "message" => "Có lỗi trong quá trình xoá đơn hàng"
        );
        echo json_encode($reponse);
        return;
      }
    }
  }
?>