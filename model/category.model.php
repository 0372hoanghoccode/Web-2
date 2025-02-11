<?php
  $database = new connectDB();

  function getCategoryListModel() {
    global $database;
    if ($database->conn) {
      $sql = "SELECT * FROM categories WHERE status = 1";
      $result = $database->query($sql);
      return $result;
    } else {
      return false;
    }
  }

  function getCategoryByIdModel($category_id) {
    global $database;
    if (!$database->conn) return false;

    $query = "SELECT * FROM categories WHERE id = $category_id";
    $result = $database->conn->query($query);

    return $result->fetch_assoc();
}

?>