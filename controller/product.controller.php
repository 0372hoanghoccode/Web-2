<?php
  if (isset($_POST['modelPath'])) {
    include_once($_POST['modelPath'].'/product.model.php');
  } else {
    include_once('model/product.model.php');
  }

  // include_once("../model/product.model.php");

  function getProductsByIdCategory($category_id, $items_amount = null, $page = null) {
    $result = getProductsByIdCategoryModel($category_id, $items_amount, $page);
    if ($result !== false) {
      $products = $result->fetch_all(MYSQLI_ASSOC);
      return $products;
    } else {
      return "Hệ thống gặp sự cố";  
    }
  }

  function getNewProducts($status) {
    $result = getNewProductsModel($status);
    if ($result !== false) {
      $products = $result->fetch_all(MYSQLI_ASSOC);
      return $products;
    } else {
      return "Hệ thống gặp sự cố";  
    }
  }

  // function getProductsForPagination($item_per_page, $page) {
  //   $result = getProductsForPaginationModel($item_per_page, $page);
  //   if ($result !== false) {
  //     if ($result->num_rows > 0) {
  //       return (object) array (
  //         'success' => true,
  //         'data' => $result->fetch_all(MYSQLI_ASSOC)
  //       );
  //     } else {
  //       return (object) array (
  //         'success' => false,
  //         'message' => "Không có sản phẩm nào"
  //       );
  //     }
  //   } else {
  //     return (object) array (
  //       'success' => false,
  //       'message' => "Không có sản phẩm nào"
  //     );
  //   }
  // }

  function getAmountProduct() {
    $result = getAmountProductModel();
    if ($result !== false) {
      return $result;
    } else {
      return "Hệ thống gặp sự cố";
    }
  }

  // Xử lý render sản phẩm (page=product)
  if (isset($_POST['currentPage']) && isset($_POST['itemsPerPage'])) {
    $listCategoryIds = [];
    if(isset($_POST['listCategoryIds'])) {
      if(is_array($_POST['listCategoryIds']) && count($_POST['listCategoryIds']) > 0) {
        foreach($_POST['listCategoryIds'] as $categoryId) {
          $listCategoryIds[] = intval($categoryId);
        }
      }
    }

    $priceRange= $_POST['priceRange'];
    $itemsPerPage = $_POST['itemsPerPage'];
    $keyword = $_POST['keyword'];
    $page = intval($_POST['currentPage']);
    
    $startRange = 0;
    $endRange = 0;

    // Gán giá trị lại cho startRange && endRange nếu có chọn lọc theo giá
    if ($priceRange == 'duoi50') {
      $startRange = 0;
      $endRange = 49000;
    } else if ($priceRange == 'tu50duoi100') {
      $startRange = 50000;
      $endRange = 100000;
    } else if ($priceRange == 'tu100duoi200') {
      $startRange = 100000;
      $endRange = 200000;
    } else if ($priceRange == 'tren200') {
      $startRange = 200001;
      $endRange = 1000000000;
    } else {
      $startRange = 0;
      $endRange = 0;
    }
    
    $amountProduct = getProductsByFilter($keyword, $listCategoryIds, $startRange, $endRange, null, null)->num_rows;
    $result = getProductsByFilter($keyword, $listCategoryIds, $startRange, $endRange, $itemsPerPage, $page);

    if ($result && $result->num_rows > 0) {
      $products = $result->fetch_all(MYSQLI_ASSOC);

      $response = (object) array(
        'listCategoryIds' => count($listCategoryIds),
        'success' => true,
        'products' => $products,
        'page' => $page,
        'amountProduct' => $amountProduct
      );
      echo json_encode($response);
    } else {
      $response = (object) array(
        'success' => false,
        'message' => "Không tìm thấy sản phẩm"
      );
      echo json_encode($response);
    }
  }

  if (isset($_POST['isSearch']) && $_POST['isSearch']) {
    $keyword = $_POST['keyword'];

    if ($keyword == '') {
      $reponse = (object) array (
        "success" => true,
        "products" => []
      );
      echo json_encode($reponse);
    } else {
      $result = searchProductsByKeywordModel($keyword);
      if ($result && $result->num_rows > 0) {
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $reponse = (object) array (
          "success" => true,
          "products" => $products
        );
        echo json_encode($reponse);
      } else {
        $reponse = (object) array (
          "success" => false,
          "message" => "Không có sản phẩm"
        );
        echo json_encode($reponse);
      }
    }
  }

  function getProductsByUnknownCategory() {
    $result = getProductsByUnknownCategoryModel();
    if ($result !== false) {
      $result = $result->fetch_all(MYSQLI_ASSOC);
      return $result;
    } else {
      return "Hệ thống gặp sự cố";
    }
  }
?>