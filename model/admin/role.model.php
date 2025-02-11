
<?php
$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__)  . $ds . '..') . $ds;
include_once("{$base_dir}connect.php");
$database = new connectDB();
function role_delete($id)
{
  $date = date('Y-m-d', time());
  global $database;
  $sql = "SELECT * FROM functions WHERE id= ". $id ."";
  $result = $database->query($sql);
  $row = $result->fetch_assoc();

  if ($row != null) {
    $sql = "UPDATE functions
    SET status = ". 0 .", delete_date = '". $date ."' WHERE id = ". $id ."";
  $result = $database->execute($sql);
  if($result) {
    $result = "<span class='success'>Xoá Quyền thành công</span>";
  } else {
    $result = "<span class='failed'>Xoá Quyền không thành công</span>";
  }
  return $result;
  } else {
     return $result = "<span class='failed'>Quyền '. $id .' không tồn tại</span>";
  }
  


}

function role_create($field)
{
  global $database;
  date_default_timezone_set('Asia/Ho_Chi_Minh');
  $date = date('Y-m-d', time());
  $sql = "SELECT * from functions WHERE name = '" . $field['name'] . "'";

  $result = null;
  $result = $database->query($sql);
  $row = $result->fetch_assoc();
  if ($row == null) {
    $sql = "INSERT INTO functions ( name,status,delete_date , update_date) 
          VALUES ('" . $field['name'] . "','" . 1 . "',NULL, '" . $date  . "') ";
    $result = $database->execute($sql);
    if ($result) {
      $result = "<span class='success'>Tạo Quyền thành công</span>";
    } else $result = "<span class='failed'>Tạo Quyền không thành công</span>";
    return ($result);
  } else return "<span class='failed'>Quyền" . $row['name'] . " đã tồn tại</span>";
}
function role_edit($field)
{
  global $database;
  date_default_timezone_set('Asia/Ho_Chi_Minh');
  $date = date('Y-m-d', time());
  $sql = "SELECT * from functions WHERE id = " . $field['id'] . "";
  $result = null;
  $result = $database->query($sql);
  $row = $result->fetch_assoc();
  if ($row != null) {
    
    $sql = "UPDATE functions SET name = ' ". $field['name'] ." ', update_date = '". $date ."' WHERE id = '". $field['id'] ."'";

    $result = $database->execute($sql);
    if ($result) {
      $result = "<span class='success'>Sửa Quyền thành công</span>";
    } else $result = "<span class='failed'>Sửa Quyền không thành công</span>";

    return ($result);
  } else return "<span class='failed'>Quyền " . $row['id'] . " không tồn tại</span>";
}

function editFunction_Details($field) {
  global $database;

  foreach ($field['checkboxValues'] as $function_id => $checked) {
      $sql = "UPDATE function_details SET action = " . $checked . " WHERE role_id = 2 and function_id = " . $function_id;
      $result = $database->execute($sql);
      if (!$result) {
          return "<span class='failed'>Sửa Quyền không thành công</span>";
      }
  }

  return "<span class='success'>Sửa quyền nhân viên thành công</span>";
}

function init_model() {
  $html = '
  <div class="modal-edit-product-container show" id="modal-edit-container">
  <div class="modal-edit-product">
  <div class="modal-header">
      <h3>Sửa quyền</h3>
      <button class="btn-close" id="btnClose"><i class="fa-solid fa-xmark"></i></button>
  </div>
  <div class="modal-body">
  <form id="Form " style="margin-top: 10px;">
  ';
  global $database;
  $sql = "SELECT * FROM functions f INNER JOIN function_details fd on fd.function_id = f.id WHERE role_id = 2 ORDER BY id ASC";
  $result = $database->query($sql);
    if ($result->num_rows < 0) {
      return "<span class='failed'>Không có quyền nào</span>";
    } 
    while ($row = $result->fetch_array()) {
      $id = $row['id'];
      $name = $row['name'];
      $action = $row['action'];
      $checked = "";
      if($action == 1) $checked = "checked";
      $role_html = '
        <div class="input-field d-flex-start">
            <input type="checkbox"  id="'.$id.'" '. $checked.'>
            <label>'.$name.'</label>
        </div>
      ';
      $html = $html . $role_html;
    }
    $html = $html . '
            <input type="reset" value="Hủy" class="button-cancel">
            <input type="submit" value="Xác nhận" class="button-confirm" add-index="9">
        </form>
    </div>
</div>
</div>';
    return $html;
}