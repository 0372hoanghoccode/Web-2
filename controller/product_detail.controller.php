<?php
  include_once('model/product_detail.model.php');

  function getProductDetailById($product_id, $closeDatabase = false) {
    $result = getProductDetailByIdModel($product_id, $closeDatabase);
    if ($result !== false) {
      $productDetail = $result->fetch_assoc();
      return $productDetail;
    } else {
      return "Hệ thống gặp sự cố";
    }
  }
?>