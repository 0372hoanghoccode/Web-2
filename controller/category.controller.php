<?php
  include_once('model/category.model.php');

  function getCategoryList() {
    $result = getCategoryListModel();
    if ($result !== false) {
      $categories = $result->fetch_all(MYSQLI_ASSOC);
      return $categories;
    } else {
      return "Không có thể loại nào";
    }
  }

  function getCategoryById($category_id) {
    $category = getCategoryByIdModel($category_id);
    return $category;
}
?>