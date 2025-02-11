<?php
include_once('../../model/connect.php');
include_once('../../model/admin/publisher.model.php');

if (isset($_POST['function'])) {
  $function = $_POST['function'];
  switch ($function) {
    case 'delete':
      deletePublisher();
      break;
    case 'create':
      createPublisher();
      break;
    case 'edit':
      editPublisher();
      break;
    case 'checkEmailExists':  // Thêm chức năng kiểm tra email tồn tại
      checkEmailExists();
      break;
    case 'checkEmailAndNameExists':  // Thêm chức năng kiểm tra email tồn tại
      checkEmailAndNameExists();
      break;
  }
}

function deletePublisher()
{
  if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $delete_result = publisher_delete($id);
    echo $delete_result;
  }
}

function createPublisher()
{
  if (isset($_POST['field'])) {
    // Kiểm tra email trước khi tạo nhà xuất bản mới
    $email = $_POST['field']['email'];
    if (checkEmailInDatabase($email)) {
      echo json_encode(['success' => false, 'message' => 'Email đã tồn tại trong hệ thống!']);
    } else {
      echo publisher_create($_POST['field']);
    }
  }
}

function editPublisher()
{
  if (isset($_POST['field'])) {
    echo publisher_edit($_POST['field']);
  }
}

// Hàm kiểm tra email tồn tại
function checkEmailExists()
{
  if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (checkEmailInDatabase($email)) {
      echo json_encode(['exists' => true]);
    } else {
      echo json_encode(['exists' => false]);
    }
  }
}

function checkEmailAndNameExists()
{
    if (isset($_POST['email']) && isset($_POST['name'])) {
        $email = $_POST['email'];
        $name = $_POST['name'];
        $emailExists = checkEmailInDatabase($email);
        $nameExists = checkNameInDatabase($name);

        echo json_encode([
            'emailExists' => $emailExists,
            'nameExists' => $nameExists,
        ]);
    }
}

// Hàm hỗ trợ kiểm tra email trong cơ sở dữ liệu
function checkEmailInDatabase($email)
{
  global $database;  // Sử dụng đối tượng $database từ connect.php

  // Truy vấn kiểm tra email trong bảng publishers
  $sql = "SELECT COUNT(*) AS count FROM publishers WHERE email = '$email' and status =1 ";
  $result = $database->query($sql);
  $row = $result->fetch_assoc();

  // Trả về true nếu email đã tồn tại, ngược lại false
  return $row['count'] > 0;
}

function checkNameInDatabase($name)  
{  
    global $database;  // Sử dụng đối tượng $database từ connect.php  

    // Chuyển đổi tên thành chữ hoa để so sánh không phân biệt hoa thường  
    $nameUpper = strtoupper($name);  

    $sql = "SELECT COUNT(*) AS count FROM publishers WHERE UPPER(name) COLLATE utf8mb4_bin = '$nameUpper' AND status = 1";  
    $result = $database->query($sql);  
    $row = $result->fetch_assoc();  

    // Trả về true nếu tên đã tồn tại, ngược lại false  
    return $row['count'] > 0;  
}
