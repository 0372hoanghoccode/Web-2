<?php
include_once('../../model/connect.php');
include_once('../../model/admin/discount.model.php');
if (isset($_POST['function'])) {
  $function = $_POST['function'];
  switch ($function) {
    case 'delete':
      deleteDiscount();
      break;
    case 'create':
      createDiscount();
      break;
    case 'edit':
      editDiscount();
      break;
  }
}
function deleteDiscount()
{
  if (isset($_POST['discount_code'])) {
    $discount_code = $_POST['discount_code'];
    $delete_result = discount_delete($discount_code);
    echo $delete_result;
  }
}
function createDiscount()
{
  if (isset($_POST['field'])) {
    echo discount_create($_POST['field']);
  }
}
function editDiscount()
{
  if (isset($_POST['field'])) {
    echo discount_edit($_POST['field']);
  }
}
