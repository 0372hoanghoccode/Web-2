<?php
include_once('../../model/connect.php');
include_once('../../model/admin/author.model.php');
if (isset($_POST['function'])) {
  $function = $_POST['function'];
  switch ($function) {
    case 'delete':
      deleteAU();
      break;
    case 'create':
      create();
      break;
    case 'edit':
      edit();
      break;
    case 'checkEmailExists':
      checkEmailExists();
      break;
  }
}
function deleteAU()
{
  if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $delete_result = author_delete($id);
    echo $delete_result;
  }
}
function create()
{
  if (isset($_POST['field'])) {
    echo author_create($_POST['field']);
  }
}
function edit()
{
  if (isset($_POST['field'])) {
    echo author_edit($_POST['field']);
  }
} 

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

function checkEmailInDatabase($email)
{
  global $database;  // Sử dụng đối tượng $database từ connect.php

  // Truy vấn kiểm tra email trong bảng publishers
  $sql = "SELECT COUNT(*) AS count FROM authors WHERE email = '$email' and status =1 ";
  $result = $database->query($sql);
  $row = $result->fetch_assoc();

  // Trả về true nếu email đã tồn tại, ngược lại false
  return $row['count'] > 0;
}


