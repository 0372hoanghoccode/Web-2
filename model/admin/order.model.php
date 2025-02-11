<?php
$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__)  . $ds . '..') . $ds;
include_once("{$base_dir}connect.php");
$database = new connectDB();

function order_render($id)
{
  $database = new connectDB();
  $sql = "SELECT * FROM order_details WHERE order_id='$id'";
  $result = $database->query($sql);
  $count = 0;
  while ($row = mysqli_fetch_array($result)) {
    $count++;
    $total_tmp = ($row["price"]) * ($row["quantity"]);
    $total_str = "";
    while ($total_tmp > 0) {
      $total_str = substr("$total_tmp", -3, 3) . '.' . $total_str;
      $total_tmp = substr("$total_tmp", 0, -3);
    }
    $price = "";
    $price_number = $row["price"];
    while ($price_number > 0) {
      $price = substr("$price_number", -3, 3) . '.' . $price;
      $price_number = substr("$price_number", 0, -3);
    }
    $sql = "SELECT * FROM products WHERE id='" . $row["product_id"] . "'";
    $result_product = $database->query($sql);
    $product = mysqli_fetch_array($result_product);
    echo "<tr>
          <th scope='row'>$count</th>
          <td>" . $product["name"] . "</td>
          <td>" . trim($price, '.') . "&#8363;</td>
          <td>{$row["quantity"]}</td>
          <td>" . trim($total_str, '.') . "&#8363;</td>
          </tr>";
  }
  $sql = "SELECT * FROM orders WHERE id='$id'";
  $result = $database->query($sql);
  $result = mysqli_fetch_array($result);
  $result = $result["status_id"];
  echo "<tr>
  <th></th>
  <td></td>
  <td></td>
  <td>Mã Giảm Giá</td>
  <td id='discount_code'>20%</td>
  </tr>
  <tr>
  <th></th>
  <td></td>
  <td></td>
  <td class='total-price'>Thành Tiền</td>
  <td class='total-price' id='price-number'></td>
  </tr>
  <input type='hidden' id='id' value='$id'>
  <input type='hidden' id='status' value='$result'>";
  $database->close();
}

function order_change_status($id, $status, $staff_id)
{
  $database = new connectDB();
  $sql = "UPDATE orders SET status_id='$status' ,staff_id='$staff_id' WHERE id='$id'";
  $database->execute($sql); {
    if ($status == "3")
      $sql = "SELECT * FROM order_details where order_id =" . $id;
    $result = $database->query($sql);
    while ($row = mysqli_fetch_array($result))
      updateQuantityProductByIdModel($row["product_id"], $row["quantity"]);
  }
  $database->close();
}
function updateQuantityProductByIdModel($id, $quantity)
{
  $database = new connectDB();
  if ($database->conn) {
    $sql = "SELECT *
            FROM products
            WHERE id = $id";
    $isExist = $database->query($sql);

    // Nếu sản phẩm tồn tại
    if ($isExist && $isExist->num_rows > 0) {
      $sqlUpdateAmount = "UPDATE products
                          SET quantity = quantity + $quantity
                          WHERE id = $id";
      $result = $database->execute($sqlUpdateAmount);
      $database->close();
      return $result;
    }
    $database->close();
    return false;
  } else {
    $database->close();
    return false;
  }
}
function getOrderStatusByOrderIdModel($id)
{
  $database = new connectDB();
  if ($database->conn) {
    $sql = "SELECT status_id
            FROM orders
            WHERE id = $id";
    $result = $database->query($sql);
    $database->close();
    return $result;
  } else {
    $database->close();
    return false;
  }
  $database->close();
}
