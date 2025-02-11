
<?php
$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__)  . $ds . '..') . $ds;
include_once("{$base_dir}connect.php");
$database = new connectDB();

function getNewReceiptId($database)
{
  $sql = "SELECT MAX(id) AS max_id FROM goodsreceipts";
  $result = $database->query($sql);
  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return intval($row['max_id']) + 1;
  } else {
    return 1;
  }
}
function receipt_create($field)
{
  $database = new connectDB();

  date_default_timezone_set('Asia/Ho_Chi_Minh');
  $date = date('Y-m-d', time());

  // $supplierId = $field['supplierId'];
  $totalPrice = $field['totalPrice'];
  $staffId = $field['staffId'];
  $receiptId = getNewReceiptId($database);



  $sqlInsertReceipt = "INSERT INTO goodsreceipts (id, staff_id, total_price, date_create) 
                         VALUES ('" . $receiptId . "', '" . $staffId . "', '" . $totalPrice . "', '" . $date . "')";
  $resultReceipt = $database->query($sqlInsertReceipt);

  if ($resultReceipt) {

    foreach ($field['details'] as $detail) {
      $productId = $detail['productId'];
      $quantity = $detail['quantity'];
      $inputPrice = $detail['inputPrice'];

      $sqlInsertDetail = "INSERT INTO goodsreceipt_details (product_id, goodsreceipt_id, quantity, input_price) 
                                    VALUES ('" . $productId . "', '" . $receiptId . "', '" . $quantity . "', '" . $inputPrice . "')";
      $resultDetail = $database->query($sqlInsertDetail);

      $sqlUpdateQuantity = "UPDATE products SET quantity = quantity + '" . $quantity . "' WHERE id = '" . $productId . "'";
      $resultUpdateQuantity = $database->query($sqlUpdateQuantity);

      if (!$resultDetail || !$resultUpdateQuantity) {
        return "<span class='failed'>Tạo đơn nhập hàng không thành công</span>";
      }
    }
    return "<span class='success'>Tạo đơn nhập hàng thành công</span>";
  } else {
    return "<span class='failed'>Error retrieving last inserted ID</span>";
  }
}






function receipt_detail($field)
{
  // Kết nối đến cơ sở dữ liệu
  $database = new connectDB();

  // Biến goodsreceipt_id bạn đã biết trước
  $goodsreceipt_id = $field['id']; // Đảm bảo bạn truyền vào $field['id']

  try {
    $sql = "SELECT gd.product_id, p.name, gd.quantity, gd.input_price
              FROM goodsreceipt_details gd
              INNER JOIN products p ON gd.product_id = p.id
              WHERE gd.goodsreceipt_id = '$goodsreceipt_id'";

    $result = $database->query($sql);

    $htmlResult = '<table id="Table">
                      <thead>
                        <tr>
                          <th>Mã Sản Phẩm</th>
                          <th>Tên Sản Phẩm</th>
                          <th>Số Lượng</th>
                          <th>Giá Nhập</th>
                        </tr>
                      </thead>
                      <tbody>';

    foreach ($result as $row) {
      $htmlResult .= '<tr>
                            <td style=" width: 10%">' . $row['product_id'] . '</td>
                            <td style=" width: 55%">' . $row['name'] . '</td>
                            <td style=" width: 15%">' . $row['quantity'] . '</td>
                            <td style=" width: 20%">' . number_format($row['input_price']) . '&#8363</td>
                            </tr>';
    }

    $htmlResult .= '</tbody>
                    </table>';

    return $htmlResult;
  } catch (PDOException $e) {
    return "Lỗi: " . $e->getMessage();
  }
}
function receipt_getSuppliers()
{
  $database = new connectDB();

  try {
    $sql = "SELECT name,id FROM suppliers WHERE status=1";

    $stmt = $database->query($sql);

    $htmlResult = '<option value="">Chọn nhà cung cấp</option>';
    foreach ($stmt as $row) {
      $htmlResult .= '<option value="' . $row['id'] . '">' . $row['id'] . '-' . $row['name'] . '</option>';
    }

    echo $htmlResult;
  } catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
  }
}

function receipt_getIdProducts($field)
{
  $database = new connectDB();
  $goodsreceipt_id = $field['id'];

  try {
    $sql = "SELECT id, name FROM products p WHERE supplier_id = $goodsreceipt_id AND p.status=1";
    $stmt = $database->query($sql);


    $htmlResult = '<option value="">Chọn sản phẩm</option>';
    foreach ($stmt as $row) {
      $htmlResult .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
    }

    echo $htmlResult;
  } catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
  }
}
function receipt_getPriceProducts($field)
{
  $database = new connectDB();

  try {
    $id = $field['id'];
    $sql = "SELECT price FROM products p WHERE p.id = '$id'";
    $stmt = $database->query($sql);

    if ($stmt->num_rows > 0) {
      $row = $stmt->fetch_assoc();
      $inputPrice = $row['price'];
      return $inputPrice;
    } else {
      return 0;
    }
  } catch (PDOException $e) {
    return "Lỗi: " . $e->getMessage();
  }
}

function receipt_getProductNumber($field)
{
  // Kết nối đến cơ sở dữ liệu
  $database = new connectDB();
  $id = $field['id'];
  try {
    // Truy vấn đếm số lượng sản phẩm
    $sql = "SELECT quantity AS product_count FROM products WHERE id = '$id'";
    $result = $database->query($sql);

    if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return json_encode([
        'success' => true,
        'product_count' => intval($row['product_count'])
      ]);
    } else {
      return json_encode([
        'success' => false,
        'error' => 'Không tìm thấy sản phẩm'
      ]);
    }
  } catch (Exception $e) {
    // Xử lý lỗi và trả về JSON
    return json_encode([
      'success' => false,
      'error' => $e->getMessage()
    ]);
  }
}
