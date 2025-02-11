<?php
include_once('../../model/connect.php');
include_once('../../model/admin/receipt.model.php');
if (isset($_POST['function'])) {
  $function = $_POST['function'];
  switch ($function) {
    case 'create':
      create();
      break;

    case 'details':
      receiptDetail();
      break;
    case 'getSuppliers':
      getSuppliers();
      break;
    case 'getIdProducts':
      getIdProducts();
      break;
    case 'getPrice':
      getPriceProducts();
      break;
    case 'getProductsNumber':
      getProductNumber();
      break;
  }
}

function create()
{
  if (isset($_POST['field'])) {
    echo receipt_create($_POST['field']);
  }
}
function receiptDetail()
{
  if (isset($_POST['field'])) {
    echo receipt_detail($_POST['field']);
  }
}
function getSuppliers()
{
  echo receipt_getSuppliers();
}
function getIdProducts()
{
  if (isset($_POST['field'])) {
    echo receipt_getIdProducts($_POST['field']);
  }
}
function getPriceProducts()
{
  if (isset($_POST['field'])) {
    echo receipt_getPriceProducts($_POST['field']);
  }
}
function getProductNumber()
{
  if (isset($_POST['field'])) {
    echo receipt_getProductNumber($_POST['field']);
  }
}
