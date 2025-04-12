<?php
$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__)  . $ds . '..') . $ds;
include_once("{$base_dir}connect.php");

function product_delete($id)
{
  $database = new connectDB();

  // Kiểm tra xem sản phẩm có trong bảng order_details hay không
  $sql_check = "SELECT COUNT(*) as count FROM order_details WHERE product_id = '" . $id . "'";
  $result_check = $database->query($sql_check);
  $row_check = mysqli_fetch_array($result_check);
  $is_sold = $row_check['count'] > 0;

  if ($is_sold) {
    // Nếu sản phẩm đã được bán (tồn tại trong order_details), chỉ cập nhật trạng thái (soft delete)
    $sql = 'UPDATE products SET status="0" WHERE id="' . $id . '"';
    $result = $database->query($sql);

    $database->close();
    if ($result) {
      return (object) array(
        'success' => true,
        'message' => "<span class='success'>Đã chuyển trạng thái sản phẩm với mã $id thành Ngừng kinh doanh</span>"
      );
    } else {
      $error = "<span class='failed'>Không thể chuyển trạng thái sản phẩm với mã $id</span>";
      return (object) array(
        'success' => false,
        'message' => $error
      );
    }
  } else {
    // Nếu sản phẩm chưa được bán (không tồn tại trong order_details), xóa thật (hard delete)

    // Xóa các bản ghi liên quan trong category_details
    $sql = 'DELETE FROM category_details WHERE product_id="' . $id . '"';
    $database->execute($sql);

    // Xóa các bản ghi liên quan trong author_details
    $sql = 'DELETE FROM author_details WHERE product_id="' . $id . '"';
    $database->execute($sql);

    // Xóa bản ghi trong bảng products
    $sql = 'DELETE FROM products WHERE id="' . $id . '"';
    $result = $database->query($sql);

    $database->close();
    if ($result) {
      return (object) array(
        'success' => true,
        'message' => "<span class='success'>Xóa sản phẩm với mã $id thành công</span>"
      );
    } else {
      $error = "<span class='failed'>Xóa sản phẩm với mã $id KHÔNG thành công</span>";
      return (object) array(
        'success' => false,
        'message' => $error
      );
    }
  }
}

function product_getCategories()
{
  $database = new connectDB();
  $sql = 'SELECT * FROM categories';
  $result = $database->query($sql);
  $ans = "<option value=''>Chọn thể loại</option>";
  while ($row = mysqli_fetch_array($result)) {
    if($row['status']==1) $ans = $ans . "<option value=" . $row["id"] . ">" . $row["name"] . "</option>\n";
  }
  $database->close();
  return $ans;
}

function product_getAuthors()
{
  $database = new connectDB();
  $sql = 'SELECT * FROM authors';
  $result = $database->query($sql);
  $ans = "<option value=''>Chọn tác giả</option>";
  while ($row = mysqli_fetch_array($result)) {
    if($row['status']==1) $ans = $ans . "<option value=" . $row["id"] . ">" . $row["name"] . "</option>\n";
  }
  $database->close();
  return $ans;
}

function product_getPublishers()
{
  $database = new connectDB();
  $sql = 'SELECT * FROM publishers';
  $result = $database->query($sql);
  $ans = "<option value=''>Chọn nhà xuất bản</option>";
  while ($row = mysqli_fetch_array($result)) {
    $ans = $ans . "<option value=" . $row["id"] . ">" . $row["name"] . "</option>\n";
  }
  $database->close();
  return $ans;
}

function product_getSuppliers()
{
  $database = new connectDB();
  $sql = 'SELECT * FROM suppliers';
  $result = $database->query($sql);
  $ans = "<option value=''>Chọn nhà cung cấp</option>";
  while ($row = mysqli_fetch_array($result)) {
    $ans = $ans . "<option value=" . $row["id"] . ">" . $row["name"] . "</option>\n";
  }
  $database->close();
  return $ans;
}

function product_create($field)
{
  $database = new connectDB();
  if ($field['id'] == 0) {
    $field['id'] = 1;
    $sql = "SELECT id FROM `products` ORDER BY id DESC LIMIT 1";
    $result = null;
    $result = $database->query($sql);
    $row = mysqli_fetch_array($result);
    if ($row != null) {
      $field['id'] = $row['id'] + 1;
    }
  }
  date_default_timezone_set('Asia/Ho_Chi_Minh');
  $date = date('Y-m-d', time());
  $sql = "SELECT * from products WHERE id = " . $field['id'] . "";
  $result = null;
  $result = $database->query($sql);
  $row = mysqli_fetch_array($result);
  if ($row == null) {
    // create new image and image file name
    $base64_string = $field['image'];
    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode(',', $base64_string);
    $file_type = str_replace("data:image/", "", $data[0]);
    $file_type = str_replace(";base64", "", $file_type);
    $image_path = "assets/images/product/image_" . $field['id'] . ".$file_type";
    // open the output file for writing
    if (file_exists("../../" . $image_path)) unlink("../../$image_path");
    $ifp = fopen("../../$image_path", 'wb');
    // we could add validation here with ensuring count( $data ) > 1
    fwrite($ifp, base64_decode($data[1]));
    // clean up the file resource
    fclose($ifp);
    // Sử dụng giá trị status từ dữ liệu gửi lên ($field['status'])
    $sql = "INSERT INTO products (id, name, publisher_id, image_path, create_date, update_date, price, quantity, supplier_id, status)
            VALUES ('" . $field['id'] . "', '" . $field['name'] . "', '" . $field['publisher_id'] . "', '" . $image_path .
            "', '" . $date  . "', '" . $date  . "', '" . $field['price'] . "', '0','".$field['supplier_id']."','" . $field['status'] . "') ";
    $result = $database->execute($sql);
    if ($result) {
      $result = "<span class='success'>Tạo sản phẩm thành công</span>";
      if(isset($field['category']) && count($field['category']) > 0){
        foreach ($field['category'] as $category_id) {
          $sql = "INSERT INTO `category_details` (`product_id`, `category_id`) VALUES ('" . $field['id'] . "', '" . $category_id . "'); ";
          $database->execute($sql);
        }
      }
      if(isset($field['author']) && count($field['author']) > 0){
        foreach ($field['author'] as $author_id) {
          $sql = "INSERT INTO `author_details` (`product_id`, `author_id`) VALUES ('" . $field['id'] . "', '" . $author_id . "'); ";
          $database->execute($sql);
        }
      }
    } else {
      $result = "<span class='failed'>Tạo sản phẩm không thành công</span>";
    }
    $database->close();
    return ($result);
  } else {
    return "<span class='failed'>Sản phẩm " . $row['id'] . " đã tồn tại</span>";
  }
}

function product_edit($field)
{
  $database = new connectDB();
  date_default_timezone_set('Asia/Ho_Chi_Minh');
  $date = date('Y-m-d', time());
  $sql = "SELECT * from products WHERE id = " . $field['id'] . "";
  $result = null;
  $result = $database->query($sql);
  $row = mysqli_fetch_array($result);
  if ($row == null) return "<span class='failed'>Sản phẩm " . $row['id'] . " không tồn tại</span>";
  $image_path = $row["image_path"];
  if ($field['image'] != "") {
    // xóa ảnh cũ
    if (file_exists("../../" . $row["image_path"])) unlink("../../$image_path");
    $base64_string = $field['image'];
    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode(',', $base64_string);
    $file_type = str_replace("data:image/", "", $data[0]);
    $file_type = str_replace(";base64", "", $file_type);
    $image_path = "assets/images/product/image_" . $field['id'] . ".$file_type";
    // open the output file for writing
    $ifp = fopen("../../$image_path", 'wb');
    // we could add validation here with ensuring count( $data ) > 1
    fwrite($ifp, base64_decode($data[1]));
    // clean up the file resource
    fclose($ifp);
  }
  $sql = 'DELETE FROM category_details WHERE product_id="' . $field['id'] . '"';
  $database->execute($sql);
  $sql = 'DELETE FROM author_details WHERE product_id="' . $field['id'] . '"';
  $database->execute($sql);
  if(isset($field['category']) && count($field['category']) > 0){
    foreach ($field['category'] as $category_id) {
      $sql = "INSERT INTO `category_details` (`product_id`, `category_id`) VALUES ('" . $field['id'] . "', '" . $category_id . "'); ";
      $database->execute($sql);
    }
  }
  if(isset($field['author']) && count($field['author']) > 0){
    foreach ($field['author'] as $author_id) {
      $sql = "INSERT INTO `author_details` (`product_id`, `author_id`) VALUES ('" . $field['id'] . "', '" . $author_id . "'); ";
      $database->execute($sql);
    }
  }
  $sql = "UPDATE products
          SET name= '" . $field['name'] . "', publisher_id= '" . $field['publisher_id'] . "', image_path= '" . $image_path .
          "', update_date= '" . $date  . "', price= '" . $field['price'] . "', supplier_id= '" . $field['supplier_id']. "', status= '" . $field['status']. "' WHERE id=" . $field['id'];

  $result = $database->execute($sql);

  $database->close();
  if ($result) {
    $result = "<span class='success'>Sửa sản phẩm thành công</span>";
  } else {
    $result = "<span class='failed'>Sửa sản phẩm không thành công</span>";
  }

  return ($result);
}
