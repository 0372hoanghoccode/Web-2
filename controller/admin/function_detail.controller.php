<?php
  include_once('../../model/connect.php');

  function getAllFunctionDetailsByUserRoleId($userRoleId) {
    $functionDetails =  getAllFunctionDetailsByUserRoleIdModel($userRoleId);
    if ($functionDetails) {
      $functionDetails = $functionDetails->fetch_all(MYSQLI_ASSOC);
      return $functionDetails;
    } else {
      return false;
    }
  }

  // function getAllFunctionDetails() {
  //   $functionDetails =  getAllFunctionDetailsModel();
  //   if ($functionDetails) {
  //     $functionDetails = $functionDetails->fetch_all(MYSQLI_ASSOC);
  //     return $functionDetails;
  //   } else {
  //     return false;
  //   }
  // }
?>