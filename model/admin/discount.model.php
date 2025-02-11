
  <?php
  $ds = DIRECTORY_SEPARATOR;
  $base_dir = realpath(dirname(__FILE__)  . $ds . '..') . $ds;
  include_once("{$base_dir}connect.php");
  $database = new connectDB();
  function discount_delete($discount_code)
  {
    $date = date('Y-m-d', time());
    global $database;
    $sql = "SELECT * FROM discounts WHERE discount_code = '". $discount_code ."'";

    $result = $database->query($sql);
    $row = $result->fetch_assoc();

    if ($row != null) {
      $sql = "UPDATE discounts
      SET status = ". 0 .", delete_date = '". $date ."' WHERE discount_code = '". $discount_code ."'";
    $result = $database->execute($sql);
    if($result) {
      $result = "<span class='success'>Xoá Mã giảm giá thành công</span>";
    } else {
      $result = "<span class='failed'>Xoá Mã giảm giá không thành công</span>";
    }
    return $result;
    } else {
      return $result = "<span class='failed'>Mã giảm giá '. $discount_code .' không tồn tại</span>";
    }
    


  }

  function discount_create($field)
  {
    global $database;
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $date = date('Y-m-d', time());

    $SQL_CHECK = "SELECT * FROM discounts WHERE discount_code = '".$field['discount_code']."'";
    $CHECK_RES = $database->query($SQL_CHECK);
    if ($CHECK_RES->num_rows > 0) {
      return "<span class='failed'>Tên mã giảm giá đã tồn tại</span>";
  }

    $sql = "SELECT * from discounts WHERE discount_code = '" . $field['discount_code'] . "'";

    $result = null;
    $result = $database->query($sql);
    $row = $result->fetch_assoc();
    if ($row == null ) {
      $sql = "INSERT INTO discounts ( discount_code,status,type, discount_value, start_date,end_date,create_date ) 
            VALUES ('" . $field['discount_code'] . "','" . 1 . "', '" . $field['type']  . "', '" . $field['discount_value']  . "','" . $field['start_date']  . "', '" . $field['end_date']  . "', '" . $date . "' ) ";
      $result = $database->execute($sql);
      if ($result) {
        $result = "<span class='success'>Tạo Mã giảm giá thành công</span>";
      } else $result = "<span class='failed'>Tạo Mã giảm giá không thành công</span>";
      return ($result);
    } else if($row != null && $row['status'] == 0) {
      $sql = "UPDATE discounts SET type = '". $field['type'] ."',discount_value = '". $field['discount_value'] ."'  ,status = '". 1 ."' , start_date = '". $field['start_date'] ."' , end_date = '". $field['end_date'] ."' , delete_date = NULL WHERE discount_code = '". $field['discount_code'] ."' ";

      $result = $database->execute($sql);
      if ($result) {
        $result = "<span class='success'>Thêm mã giảm giá thành công</span>";
      } else $result = "<span class='failed'>Thêm Mã giảm giá không thành công</span>";

      return ($result);

    }
    else return "<span class='failed'>Mã giảm giá " . $row['discount_code'] . " đã tồn tại</span>";
  }
  function discount_edit($field)
  {
    global $database;
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $date = date('Y-m-d', time());
    $sql = "SELECT * from discounts WHERE discount_code = '" . $field['discount_code'] . "' ";
    $result = null;
    $result = $database->query($sql);
    $row = $result->fetch_assoc();
    if ($row != null) {
      
      $sql = "UPDATE discounts SET type = '". $field['type'] ."',discount_value = '". $field['value_discount'] ."' , start_date = '". $field['start_date'] ."' , end_date = '". $field['end_date'] ."' , update_date = '". $date ."' WHERE discount_code = '". $field['discount_code'] ."' ";

      $result = $database->execute($sql);
      if ($result) {
        $result = "<span class='success'>Sửa Mã giảm giá thành công</span>";
      } else $result = "<span class='failed'>Sửa Mã giảm giá không thành công</span>";

      return ($result);
    } else return "<span class='failed'>Mã giảm giá " . $row['discount_code'] . " không tồn tại</span>";
  }
