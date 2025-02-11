<?php
include_once('../../model/connect.php');
include_once('../../model/admin/product.model.php');
if (isset($_POST['function'])) {
  $function = $_POST['function'];
  switch ($function) {
    case 'delete':
      delete();
      break;
    case 'getCategories':
      getCategories();
      break;
    case 'getAuthors':
      getAuthors();
      break;
    case 'getPublishers':
      getPublishers();
      break;
    case 'getSuppliers':
      getSuppliers();
      break;
    case 'create':
      create();
      break;
    case 'edit':
      edit();
      break;
  }
}
function delete()
{
  if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $delete_result = product_delete($id);
    echo $delete_result->message;
  }
}
function create()
{
  if (isset($_POST['field'])) {
    echo product_create($_POST['field']);
  }
}
function edit()
{
  if (isset($_POST['field'])) {
    echo product_edit($_POST['field']);
  }
}
function getCategories()
{
  echo product_getCategories();
}
function getAuthors()
{
  echo product_getAuthors();
}
function getPublishers()
{
  echo product_getPublishers();
}
function getSuppliers()
{
  echo product_getSuppliers();
}
