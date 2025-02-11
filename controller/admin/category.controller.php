<?php
include_once('../../model/connect.php');
include_once('../../model/admin/category.model.php');
if (isset($_POST['function'])) {
  $function = $_POST['function'];
  switch ($function) {
    case 'delete':
      deleteCategory();
      break;
    case 'create':
      createCategory();
      break;
    case 'edit':
      editCategory();
      break;
    case 'checkNameExists':
      checkNameExists();
      break;
      case 'checkCategoryExists':
        checkCategoryExists();
        break;
  }
}
function deleteCategory()
{
  if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $delete_result = category_delete($id);
    echo $delete_result;
  }
}
function createCategory()
{
  if (isset($_POST['field'])) {
    echo category_create($_POST['field']);
  }
}
function editCategory()
{
  if (isset($_POST['field'])) {
    echo category_edit($_POST['field']);
  }
}

function checkNameInDatabase($name)  
{  
    global $database;  // Sử dụng đối tượng $database từ connect.php  

    // Chuyển đổi tên thành chữ hoa để so sánh không phân biệt hoa thường  
    $nameUpper = strtoupper($name);  

    $sql = "SELECT COUNT(*) AS count FROM categories WHERE UPPER(name) COLLATE utf8mb4_bin = '$nameUpper' AND status = 1";  
    $result = $database->query($sql);  
    $row = $result->fetch_assoc();  

    // Trả về true nếu tên đã tồn tại, ngược lại false  
    
    return $row['count'] > 0;  
}

function checkNameExists()
{
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
        echo json_encode(['exists' => checkNameInDatabase($name)]);
    }
}

function checkCategoryExists() {
  global $database; // Sử dụng đối tượng $database từ kết nối
  $name = $_POST['name'];
  $id = $_POST['id'];

  $sql = "SELECT COUNT(*) AS count FROM categories WHERE UPPER(name) = UPPER('$name') AND id != '$id' AND status = 1";
  $result = $database->query($sql);
  $row = $result->fetch_assoc();

  echo json_encode(['exists' => $row['count'] > 0]);
}

