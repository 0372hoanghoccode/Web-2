
<?php
$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__)  . $ds . '..') . $ds;
include_once("{$base_dir}connect.php");
$database = new connectDB();
// function publisher_delete($id)
// {
//   global $database;
//   $sql_product = "SELECT * FROM products WHERE publisher_id=$id";
//   $result_product = $database->query($sql_product);
//     if(mysqli_num_rows($result_product) > 0) {
//         $error = "<span class='failed'>Xóa nhà xuất bản với mã $id KHÔNG thành công do có ràng buộc với bảng Products</span>\n";
//         return (object) array(
//             'success' => false,
//             'message' => $error
//           );
//     }
//   $sql_cat = 'DELETE FROM publishers WHERE id="' . $id . '"';
//   $result_cat = $database->query($sql_cat);
//   if ($result_cat) {
//     return (object) array(
//       'success' => true,
//       'message' => "<span class='success'>Xóa nhà xuất bản với mã $id thành công</span>"
//     );
//   } else {
//     $error = "<span class='failed'>Xóa nhà xuất bản với mã $id KHÔNG thành công</span>\n";
//     if (!$result_cat) {
//       $error += "Lỗi khi xử lý bảng publishers\n";
//     }
//     return (object) array(
//       'success' => false,
//       'message' => $error
//     );
//   }
// }
function publisher_delete($id)
{
  global $database;
  $sql = "SELECT * FROM publishers WHERE id= ". $id ."";
  $result = $database->query($sql);
  $row = $result->fetch_assoc();

  if ($row != null) {
    $sql = "UPDATE publishers
    SET status = ". 0 ." WHERE id = ". $id ."";
  $result = $database->execute($sql);
  if($result) {
    $result = "<span class='success'>Xoá thể nhà xuất bản thành công</span>";
  } else {
    $result = "<span class='failed'>Xoá thể nhà xuất bản không thành công</span>";
  }
  return $result;
  } else {
     return $result = "<span class='failed'>Nhà xuất bản '. $id .' không tồn tại</span>";
  }

}
function publisher_create($field)
{
  global $database;
  $nameUpper = strtoupper($field['name']);
  $sql = "SELECT * from publishers WHERE UPPER(name) COLLATE utf8mb4_bin = '$nameUpper' AND status = 1";
  $result = null;
  $result = $database->query($sql);
  $row = mysqli_fetch_array($result);
  if ($row == null) {
    $sql = "INSERT INTO publishers ( name, email,status) 
          VALUES ('" . $field['name'] . "', '" . $field['email']  . "',1) ";
    $result = $database->execute($sql);
    if ($result) {
      $result = "<span class='success'>Tạo nhà xuất bản thành công</span>";
    } else $result = "<span class='failed'>Tạo nhà xuất bản không thành công</span>";

    return ($result);
  } 
  // else return "<span class='failed'>Thể loại" . $row['name'] . " đã tồn tại</span>";
}
function publisher_edit($field)
{
  global $database;
  $sql = "SELECT * from publishers WHERE id = " . $field['id'] . "";
  $result = null;
  $result = $database->query($sql);
  $row = mysqli_fetch_array($result);
  if ($row != null) {
    $sql = "UPDATE publishers
          SET name= '" . $field['name'] . "',email= '" . $field['email']  .  "' 
        WHERE id=".$field['id'];

    $result = $database->execute($sql);
    if ($result) {
      $result = "<span class='success'>Sửa nhà xuất bản thành công</span>";
    } else $result = "<span class='failed'>Sửa nhà xuất bản không thành công</span>";

    return ($result);
  } else return "<span class='failed'>Nhà xuất bản " . $row['id'] . " không tồn tại</span>";
}
