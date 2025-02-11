<?php
include_once('connect.php');

function checkIsExistDiscountCode($discountCode) {
  $database = new connectDB();
  if ($database->conn) {
    $sql = "SELECT *
            FROM discounts
            WHERE discount_code = '$discountCode' AND status = 1";
    $result = $database->query($sql);
    $database->close();
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);

      return (object) array (
        "success" => true,
        "discount_code" => $row['discount_code'],
        "discount_value" => $row['discount_value'],
        "type" => $row['type'],
        "start_date" => $row['start_date'],
        "finish_date" => $row['end_date']
      );
    } else {
      return (object) array (
        "success" => false,
        "message" => "Mã giảm giá không tồn tại"
      );
    }
  } else {
    $database->close();
    return false;
  }
}
?>