<?php
$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__) . $ds . '..') . $ds;
include_once("{$base_dir}connect.php");

function getRoleIdByUsernameModel($username)
{
    $database = new connectDB();
    if ($database->conn) {
        $sql = "SELECT * FROM accounts WHERE username = ?";
        $stmt = $database->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $database->close();
        return $result;
    } else {
        $database->close();
        return false;
    }
}

function getTotalIncome()
{
    $database = new connectDB();
    if ($database->conn) {
        $sql = "SELECT SUM(o.total_price) AS total_income FROM orders o WHERE o.status_id = 5";
        $result = $database->query($sql);
        $database->close();
        return $result;
    } else {
        $database->close();
        return false;
    }
}

function getTotalOrders()
{
    $database = new connectDB();
    if ($database->conn) {
        $sql = "SELECT COUNT(id) AS order_count FROM orders";
        $result = $database->query($sql);
        $database->close();
        return $result;
    } else {
        $database->close();
        return false;
    }
}

function getTotalProducts()
{
    $database = new connectDB();
    if ($database->conn) {
        $sql = "SELECT COUNT(id) AS product_count FROM products";
        $result = $database->query($sql);
        $database->close();
        return $result;
    } else {
        $database->close();
        return false;
    }
}

function getTotalAccounts()
{
    $database = new connectDB();
    if ($database->conn) {
        $sql = "SELECT COUNT(username) AS member_count FROM accounts";
        $result = $database->query($sql);
        $database->close();
        return $result;
    } else {
        $database->close();
        return false;
    }
}

function money_format($money)
{
    if ($money == 0) return "0₫";
    $formated = number_format($money, 0, ',', '.') . "₫";
    return $formated;
}

function getStats($date_start, $date_end)
{
    $database = new connectDB();
    if (!$database->conn) {
        echo "<div style='text-align: center; padding: 20px; background: #fff; border: 1px solid #ddd;'><h2>Lỗi kết nối cơ sở dữ liệu.</h2></div>";
        return;
    }

    $sql = "SELECT
                a.username,
                di.fullname,
                di.phone_number,
                di.address,
                di.city,
                di.district,
                di.ward,
                COUNT(o.id) AS order_count,
                SUM(o.total_price) AS total_purchase
            FROM
                accounts a
            INNER JOIN
                delivery_infoes di ON a.username = di.user_id
            INNER JOIN
                orders o ON di.user_info_id = o.delivery_info_id
            WHERE
                o.status_id = 5
                AND o.date_create BETWEEN ? AND ?
            GROUP BY
                a.username, di.fullname, di.phone_number, di.address, di.city, di.district, di.ward
            ORDER BY
                total_purchase DESC
            LIMIT 5";

    $stmt = $database->conn->prepare($sql);
    if ($stmt === false) {
        echo "<div style='text-align: center; padding: 20px; background: #fff; border: 1px solid #ddd;'><h2>Lỗi truy vấn: " . htmlspecialchars($database->conn->error) . "</h2></div>";
        $database->close();
        return;
    }

    $stmt->bind_param("ss", $date_start, $date_end);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<div style='text-align: center; padding: 20px; background: #fff; border: 1px solid #ddd;'><h2>Không có khách hàng nào mua hàng trong khoảng thời gian này.</h2></div>";
        $stmt->close();
        $database->close();
        return;
    }

    // Embed CSS
    echo '<style>
    /* Thiết lập biến màu sắc toàn cục */
    :root {
        --primary-color: #007bff;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --secondary-color: #6c757d;
        --background-color: #f8f9fa;
        --border-color: #dee2e6;
        --text-color: #333;
        --hover-bg: #e9ecef;
    }

    /* Reset cơ bản */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Typography */
    body, table {
        font-family: "Arial", sans-serif;
        font-size: 14px;
        color: var(--text-color);
    }

    /* Container chung */
    .stats-container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 15px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Bảng thống kê chính */
    .thongke-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background: #fff;
    }
    .thongke-table th, .thongke-table td {
        padding: 12px;
        border: 1px solid var(--border-color);
        text-align: left;
    }
    .thongke-table th {
        background: var(--primary-color);
        color: #fff;
        font-weight: 600;
    }
    .thongke-table tr:nth-child(even) {
        background: var(--background-color);
    }
    .thongke-table tr:hover {
        background: var(--hover-bg);
    }

    /* Nút chi tiết */
    .chitietbtn {
        padding: 8px 16px;
        background: var(--success-color);
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        transition: background 0.2s;
    }
    .chitietbtn:hover {
        background: #218838;
    }

    /* Thông tin khách hàng */
    .customer-details {
        background: var(--background-color);
        border: 1px solid var(--border-color);
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 6px;
    }
    .customer-details h3 {
        margin: 0 0 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-color);
        font-size: 18px;
    }
    .customer-details p {
        margin: 8px 0;
        line-height: 1.6;
    }

    /* Bảng đơn hàng */
    .orders-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    .orders-table th, .orders-table td {
        padding: 10px;
        border: 1px solid var(--border-color);
        text-align: left;
    }
    .orders-table th {
        background: var(--secondary-color);
        color: #fff;
        font-weight: 600;
    }
    .orders-table tr:nth-child(even) {
        background: var(--background-color);
    }

    /* Bảng sản phẩm */
    .order-products {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    .order-products th, .order-products td {
        padding: 10px;
        border: 1px solid var(--border-color);
        text-align: left;
    }
    .order-products th {
        background: #495057;
        color: #fff;
        font-weight: 600;
    }

    /* Liên kết xem chi tiết */
    .xemchitiet {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }
    .xemchitiet:hover {
        text-decoration: underline;
    }

    /* Thông báo lỗi */
    .error-message, .empty-message {
        text-align: center;
        padding: 20px;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        margin: 20px 0;
    }
    .error-message {
        color: var(--danger-color);
    }

    /* Ẩn/hiện chi tiết */
    .details-row {
        display: none;
    }
    .details-row.show {
        display: table-row;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .thongke-table, .orders-table, .order-products {
            font-size: 12px;
        }
        .thongke-table th, .thongke-table td,
        .orders-table th, .orders-table td,
        .order-products th, .order-products td {
            padding: 8px;
        }
        .chitietbtn {
            padding: 6px 12px;
            font-size: 12px;
        }
        .stats-container {
            margin: 10px;
            padding: 10px;
        }
    }
</style>';

    echo '<table class="thongke-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Họ và Tên</th>
                    <th>Số đơn hàng</th>
                    <th>Tổng mua</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>';

    $index = 1;
    while ($row = $result->fetch_assoc()) {
        $username = htmlspecialchars($row['username']);
        echo '<tr>
                <td>' . $index . '</td>
                <td>' . htmlspecialchars($row['fullname']) . '</td>
                <td>' . htmlspecialchars($row['order_count']) . '</td>
                <td>' . money_format($row['total_purchase']) . '</td>
                <td><button type="button" class="chitietbtn" data-username="' . $username . '">Xem chi tiết</button></td>
            </tr>';

        // Prepare customer details and order info placeholder
        echo '<tr class="details-row" id="details-' . $username . '">
                <td colspan="5" style="padding: 15px;">
                    <div class="customer-details">
                        <h3>Thông tin khách hàng</h3>
                        <p><strong>Tài khoản:</strong> ' . $username . '</p>
                        <p><strong>Họ tên:</strong> ' . htmlspecialchars($row['fullname']) . '</p>
                        <p><strong>Số điện thoại:</strong> ' . htmlspecialchars($row['phone_number']) . '</p>
                        <p><strong>Địa chỉ:</strong> ' . htmlspecialchars($row['address']) . ', ' .
                          htmlspecialchars($row['ward']) . ', ' .
                          htmlspecialchars($row['district']) . ', ' .
                          htmlspecialchars($row['city']) . '</p>
                    </div>
                    <div class="order-container">
                        <h3>Danh sách đơn hàng</h3>
                        <div id="orders-content-' . $username . '"></div>
                    </div>
                </td>
            </tr>';
        $index++;
    }

    echo '</tbody></table>';

    $stmt->close();
    $database->close();
}

function getUserOrderDetails($username, $date_start, $date_end)
{
    $database = new connectDB();
    if (!$database->conn) {
        return "<div class='error-message'>Lỗi kết nối cơ sở dữ liệu.</div>";
    }

    $sql = "SELECT
                o.id AS order_id,
                o.date_create,
                o.total_price,
                o.discount_code,
                di.fullname,
                di.phone_number,
                di.address,
                di.city,
                di.district,
                di.ward
            FROM
                orders o
            INNER JOIN
                delivery_infoes di ON o.delivery_info_id = di.user_info_id
            WHERE
                di.user_id = ?
                AND o.status_id = 5
                AND o.date_create BETWEEN ? AND ?
            ORDER BY
                o.date_create DESC";

    $stmt = $database->conn->prepare($sql);
    if ($stmt === false) {
        $database->close();
        return "<div class='error-message'>Lỗi truy vấn: " . htmlspecialchars($database->conn->error) . "</div>";
    }

    $stmt->bind_param("sss", $username, $date_start, $date_end);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        $database->close();
        return "<div class='empty-message'>Khách hàng này không có đơn hàng trong khoảng thời gian này.</div>";
    }

    $output = '<table class="orders-table">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày tạo</th>
                        <th>Mã giảm giá</th>
                        <th>Tổng tiền</th>
                        <th>Thông tin giao hàng</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>';

    $total_spending = 0;
    while ($order = $result->fetch_assoc()) {
        $order_id = $order['order_id'];
        $discount_code = $order['discount_code'] ? $order['discount_code'] : 'Không có';
        $delivery_info = htmlspecialchars($order['fullname']) . '<br>' .
                        htmlspecialchars($order['phone_number']) . '<br>' .
                        htmlspecialchars($order['address']) . ', ' .
                        htmlspecialchars($order['ward']) . ', ' .
                        htmlspecialchars($order['district']) . ', ' .
                        htmlspecialchars($order['city']);

        $output .= '<tr>
                        <td>' . htmlspecialchars($order_id) . '</td>
                        <td>' . htmlspecialchars($order['date_create']) . '</td>
                        <td>' . htmlspecialchars($discount_code) . '</td>
                        <td>' . money_format($order['total_price']) . '</td>
                        <td>' . $delivery_info . '</td>
                        <td><button class="xemchitiet" data-order="' . $order_id . '">Xem sản phẩm</button></td>
                    </tr>';

        // Container for order products
        $output .= '<tr id="products-row-' . $order_id . '" style="display:none;">
                        <td colspan="6" id="products-content-' . $order_id . '" style="padding:15px;">
                            <div class="loading">Đang tải...</div>
                        </td>
                    </tr>';

        $total_spending += $order['total_price'];
    }

    $output .= '<tr>
                    <td colspan="4" style="text-align: right;"><strong>Tổng cộng:</strong></td>
                    <td colspan="2"><strong>' . money_format($total_spending) . '</strong></td>
                </tr>';

    $output .= '</tbody></table>';

    $stmt->close();
    $database->close();
    return $output;
}

function getOrderProducts($order_id)
{
    $database = new connectDB();
    if (!$database->conn) {
        return "<div class='error-message'>Lỗi kết nối cơ sở dữ liệu.</div>";
    }

    $sql = "SELECT
                od.product_id,
                p.name AS product_name,
                od.quantity,
                od.price,
                (od.quantity * od.price) AS subtotal
            FROM
                order_details od
            INNER JOIN
                products p ON od.product_id = p.id
            WHERE
                od.order_id = ?";

    $stmt = $database->conn->prepare($sql);
    if ($stmt === false) {
        $database->close();
        return "<div class='error-message'>Lỗi truy vấn: " . htmlspecialchars($database->conn->error) . "</div>";
    }

    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        $database->close();
        return "<div class='empty-message'>Không tìm thấy sản phẩm nào trong đơn hàng này.</div>";
    }

    $output = '<table class="order-products">
                <thead>
                    <tr>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>';

    $total = 0;
    while ($product = $result->fetch_assoc()) {
        $output .= '<tr>
                        <td>' . htmlspecialchars($product['product_id']) . '</td>
                        <td>' . htmlspecialchars($product['product_name']) . '</td>
                        <td>' . htmlspecialchars($product['quantity']) . '</td>
                        <td>' . money_format($product['price']) . '</td>
                        <td>' . money_format($product['subtotal']) . '</td>
                    </tr>';
        $total += $product['subtotal'];
    }

    $output .= '<tr>
                    <td colspan="4" style="text-align: right;"><strong>Tổng cộng:</strong></td>
                    <td><strong>' . money_format($total) . '</strong></td>
                </tr>';

    $output .= '</tbody></table>';

    $stmt->close();
    $database->close();
    return $output;
}

function checkFunction($username, $function_id)
{
    $database = new connectDB();
    if ($database->conn) {
        $sql = "SELECT *
                FROM accounts a
                INNER JOIN function_details fd ON a.role_id = fd.role_id
                WHERE a.username = ? AND function_id = ?";
        $stmt = $database->conn->prepare($sql);
        $stmt->bind_param("si", $username, $function_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        echo $row["action"] ?? '';
        $stmt->close();
        $database->close();
    }
}
?>
