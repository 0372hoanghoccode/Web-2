
<?php
$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__)  . $ds . '..') . $ds;
include_once("{$base_dir}connect.php");
$database = new connectDB();
function author_delete($id)
{
    global $database;

    $sql = "SELECT * FROM authors WHERE id= ". $id ."";

    $result = $database->query($sql);
    $row = $result->fetch_assoc();

    if ($row != null) {
        $sql = "UPDATE authors
    SET status = ". 0 . " WHERE id = ". $id ."";
         $result = $database->query($sql);

         if($result) {
            $result = "<span class='success'>Xoá tác giả thành công</span>";
          } else {
            $result = "<span class='failed'>Xoá tác giả  không thành công</span>";
          }
          return $result;
          } else {
             return $result = "<span class='failed'>Tác giả  '. $id .' không tồn tại</span>";
          }
}





function getMaximumAuthorId() {
    global $database;

    $sql_max_id = "SELECT MAX(id) AS max_id FROM authors";
    $result_max_id = $database->query($sql_max_id);

    if ($result_max_id->num_rows > 0) {
        $row = $result_max_id->fetch_assoc();
        return $row['max_id'];
    }

    return 0; 
}

// function author_create($field)
// {
//     global $database;

//     // Lấy giá trị từ mảng $field
//     $name = $field['name'];
//     $email = $field['email'];

//     $sql_check = "SELECT * FROM authors WHERE email = '$email'";
//     $result_check = $database->query($sql_check);

//     if ($result_check->num_rows === 0) {
  
//         // Lấy ID tác giả lớn nhất
//         $maxId = getMaximumAuthorId();

//         $newId = $maxId + 1;

//         $sql_insert = "INSERT INTO authors (id, name, email, status) VALUES ('$newId', '$name', '$email', '" . 1 . "')";
//         $result_insert = $database->query($sql_insert);

//         if ($result_insert) {
//             return "<span class='success'>Tạo tác giả thành công</span>";
//         } else {
//             return "<span class='failed'>Tạo tác giả không thành công</span>";
//         }
//     }
//        return ;

// }

function author_create($field)
{
  global $database;
  $name = $field['name'];
  $email = $field['email'];

  $sql = "SELECT * FROM authors WHERE email = '$email' and status = 1";
  $result = null;
  $result = $database->query($sql);
  $row = mysqli_fetch_array($result);
  if ($row == null) {
    $sql = "INSERT INTO authors ( name, email,status) 
           VALUES ('$name', '$email', '" . 1 . "') ";
    $result = $database->execute($sql);
    if ($result) {
        $result = "<span class='success'>Tạo tác giả thành công</span>";
      } else {
        $result = "<span class='failed'>Tạo tác giả không thành công</span>";
      }
      
      return $result;
  } 
  // else return "<span class='failed'>Thể loại" . $row['name'] . " đã tồn tại</span>";
}


function author_edit($field) {
    global $database; 

    $sql_check = "SELECT * FROM authors WHERE email = '" . $field['email'] . "' AND id != " . $field['id'];
    $result_check = $database->query($sql_check);

    if ($result_check && $result_check->num_rows > 0) {
        // Email đã tồn tại cho một tác giả khác, không thể sửa đổi thành email này
        return "<span class='failed'>Email đã tồn tại cho một tác giả khác. Không thể sửa đổi.</span>";
    } else {
        // Tiến hành sửa đổi thông tin tác giả
        $sql = "UPDATE authors
        SET name = '" . $field['name'] . "', email = '" . $field['email'] . "', status = 1 
        WHERE id = " . $field['id'];

        $result = $database->query($sql);

        if ($result) {
            return "<span class='success'>Sửa tác giả thành công</span>";
        } else {
            return "<span class='failed'>Sửa tác giả không thành công</span>";
        }
    }
}


