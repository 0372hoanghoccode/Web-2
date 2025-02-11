
<?php
$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__)  . $ds . '..') . $ds;
include_once("{$base_dir}connect.php");
$database = new connectDB();
function category_delete($id)
{
  $date = date('Y-m-d', time());
  global $database;
  $sql = "SELECT * FROM categories WHERE id= ". $id ."";
  $result = $database->query($sql);
  $row = $result->fetch_assoc();

  if ($row != null) {
    $sql = "UPDATE categories
    SET status = ". 0 .", delete_date = '". $date ."' WHERE id = ". $id ."";
  $result = $database->execute($sql);
  if($result) {
    $result = "<span class='success'>Xoá thể loại thành công</span>";
  } else {
    $result = "<span class='failed'>Xoá thể loại không thành công</span>";
  }
  return $result;
  } else {
     return $result = "<span class='failed'>Thể loại '. $id .' không tồn tại</span>";
  }
  


}

function category_create($field)
{
  global $database;
  $nameUpper = strtoupper($field['name']); 
  date_default_timezone_set('Asia/Ho_Chi_Minh');
  $date = date('Y-m-d', time());
  $sql = "SELECT * from categories WHERE UPPER(name) COLLATE utf8mb4_bin = '$nameUpper'";

  $result = null;
  $result = $database->query($sql);
  $row = $result->fetch_assoc();
  if ($row == null || $row['status'] == 0) {
    $sql = "INSERT INTO categories ( name,status, create_date, update_date,delete_date ) 
          VALUES ('" . $field['name'] . "','" . 1 . "', '" . $date  . "', '" . $date  . "',NULL) ";
    $result = $database->execute($sql);
    if ($result) {
      $result = "<span class='success'>Tạo thể loại thành công</span>";
    } else $result = "<span class='failed'>Tạo thể loại không thành công</span>";
    return ($result);
  } else return "<span class='failed'>Thể loại " . $row['name'] . " đã tồn tại</span>";
}
function category_edit($field)
{
  global $database;
  date_default_timezone_set('Asia/Ho_Chi_Minh');
  $date = date('Y-m-d', time());
  // check valid
  $sql_check = "SELECT * FROM categories WHERE name = '" . $field['name'] . "' AND id != " . $field['id'];

  $valid = $database->query($sql_check);
  if ($valid->num_rows > 0) {
    return "<span class='failed'>Tên thể loại đã tồn tại</span>";
}

  //  edit
  $sql = "SELECT * from categories WHERE id = " . $field['id'] . "";
  $result = null;
  $result = $database->query($sql);
  $row = $result->fetch_assoc();
  if ($row != null) {
    
    $sql = "UPDATE categories SET name = ' ". $field['name'] ." ', update_date = '". $date ."' WHERE id = '". $field['id'] ."'";

    $result = $database->execute($sql);
    if ($result) {
      $result = "<span class='success'>Sửa thể loại thành công</span>";
    } else $result = "<span class='failed'>Sửa thể loại không thành công</span>";

    return ($result);
  } else return "<span class='failed'>Thể loại " . $row['id'] . " không tồn tại</span>";
}
