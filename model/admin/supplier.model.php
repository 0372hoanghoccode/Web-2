
<?php
$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__)  . $ds . '..') . $ds;
include_once("{$base_dir}connect.php");
$database = new connectDB();
function supplier_delete($id)
{
  $date = date('Y-m-d', time());
  global $database;
  $sql = "SELECT * FROM suppliers WHERE id= ". $id ."";
  $result = $database->query($sql);
  $row = $result->fetch_assoc();

  if ($row != null) {
    $sql = "UPDATE suppliers
    SET status = ". 0 .", delete_date = '". $date ."' WHERE id = ". $id ."";
  $result = $database->execute($sql);
  if($result) {
    $result = "<span class='success'>Xoá Nhà Cung Cấp thành công</span>";
  } else {
    $result = "<span class='failed'>Xoá Nhà Cung Cấp không thành công</span>";
  }
  return $result;
  } else {
     return $result = "<span class='failed'>Nhà Cung Cấp '. $id .' không tồn tại</span>";
  }
  


}

function supplier_create($field)
{
  global $database;
  date_default_timezone_set('Asia/Ho_Chi_Minh');
  $date = date('Y-m-d', time());
  $nameUpper = strtoupper($field['name']); 
  $sql = "SELECT * from suppliers WHERE UPPER(name) COLLATE utf8mb4_bin = '$nameUpper'";

  $result = null;
  $result = $database->query($sql);
  $row = $result->fetch_assoc();
  if ($row == null || $row['status']  == 0) {
    $sql = "INSERT INTO suppliers ( name,email,number_phone,status, create_date, update_date,delete_date ) 
          VALUES ('" . $field['name'] . "','" . $field['email'] . "','" . $field['sdt'] . "','" . 1 . "', '" . $date  . "', '" . $date  . "',NULL) ";
    $result = $database->execute($sql);
    if ($result) {
      $result = "<span class='success'>Tạo Nhà Cung Cấp thành công</span>";
    } else $result = "<span class='failed'>Tạo Nhà Cung Cấp không thành công</span>";
    return ($result);
  } else return "<span class='failed'>Nhà Cung Cấp" . $row['name'] . " đã tồn tại</span>";
}
function supplier_edit($field)
{
  global $database;
  date_default_timezone_set('Asia/Ho_Chi_Minh');
  $date = date('Y-m-d', time());
  $sql = "SELECT * from suppliers WHERE id = " . $field['id'] . "";
  $result = null;
  $result = $database->query($sql);
  $row = $result->fetch_assoc();
  if ($row != null) {
    
    $sql = "UPDATE suppliers SET name = ' ". $field['name'] ." ',email = ' ". $field['email'] ." ',number_phone = ' ". $field['sdt'] ." ', update_date = '". $date ."' WHERE id = '". $field['id'] ."'";

    $result = $database->execute($sql);
    if ($result) {
      $result = "<span class='success'>Sửa Nhà Cung Cấp thành công</span>";
    } else $result = "<span class='failed'>Sửa Nhà Cung Cấp không thành công</span>";

    return ($result);
  } else return "<span class='failed'>Nhà Cung Cấp " . $row['id'] . " không tồn tại</span>";
}
