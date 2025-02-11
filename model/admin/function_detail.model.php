<?php
  $ds = DIRECTORY_SEPARATOR;
  $base_dir = realpath(dirname(__FILE__)  . $ds . '..') . $ds;
  include_once("{$base_dir}connect.php");

  function getAllFunctionDetailsByUserRoleIdModel($userRoleId) {
    $database = new connectDB();
    if ($database->conn) {
      $sql = "SELECT *
              FROM function_details fd
              WHERE role_id = $userRoleId AND action = 1";
      $result = $database->query($sql);
    
      $database->close();
      return $result;
    } else {
      $database->close();
      return false;
    }
  }

  function getAllFunctionDetailsByRolerIdModel($roleId) {
    $database = new connectDB();
    if ($database->conn) {
      $sql = "SELECT *
              FROM function_details
              WHERE role_id = $roleId AND action = 1";
      $result = $database->query($sql);
      $database->close();
      return $result;
    } else {
      $database->close();
      return false;
    }
  }
?>