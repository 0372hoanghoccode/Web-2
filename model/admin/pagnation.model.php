<?php
class pagnation
{
    private $number_of_item;
    private $current_page;
    private $table;
    private $filter = "";
    private $orderby = "";

    public function __construct($number_of_item, $current_page, $table)
    {
        $this->number_of_item = $number_of_item;
        $this->current_page = $current_page;
        $this->table = $table;
    }
    public function setNumberOfItem($number_of_item)
    {
        $this->number_of_item = $number_of_item;
    }
    public function getNumberOfItem()
    {
        return $this->number_of_item;
    }
    public function setCurrentPage($current_page)
    {
        $this->current_page = $current_page;
    }
    public function getCurrentPage()
    {
        return $this->current_page;
    }
    public function setTable($table)
    {
        $this->table = $table;
    }
    public function getTable()
    {
        return $this->table;
    }
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }
    public function getFilter()
    {
        return $this->filter;
    }
    public function setOrderby($orderby)
    {
        $this->orderby = $orderby;
    }
    public function getOrderby()
    {
        return $this->orderby;
    }
    public function getData()
    {

        $database = new connectDB();
        $offset = ($this->current_page - 1) * $this->number_of_item;
        $sql = "SELECT DISTINCT $this->table.* FROM $this->table $this->filter $this->orderby LIMIT $this->number_of_item OFFSET $offset ";
        // echo $sql;
        $result = $database->query($sql);
        $database->close();
        if ($result->num_rows > 0) {
            return (object) array(
                'success' => true,
                'data' => $result
            );
        } else {
            return (object) array(
                'success' => false
            );
        }
    }
    function getTotalRecords()
    {

        $database = new connectDB();
        $total_records = $database->query("SELECT DISTINCT $this->table.* FROM $this->table $this->filter");
        $database->close();
        return $total_records->num_rows;
    }
    public function render()
    {
        $database = new connectDB();
        $load_result = $this->getData();
        if ($load_result->success) {
            $result = $load_result->data;
            switch ($this->table) {

                case "products": {
                        echo '<div class="table__wrapper">
                    <table id="content-product">
                        <thead class="menu">
                            <tr>
                                <th data-order="id">Mã SP</i></th>
                                <th>Ảnh</th>
                                <th data-order="name">Tên Sản Phẩm</i></th>
                                <th>Thể loại</i></th>
                                <th>Ngày cập nhật/Ngày tạo</th>
                                <th>Tác giả</th>
                                <th data-order="price">Giá</i></th>
                                <th data-order="quantity">Số lượng</i></th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="table-content" id="content">';

                        while ($row = mysqli_fetch_array($result)) {
                            // masp
                            echo '<tr>
                        <td class="id" publisher_id="' . $row['publisher_id'] . '" supplier_id="' . $row['supplier_id'] . '"status="' . $row['status'] . '">' . $row['id'] . '</td>';
                            // img

                            echo '<td class="image">
                        <img src="../' . $row['image_path'] . '" alt="image not found" />';
                            //name
                            echo '<td class="name">' . $row['name'] . '</td>';
                            //catagory
                            $cat_sql = "SELECT *  FROM `categories` WHERE id IN (SELECT category_id  FROM `category_details` WHERE product_id = '" . $row['id'] . "')";
                            $select_result = $database->query($cat_sql);

                            $str = "";
                            $deleted_str = "";
                            $name = "";
                            while ($select = mysqli_fetch_array($select_result)) {
                                if ($select['status'] == 1) $str = $str . $select['id'] . ',';
                                else $deleted_str = $deleted_str . $select['id'] . ',';
                                if ($select['status'] == 1) $name = $name . $select['name'] . ', ';
                            }
                            $str =  rtrim($str, ", ");
                            $deleted_str =  rtrim($deleted_str, ", ");
                            $name =  rtrim($name, ", ");
                            echo '<td class="type" value="[' . $str . ']"' . 'value_hidden="[' . $deleted_str . ']".>' . $name . '</td>';
                            // date
                            echo '<td class="date">' . date("d/m/Y", strtotime($row['update_date'])) . ' ' . date("d/m/Y", strtotime($row['create_date'])) . '</td>';
                            //author
                            $author_sql = "SELECT *  FROM `authors` WHERE id IN (SELECT author_id  FROM `author_details` WHERE product_id = '" . $row['id'] . "')";
                            $select_result = $database->query($author_sql);

                            $str = "";
                            $deleted_str = "";
                            $name = "";
                            while ($select = mysqli_fetch_array($select_result)) {
                                if ($select['status'] == 1) $str = $str . $select['id'] . ',';
                                else $deleted_str = $deleted_str . $select['id'] . ',';
                                if ($select['status'] == 1) $name = $name . $select['name'] . ', ';
                            }
                            $str =  rtrim($str, ", ");
                            $deleted_str =  rtrim($deleted_str, ", ");
                            $name =  rtrim($name, ", ");
                            echo '<td class="author" value="[' . $str . ']"' . 'value_hidden="[' . $deleted_str . ']".>' . $name . '</td>';
                            //price and amount
                            echo '<td class="price">';

                            echo  money_format($row['price']) . '</td> <td class="amount">' . $row['quantity'] . '</td>';
                            // button
                            echo '<td class="actions ">
                               <button class="actions--delete">Xoá</button>
                        <button class="actions--edit" >Sửa</button>
                        </td>';
                            echo '</tr>';
                        }
                        echo '
                        </tbody>
                        </table>

                        </div>';
                    }
                    break;
                case "suppliers": {
                        echo '
                    <div class="table__wrapper">
                    <table id="content-product">
                        <thead class="menu">
                            <tr>
                            <th data-order="id">Mã nhà cung cấp</th>
                            <th data-order="name">Tên nhà cung cấp</th>
                            <th data-order="email">Email </th>
                            <th data-order="number_phone">Số điện thoại </th>
                            <th>Trạng thái </th>
                            <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="table-content" id="content">

                    ';

                        while ($row = mysqli_fetch_array($result)) {
                            echo '<tr>';
                            echo '<td class="id">'  . $row['id'] . '</td>';
                            echo '<td class="name">' . $row['name'] . '</td>';
                            echo '<td class="email">' . $row['email'] . '</td>';
                            echo '<td class="number_phone">' . $row['number_phone'] . '</td>';
                            if ($row['status'] == 1) {
                                echo '<td class="status" >Đang hoạt động</td>';
                            } else {
                                echo '<td class="status" >Không hoạt động</td>';
                            }
                            $disabled = ($row['status'] == 0) ? 'disabled' : '';
                            // echo '<td class="status">' . $row['status'] . '</td>';
                            echo '<td class="actions">
                             <button class="actions--edit" id="edit-status" ' . $disabled . '>Sửa</button>
                             <button class="actions--delete" id="delete-status" ' . $disabled . '>Xoá</button>
                        </td>
                        </tr>';
                        }
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<tr>';
                            echo '<td class="id">'  . $row['id'] . '</td>';
                            echo '<td class="name">' . $row['name'] . '</td>';
                            echo '<td class="email">' . $row['email'] . '</td>';
                            echo '<td class="number_phone">' . $row['number_phone'] . '</td>';
                            if ($row['status'] == 1) {
                                echo '<td class="status">Đang hoạt động</td>';
                            } else {
                                echo '<td class="status">Không hoạt động</td>';
                            }

                            // Kiểm tra status để quyết định ẩn nút
                            if ($row['status'] == 0) {
                                echo '<td class="actions">
                                        <span>Không thể sửa/Xoá</span>
                                      </td>';
                            } else {
                                echo '<td class="actions">
                                        <button class="actions--edit" id="edit-status">Sửa</button>
                                        <button class="actions--delete" id="delete-status">Xoá</button>
                                      </td>';
                            }
                            echo '</tr>';
                        }
                        echo '
                    </tbody>
                    </table>
                    </div>';
                    }
                    break;
                case "goodsreceipts": {
                        echo '
                            <div class="table__wrapper">
                                <table id="content-product">
                                    <thead class="menu">
                                        <tr>
                                            <th data-order="id">Mã đơn nhập</th>
                                            <th>Tên nhà cung cấp</th>
                                            <th>Người nhập đơn</th>
                                            <th>Tổng tiền</th>
                                            <th>Ngày tạo</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-content" id="content">
                        ';

                        while ($row = mysqli_fetch_array($result)) {
                            echo '<tr>';
                            echo '<td class="id">'  . $row['id'] . '</td>';
                            // Truy vấn tên nhà cung cấp từ bảng 'suppliers'
                            $sup_sql = "SELECT s.name
                                        FROM goodsreceipts gr
                                        INNER JOIN goodsreceipt_details gd ON gd.goodsreceipt_id = gr.id
                                        INNER JOIN products p ON p.id = gd.product_id
                                        INNER JOIN suppliers s ON s.id = p.supplier_id
                                        WHERE gr.id = '" . $row['id'] . "'
                                        LIMIT 1";
                            $sup_result = $database->query($sup_sql);
                            $supplier = mysqli_fetch_array($sup_result);
                            $supplier_name = ($supplier) ? $supplier['name'] : '';

                            echo '<td class="supplierName">' . $supplier_name . '</td>';

                            echo '<td class="staff_id">' . $row['staff_id'] . '</td>';
                            echo '<td class="total_price">';
                            echo  money_format($row['total_price']) . '</td>';
                            echo '<td class="date_create">' . $row['date_create'] . '</td>';

                            echo '<td class="actions">
                                    <button class="actions--edit">Xem chi tiết</button>
                                </td>
                            </tr>';
                        }

                        echo '
                            </tbody>
                        </table>
                    </div>';
                    }
                    break;


                case "orders": {
                        echo '
                    <div class="table__wrapper">
                        <table id="content-product">
                            <thead class="menu">
                                <tr>
                                    <th data-order="id">Mã đơn</th>
                                    <th data-order="customer_id">Mã KH</th>
                                    <th data-order="staff_id">Mã NV</th>
                                    <th data-order="date_create">Ngày tạo</th>
                                    <th data-order="total_price">Tổng giá</th>
                                    <th>Địa chỉ giao</th>
                                    <th>Trạng thái</th>
                                    <th>Mã giảm giá</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="table-content" id="content">
                    ';
                        while ($row = mysqli_fetch_array($result)) {
                            $sql_customer = "SELECT di.user_id
                                                FROM orders o
                                                INNER JOIN delivery_infoes di ON o.delivery_info_id = di.user_info_id
                                                WHERE o.id = '$row[0]'";
                            $result_customer = $database->query($sql_customer);
                            $row_customer = mysqli_fetch_array($result_customer);

                            echo '<tr>';
                            echo '<td class="order_id">' . $row[0] . '</td>';
                            echo '<td class="customer_id">' . $row_customer[0] . '</td>';
                            echo '<td class="staff_id">' . $row[1] . '</td>';
                            echo '<td class="date-update">' . $row[3] . '</td>';

                            echo '<td class="total_price">' . money_format($row['4'])  . '</td>';

                            $sql_address = 'SELECT * from delivery_infoes WHERE user_info_id="' . $row[2] . '"';
                            $result_address = $database->query($sql_address);
                            $row_address = mysqli_fetch_array($result_address);

                            echo '<td class="address">' . $row_address['address'] . ', ' . $row_address['ward'] . ', ' . $row_address['district'] . ', ' . $row_address['city'] . '</td>';

                            $sql_status = 'SELECT * from order_statuses WHERE id="' . $row[5] . '"';
                            $result_status  = $database->query($sql_status);
                            $row_status = mysqli_fetch_array($result_status);

                            echo '<td class="status">' . $row_status['name'] . '</td>';
                            echo '<td class="discount_code">' . $row['discount_code'] . '</td>';
                            echo '<td class="actions">
                        <button class="actions--view">Chi tiết</button>
                        <!-- <button class="actions--delete">Xoá</button> -->
                        </td>
                        </tr>';
                        }
                        echo '
                    </tbody>
                    </table>
                    </div>';
                    }
                    break;
                case "accounts": {
                        echo '
                    <div class="table__wrapper">
                        <table id="content-product">
                            <thead class="menu">
                            <tr>
                                <th  data-order="username">Mã người dùng</th>
                                <th>Loại tài khoản</th>
                                <th>Trạng thái tài khoản</th>
                                <th>Hành động</th>
                            </tr>
                            </thead>
                            <tbody class="table-content" id="content">
                    ';

                        while ($row = mysqli_fetch_array($result)) {
                            echo '<tr>';
                            echo '<td class="id">'  . $row['username'] . '</td>';

                            $sql_role = 'SELECT * from roles WHERE id="' . $row['role_id'] . '"';
                            $row_role = mysqli_fetch_array($database->query($sql_role));

                            echo '<td class="type" value="staff">' . $row_role['name'] . '</td>';
                            $sql_status = 'SELECT * from accounts WHERE username="' . $row['username'] . '"';
                            $row_status = mysqli_fetch_array($database->query($sql_status));

                            $status = "";
                            if ($row['status'] == 1) $status = "Hoạt động";
                            if ($row['status'] == 0) $status = "Không hoạt động";

                            echo '<td class="status" value="active">' . $status . '</td>';
                            if ($row['username'] == "admin") {
                                echo '<td class="actions">
                                <button class="actions--pass">Đổi mật khẩu</button>
                                </td>
                            </tr>';
                            } else {
                                echo '<td class="actions">
                            <button class="actions--edit">Sửa</button>
                            <button class="actions--pass">Đổi mật khẩu</button>
                            </td>
                        </tr>';
                            }
                        }
                        echo '
                    </tbody>
                    </table>
                    </div>';
                    }
                    break;
                case "authors": {
                        echo '
                    <div class="table__wrapper">
                    <table id="content-product">
                        <thead class="menu">
                            <tr>
                            <th data-order="id">Mã tác giả</th>
                            <th data-order="name">Tên tác giả</th>
                            <th  data-order="email">Email tác giả</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="table-content" id="content">

                    ';

                        while ($row = mysqli_fetch_array($result)) {
                            echo '<tr>';
                            echo '<td class="id">'  . $row['id'] . '</td>';
                            echo '<td class="name">' . $row['name'] . '</td>';
                            echo '<td class="email">' . $row['email'] . '</td>';
                            if ($row['status'] == 1) {
                                echo '<td class="status" >Đang hoạt động</td>';
                            } else {
                                echo '<td class="status" >Không hoạt động</td>';
                            }
                            echo '<td class="actions">
                            <button class="actions--edit">Sửa</button>';
                            if ($row['status'] == 1)
                                echo '<button class="actions--delete">Xoá</button>

                        </td>
                        </tr>';
                        }
                        echo '
                    </tbody>
                    </table>
                    </div>';
                    }
                    break;
                case "publishers": {
                        echo '
                    <div class="table__wrapper">
                    <table id="content-product">
                        <thead class="menu">
                            <tr>
                            <th  data-order="id">Mã NXB</th>
                            <th  data-order="name">Tên NXB</th>
                            <th  data-order="email">Email NXB</th>
                            <th>Trạng Thái</th>
                            <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="table-content" id="content">

                    ';

                        while ($row = mysqli_fetch_array($result)) {
                            echo '<tr>';
                            echo '<td class="id">'  . $row['id'] . '</td>';
                            echo '<td class="name">' . $row['name'] . '</td>';
                            echo '<td class="email">' . $row['email'] . '</td>';
                            if ($row['status'] == 1) {
                                echo '<td class="status" >Đang hoạt động</td>';
                            } else {
                                echo '<td class="status" >Không hoạt động</td>';
                            }
                            echo '<td class="actions">
                            <button class="actions--edit">Sửa</button>
                            <button class="actions--delete">Xoá</button>
                        </td>
                        </tr>';
                        }

                        while ($row = mysqli_fetch_array($result)) {
                            echo '<tr>';
                            echo '<td class="id">'  . $row['id'] . '</td>';
                            echo '<td class="name">' . $row['name'] . '</td>';
                            echo '<td class="email">' . $row['email'] . '</td>';

                            if ($row['status'] == 1) {
                                echo '<td class="status">Đang hoạt động</td>';
                                echo '<td class="actions">
                                        <button class="actions--edit">Sửa</button>
                                        <button class="actions--delete">Xoá</button>
                                      </td>';
                            } else {
                                echo '<td class="status">Không hoạt động</td>';
                                echo '<td class="actions">
                                        <span>Không thể sửa/Xoá</span>
                                      </td>';
                            }

                            echo '</tr>';
                        }
                        echo '
                    </tbody>
                    </table>
                    </div>';
                    }
                    break;

                case "categories": {
                        echo '
                    <div class="table__wrapper">
                    <table id="content-product">
                        <thead class="menu">
                            <tr>
                            <th  data-order="id">Mã thể loại</th>
                            <th data-order="name">Tên thể loại</th>
                            <th>Số lượng sách</th>
                            <th>Trạng thái</th>

                            <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="table-content" id="content">

                    ';

                        while ($row = mysqli_fetch_array($result)) {
                            echo '<tr>';
                            echo '<td class="id">'  . $row['id'] . '</td>';
                            echo '<td class="name">' . $row['name'] . '</td>';
                            $sql_amount =
                                "SELECT SUM(quantity) as total
                                FROM `products`  as p
                                INNER JOIN category_details as cd ON cd.category_id = " . $row['id'] . "
                                WHERE p.id = cd.product_id";
                            $result_amount = $database->query($sql_amount);
                            $amount = mysqli_fetch_array($result_amount)['total'];
                            if ($amount !== null) {
                                echo '<td class="amount">' . $amount . '</td>';
                            } else {
                                echo '<td class="amount">' . 0 . '</td>';
                            }



                            if ($row['status'] == 1) {
                                echo '<td class="status">Đang hoạt động</td>';
                                echo '<td class="actions">
                                        <button class="actions--edit">Sửa</button>
                                        <button class="actions--delete">Xoá</button>
                                      </td>';
                            } else {
                                echo '<td class="status">Không hoạt động</td>';
                                echo '<td class="actions">
                                        <span>Không thể sửa/Xoá</span>
                                      </td>';
                            }

                            echo '</tr>';
                        }
                        echo '
                    </tbody>
                    </table>
                    </div>';
                    }
                    break;
                case "discounts": {
                        echo '
                    <div class="table__wrapper">
                    <table id="content-product">
                        <thead class="menu">
                            <tr>
                            <th data-order="discount_code">Tên mã giảm giá</th>
                            <th>Loại mã giảm giá</th>
                            <th data-order="discount_value">Giá trị mã giảm giá</th>
                            <th data-order="start_date">Ngày bắt đầu</th>
                            <th data-order="end_date">Ngày kết thúc</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="table-content" id="content">
                    ';

                        while ($row = mysqli_fetch_array($result)) {
                            echo '<tr>';
                            echo '<td class="discount_code">'  . $row['discount_code'] . '</td>';
                            echo '<td class="type">' . $row['type'] . '</td>';
                            if ($row['type'] == "PR") {
                                echo '<td class="discount_value">' . $row['discount_value'] . '%</td>';
                            } else {
                                echo '<td class="discount_value">' . $row['discount_value'] . 'đ</td>';
                            }

                            echo '<td class="start_date">' . $row['start_date'] . '</td>';
                            echo '<td class="end_date">' . $row['end_date'] . '</td>';
                            if ($row['status'] == 1) {
                                echo '<td class="status" >Đang hoạt động</td>';
                            } else {
                                echo '<td class="status" >Không hoạt động</td>';
                            }
                            // echo '<td class="status">'.$row['status'].'</td>';
                            echo '<td class="actions">';
                            if ($row['status'] == 1) {
                                echo '<button class="actions--delete">Xoá</button>
                                <button class="actions--edit">Sửa</button>';
                            } else {
                                echo '<button disabled style="cursor: none; pointer-events: none; user-select: text;">Xoá</button>
                                <button disabled style="cursor: none; pointer-events: none; user-select: text;">Sửa</button>';
                            }
                            echo '</td>
                        </tr>';
                        }
                        echo '
                    </tbody>
                    </table>
                    </div>';
                    }
                    break;
                case "functions": {
                        echo '
                    <div class="table__wrapper">
                    <table id="content-product">
                        <thead class="menu">
                            <tr>
                            <th data-order="id">Mã quyền</th>
                            <th data-order="name">Tên quyền</th>
                            </tr>
                        </thead>
                        <tbody class="table-content" id="content">

                    ';

                        while ($row = mysqli_fetch_array($result)) {
                            echo '<tr>';
                            echo '<td class="id">'  . $row['id'] . '</td>';
                            echo '<td class="name">' . $row['name'] . '</td>';
                            // echo '<td class="status">'.$row['status'].'</td>';
                            // echo '<td class="date-create">'.$row['create_date'].'</td>';
                            // echo '<td class="date-delete">'.$row['delete_date'].'</td>';
                            // echo '<td class="date-update">'.$row['update_date'].'</td>';
                            //     echo '<td class="actions">
                            //     <button class="actions--edit">Sửa</button>
                            // </td>
                            echo '</tr>';
                        }
                        echo '
                    </tbody>
                    </table>
                    </div>';
                    }
                    break;
            }
            echo '<div class="pagination">';

            $page_number = ceil($this->getTotalRecords() / $this->number_of_item);
            if ($this->current_page > 1) {
                echo "<span class='pag-pre'>&laquo;</span>";
                echo "<span class='pag'>1</span>";
            }
            if ($this->current_page > 2) {
                echo "<span class='pag'>2</span>";
            }
            if ($this->current_page > 4) echo sprintf("...<span class='pag'>%u</span>", $this->current_page - 1);
            elseif ($this->current_page > 3) echo "<span class='pag'>3</span>";
            echo "<span class='active'>$this->current_page</span>";
            if ($this->current_page < $page_number) echo sprintf("<span class='pag'>%u</span>", $this->current_page + 1);
            if ($this->current_page + 3 < $page_number) echo "...";
            if ($this->current_page + 2 < $page_number) echo sprintf("<span class='pag'>%u</span>", $page_number - 1);
            if ($this->current_page + 1 < $page_number) echo "<span class='pag'>$page_number</span>";

            if ($this->current_page < $page_number) echo "<span class='pag-con'>&raquo;</span>";

            echo '</div>';
        } else {
            switch ($this->table) {
                case "products":
                    echo "<div id='zero-item'><h2>Không có sản phẩm nào</h2></div>";
                    break;
                case "orders":
                    echo "<div id='zero-item'><h2>Không có đơn hàng nào</h2></div>";
                    break;
                case "accounts":
                    echo "<div id='zero-item'><h2>Không có tài khoản nào</h2></div>";
                    break;
                case "authors":
                    echo "<div id='zero-item'><h2>Không có tác giả nào</h2></div>";
                    break;
                case "publishers":
                    echo "<div id='zero-item'><h2>Không có NBX nào</h2></div>";
                    break;
                case "suppliers":
                    echo "<div id='zero-item'><h2>Không có nhà cung cấp nào</h2></div>";
                    break;
                case "discounts":
                    echo "<div id='zero-item'><h2>Không có mã khuyến mãi nào</h2></div>";
                    break;
                case "categories":
                    echo "<div id='zero-item'><h2>Không có thể loại nào</h2></div>";
                    break;
                case "goodsreceipts":
                    echo "<div id='zero-item'><h2>Không có đơn nhập hàng nào</h2></div>";
                    break;
                case "functions":
                    echo "<div id='zero-item'><h2>Không có quyền nào</h2></div>";
                    break;
            }
        }
        $database->close();
    }
}

function getFilterSQL($table, $data)
{
    switch ($table) {
        case 'products':
            return getProductFilterSQL($data);
            break;
        case 'authors':
            return getAuthorFilterSQL($data);
            break;
        case 'orders':
            return getOrderFilterSQL($data);
            break;
        case 'categories':
            return getCategoryFilterSQL($data);
            break;
        case 'discounts':
            return getDiscountFilterSQL($data);
            break;
        case 'publishers':
            return getPublisherFilterSQL($data);
            break;
        case 'suppliers':
            return getSupplierFilterSQL($data);
            break;
        case 'functions':
            return getRoleFilterSQL($data);
            break;
        case 'accounts':
            return getUserFilterSQL($data);
            break;
        case 'goodsreceipts':
            return getReceiptFilterSQL($data);
            break;
    }
}
function getProductFilterSQL($data)
{
    $filter = "";
    $innerjoin = "";
    if (!empty($data)) {
        if (!empty($data['name'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . "`name` LIKE '%" . $data['name'] . "%'";
        }
        if (!empty($data['id'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . " id = " . $data['id'];
        }
        if (!empty($data['price_start'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . " price >= " . $data['price_start'];
        }
        if (!empty($data['price_end'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . " price <= " . $data['price_end'];
        }
        if ((isset($data['status']) && $data['status'] == 0) || !empty($data['status']) && $data['status'] != -1) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . " status=" . $data['status'];
        }
        if (!empty($data['category'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . " cd.product_id = products.id ";
            $innerjoin = $innerjoin . "INNER JOIN category_details	as cd ON cd.category_id =' " . $data['category'] . "'";
        }
        if (!empty($data['date_type'])) {
            if (!empty($data['date_start'])) {
                if ($filter != "") $filter = $filter . " AND ";
                $filter = $filter . " `" . $data['date_type'] . "` >= '" . $data['date_start'] . "'";
            }
            if (!empty($data['date_end'])) {
                if ($filter != "") $filter = $filter . " AND ";
                $filter = $filter . " `" . $data['date_type'] . "` <= '" . $data['date_end'] . "'";
            }
        }
        if ($filter != "") $filter = "WHERE " . $filter;
    }
    return $innerjoin . $filter;
}
function getAuthorFilterSQL($data)
{
    $filter = "";
    if (!empty($data)) {
        if (!empty($data['author_name'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . "`name` LIKE '%" . $data['author_name'] . "%'";
        }
        if (!empty($data['author_id'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . " id = " . $data['author_id'];
        }
        if (!empty($data['author_email'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . "`email` LIKE '%" . $data['author_email'] . "%'";
        }
        if (!empty($data['author_status'])) {
            if ($filter != "") $filter = $filter . " AND ";
            if ($data['author_status'] == "active") {

                $filter = $filter . "status = 1 ";
            } elseif ($data['author_status'] == "inactive") {
                $filter = $filter . "status = 0 ";
            }
        }


        if ($filter != "") $filter = "WHERE " . $filter;
    }
    return  $filter;
}
function getReceiptFilterSQL($data)
{
    $filter = "";
    $innerjoin = "";
    if (!empty($data)) {
        if (!empty($data['supplierName'])) {
            // Lấy tên nhà cung cấp từ dữ liệu
            $supplierName = $data['supplierName'];
            $filter = $filter . " s.name LIKE '%" . $supplierName . "%'";
            $innerjoin = $innerjoin . " inner join goodsreceipt_details gd on gd.goodsreceipt_id = goodsreceipts.id
                                        inner join products p on p.id = gd. product_id
                                        inner join suppliers s on s.id = p.supplier_id ";
        }
        if (!empty($data['id'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . " goodsreceipts.id = " . $data['id'];
        }
        if (!empty($data['staff_id'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . "`staff_id` LIKE '%" . $data['staff_id'] . "%'";
        }
        if (!empty($data['price_start'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . " total_price >= " . $data['price_start'];
        }
        if (!empty($data['price_end'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . " total_price <= " . $data['price_end'];
        }
        if (!empty($data['date_start'])) {
            if ($filter != "") $filter .= " AND ";
            $filter .= "date_create >= '" . $data['date_start'] . "'";
        }
        if (!empty($data['date_end'])) {
            if ($filter != "") $filter .= " AND ";
            $filter .= "date_create <= '" . $data['date_end'] . "'";
        }
        if ($filter != "") $filter = "WHERE " . $filter;
    }
    return  $innerjoin . $filter;
}

function getCategoryFilterSQL($data)
{
    $filter = "";
    if (!empty($data)) {
        if (!empty($data['category_name'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . "`name` LIKE '%" . $data['category_name'] . "%'";
        }
        if (!empty($data['category_id'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . " id = " . $data['category_id'];
        }
        if (!empty($data['category_status'])) {
            if ($filter != "") $filter = $filter . " AND ";
            if ($data['category_status'] == "active") {

                $filter = $filter . "status = 1 ";
            } elseif ($data['category_status'] == "inactive") {
                $filter = $filter . "status = 0 ";
            }
        }



        if ($filter != "") $filter = "WHERE " . $filter;
    }
    return  $filter;
}

function getSupplierFilterSQL($data)
{
    $filter = "";
    if (!empty($data)) {
        if (!empty($data['supplier_name'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . "`name` LIKE '%" . $data['supplier_name'] . "%'";
        }
        if (!empty($data['supplier_id'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . " id = " . $data['supplier_id'];
        }
        if (!empty($data['supplier_status'])) {
            if ($filter != "") $filter = $filter . " AND ";
            if ($data['supplier_status'] == "active") {

                $filter = $filter . "status = 1 ";
            } elseif ($data['supplier_status'] == "inactive") {
                $filter = $filter . "status = 0 ";
            }
        }

        if (!empty($data['supplier_date_type'])) {
            if (!empty($data['supplier_date_start'])) {
                if ($filter != "") $filter = $filter . " AND ";
                $filter = $filter . " `" . $data['supplier_date_type'] . "` >= '" . $data['supplier_date_start'] . "'";
            }
            if (!empty($data['supplier_date_end'])) {
                if ($filter != "") $filter = $filter . " AND ";
                $filter = $filter . " `" . $data['supplier_date_type'] . "` <= '" . $data['supplier_date_end'] . "'";
            }
        }

        if ($filter != "") $filter = "WHERE " . $filter;
    }
    return  $filter;
}

function getPublisherFilterSQL($data)
{
    $filter = "";
    if (!empty($data)) {
        if (!empty($data['publisher_name'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . "`name` LIKE '%" . $data['publisher_name'] . "%'";
        }
        if (!empty($data['publisher_id'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . " id = " . $data['publisher_id'];
        }
        if (!empty($data['publisher_email'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . "`email` LIKE '%" . $data['publisher_email'] . "%'";
        }
        if (!empty($data['publisher_status'])) {
            if ($filter != "") $filter = $filter . " AND ";
            if ($data['publisher_status'] == "active") {

                $filter = $filter . "status = 1 ";
            } elseif ($data['publisher_status'] == "inactive") {
                $filter = $filter . "status = 0 ";
            }
        }

        if ($filter != "") $filter = "WHERE " . $filter;
    }
    return $filter;
}
function getRoleFilterSQL($data)
{
    $filter = "";
    if (!empty($data)) {
        if (!empty($data['role_name'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . "`name` LIKE '%" . $data['role_name'] . "%'";
        }
        if (!empty($data['role_id'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . " id = " . $data['role_id'];
        }
        if (!empty($data['role_status'])) {
            if ($filter != "") $filter = $filter . " AND ";
            if ($data['role_status'] == "active") {

                $filter = $filter . "status = 1 ";
            } else if ($data['role_status'] == "inactive") {
                $filter = $filter . "status = 0 ";
            }
        }

        if ($filter != "") $filter = "WHERE " . $filter;
    }
    return  $filter;
}
function getDiscountFilterSQL($data)
{
    $filter = "";
    if (!empty($data)) {
        if (!empty($data['discount_name'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . "`discount_code` LIKE '%" . $data['discount_name'] . "%'";
        }
        if (!empty($data['discount_status'])) {
            if ($filter != "") $filter = $filter . " AND ";
            if ($data['discount_status'] == "active") {

                $filter = $filter . "status = 1 ";
            } elseif ($data['discount_status'] == "inactive") {
                $filter = $filter . "status = 0 ";
            }
        }



        if ($filter != "") $filter = "WHERE " . $filter;
    }
    return  $filter;
}
function getUserFilterSQL($data)
{
    $filter = "";
    if (!empty($data)) {
        if (!empty($data['user_id'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . "`username` LIKE '%" . $data['user_id'] . "%'";
        }
        if (!empty($data['user_role'])) {
            if ($filter != "") $filter = $filter . " AND ";
            $filter = $filter . " role_id = " . $data['user_role'];
        }
        if (!empty($data['user_status'])) {
            if ($filter != "") $filter = $filter . " AND ";
            if ($data['user_status'] == "active") {
                $filter = $filter . "status = 1 ";
            } else if ($data['user_status'] == "inactive") {
                $filter = $filter . "status = 0 ";
            }
        }

        if ($filter != "") $filter = "WHERE " . $filter;
    }
    return  $filter;
}


// function getOrderFilterSQL($data)
// {
//     $filter = "";
//     $join = "";

//     if (!empty($data)) {
//         if (!empty($data['id_customer'])) {
//             $join = " INNER JOIN delivery_infoes di ON di.user_info_id = o.delivery_info_id";
//             if ($filter != "") $filter .= " AND ";
//             $filter = $filter . "di.user_id LIKE '%" . $data['id_customer'] . "%'";
//         }
//         if (!empty($data['id_staff'])) {
//             if ($filter != "") $filter .= " AND ";
//             $filter = $filter . "`staff_id` LIKE '%" . $data['id_staff'] . "%'";
//         }
//         if (!empty($data['id_Order'])) {
//             if ($filter != "") $filter .= " AND ";
//             $filter .= "id = " . $data['id_Order'];
//         }

//         if (!empty($data['Order_status']) && $data['Order_status']!="all") {
//             if ($filter != "") $filter = $filter . " AND ";
//             $filter = $filter . "status_id = ".$data['Order_status'] ;


//         }
//         if (!empty($data['date_begin'])) {
//             if ($filter != "") $filter .= " AND ";
//             $filter .= "date_create >= '" . $data['date_begin'] . "'";
//         }
//         if (!empty($data['date_end'])) {
//             if ($filter != "") $filter .= " AND ";
//             $filter .= "date_create <= '" . $data['date_end'] . "'";
//         }

//         if ($filter != "") $filter = " WHERE " . $filter;
//     }
//     return $filter;
// }

function getOrderFilterSQL($data)
{
    $filter = "";  // Biến lưu trữ câu lệnh WHERE
    $join = "";    // Biến lưu trữ câu lệnh JOIN

    if (!empty($data)) {
        // Điều kiện kiểm tra và thiết lập JOIN nếu có id_customer
        if (!empty($data['id_customer'])) {
            // Thêm JOIN để kết nối với bảng delivery_infoes
            $join = " INNER JOIN delivery_infoes di ON di.user_info_id = orders.delivery_info_id";

            if ($filter != "") $filter .= " AND ";
            $filter .= "di.user_id LIKE '%" . $data['id_customer'] . "%'";
        }

        // Điều kiện lọc cho staff_id
        if (!empty($data['id_staff'])) {
            if ($filter != "") $filter .= " AND ";
            $filter .= "orders.staff_id LIKE '%" . $data['id_staff'] . "%'";
        }

        // Điều kiện lọc theo id_Order
        if (!empty($data['id_Order'])) {
            if ($filter != "") $filter .= " AND ";
            $filter .= "orders.id = " . $data['id_Order'];
        }

        // Điều kiện lọc theo Order_status
        if (!empty($data['Order_status']) && $data['Order_status'] != "all") {
            if ($filter != "") $filter .= " AND ";
            $filter .= "orders.status_id = " . $data['Order_status'];
        }

        // Điều kiện lọc theo date_begin
        if (!empty($data['date_begin'])) {
            if ($filter != "") $filter .= " AND ";
            $filter .= "orders.date_create >= '" . $data['date_begin'] . "'";
        }

        // Điều kiện lọc theo date_end
        if (!empty($data['date_end'])) {
            if ($filter != "") $filter .= " AND ";
            $filter .= "orders.date_create <= '" . $data['date_end'] . "'";
        }
    }

    // Kết hợp JOIN và WHERE nếu cần
    if ($filter != "") $filter = " WHERE " . $filter;

    // Trả về chuỗi JOIN và WHERE kết hợp để hàm render() sử dụng
    return $join . $filter;
}


function money_format($money)
{
    if ($money == 0)  return  "0&#8363;";
    $formated = "";
    while ($money > 0) {
        $formated = substr("$money", -3, 3) . '.' . $formated;
        $money = substr("$money", 0, -3);
    }

    return  trim($formated, '. ') . "&#8363;";
}
