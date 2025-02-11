<?php
  include_once('connect.php');

  function addNewOrderDetail($orderId, $productId, $quantity, $price) {
    $database = new connectDB();
    if ($database->conn) {
      $sql = "INSERT INTO order_details(order_id, product_id, quantity, price)
               VALUES ($orderId, $productId, $quantity, $price);";
      $result = $database->execute($sql);
      $database->close();
      return $result;
    } else {
      $database->close();
      return false;
    }
  }

  function getAllOrderDetailByOrderIdModel($orderId) {
    $database = new connectDB();
    if ($database->conn) {
      $sql = "SELECT od.order_id, od.product_id, od.quantity, od.price, p.name as product_name, p.image_path
              FROM order_details od 
              INNER JOIN products p ON p.id = od.product_id
              WHERE order_id = $orderId";
      $result = $database->query($sql);
      $database->close();
      return $result;
    } else {
      $database->close();
      return false;
    }
  }

  function deleteOrderDetailByOrderIdAndProductId($orderId, $productId) {
    $database = new connectDB();
    if ($database->conn) {
      $sql = "DELETE FROM order_details
               WHERE order_id = $orderId AND product_id = $productId";
      $result = $database->execute($sql);
      $database->close();
      return $result;
    } else {
      $database->close();
      return false;
    }
  }
?>