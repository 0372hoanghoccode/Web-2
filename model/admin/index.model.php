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
    $formated = "";
    while ($money > 0) {
        $formated = substr("$money", -3, 3) . '.' . $formated;
        $money = substr("$money", 0, -3);
    }
    return trim($formated, '. ') . "₫";
}

function getStats($date_start, $date_end)
{
    $database = new connectDB();
    if (!$database->conn) {
        echo "<div id='zero-item'><h2>Lỗi kết nối cơ sở dữ liệu.</h2></div>";
        return;
    }

    // Truy vấn để lấy danh sách khách hàng dựa trên user_id từ delivery_infoes
    $sql = "SELECT
                a.username,
                di.user_id,
                SUM(od.quantity) AS total_quantity
            FROM
                accounts a
            INNER JOIN
                delivery_infoes di ON a.username = di.user_id
            INNER JOIN
                orders o ON di.user_info_id = o.delivery_info_id
            INNER JOIN
                order_details od ON o.id = od.order_id
            WHERE
                o.status_id = 5
                AND o.date_create BETWEEN ? AND DATE_ADD(?, INTERVAL 0 DAY)
            GROUP BY
                a.username, di.user_id
            HAVING
                total_quantity > 0";

    $stmt = $database->conn->prepare($sql);
    if ($stmt === false) {
        echo "<div id='zero-item'><h2>Lỗi truy vấn: " . htmlspecialchars($database->conn->error) . "</h2></div>";
        $database->close();
        return;
    }

    $stmt->bind_param("ss", $date_start, $date_end);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<div id='zero-item'><h2>Không có khách hàng nào mua hàng trong khoảng thời gian này.</h2></div>";
        $stmt->close();
        $database->close();
        return;
    }

    // Hiển thị danh sách khách hàng
    while ($row = $result->fetch_assoc()) {
        echo '<div class="thongke">';
        echo '<h3 class="sanpham">' . htmlspecialchars($row['username']) . '</h3>';
        echo '<button type="button" class="chitietbtn" data-id="' . htmlspecialchars($row['username']) . '">Chi tiết</button>';
        echo '</div>';
    }

    $stmt->close();
    $database->close();
}

function getStatDetails($username, $date_start, $date_end, $order, $type)
{
    $database = new connectDB();
    if (!$database->conn) {
        echo "<div id='zero-item'><h2>Lỗi kết nối cơ sở dữ liệu.</h2></div>";
        return;
    }

    // Truy vấn để lấy chi tiết sản phẩm đã bán của khách hàng
    $sql = "SELECT
                p.id AS product_id,
                p.name,
                p.image_path,
                p.price AS original_price,
                SUM(od.quantity) AS total_quantity_sold
            FROM
                products p
            INNER JOIN
                order_details od ON p.id = od.product_id
            INNER JOIN
                orders o ON od.order_id = o.id
            INNER JOIN
                delivery_infoes di ON o.delivery_info_id = di.user_info_id
            INNER JOIN
                accounts a ON di.user_id = a.username
            WHERE
                a.username = ?
                AND o.status_id = 5
                AND o.date_create BETWEEN ? AND DATE_ADD(?, INTERVAL 0 DAY)
            GROUP BY
                p.id, p.name, p.image_path, p.price
            ORDER BY p.id ASC";

    $stmt = $database->conn->prepare($sql);
    if ($stmt === false) {
        echo "<div id='zero-item'><h2>Lỗi truy vấn: " . htmlspecialchars($database->conn->error) . "</h2></div>";
        $database->close();
        return;
    }

    $stmt->bind_param("sss", $username, $date_start, $date_end);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<div id='zero-item'><h2>Khách hàng này không mua sản phẩm nào trong khoảng thời gian này.</h2></div>";
        $stmt->close();
        $database->close();
        return;
    }

    // Mảng để lưu chi tiết sản phẩm
    $products = [];
    $total_revenue = 0;
    $total_quantity = 0;

    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];

        // Tính doanh thu có áp dụng giảm giá
        $sql_orders = "SELECT
                        o.id AS order_id,
                        o.discount_code,
                        d.type AS discount_type,
                        d.discount_value,
                        SUM(od.quantity) AS quantity_in_order
                    FROM
                        order_details od
                    INNER JOIN
                        orders o ON od.order_id = o.id
                    INNER JOIN
                        delivery_infoes di ON o.delivery_info_id = di.user_info_id
                    LEFT JOIN
                        discounts d ON o.discount_code = d.discount_code
                    WHERE
                        od.product_id = ?
                        AND di.user_id = ?
                        AND o.status_id = 5
                        AND o.date_create BETWEEN ? AND DATE_ADD(?, INTERVAL 0 DAY)
                    GROUP BY
                        o.id, o.discount_code, d.type, d.discount_value";

        $stmt_orders = $database->conn->prepare($sql_orders);
        if ($stmt_orders === false) {
            echo "<div id='zero-item'><h2>Lỗi truy vấn: " . htmlspecialchars($database->conn->error) . "</h2></div>";
            $stmt->close();
            $database->close();
            return;
        }
        $stmt_orders->bind_param("isss", $product_id, $username, $date_start, $date_end);
        $stmt_orders->execute();
        $orders_result = $stmt_orders->get_result();

        $product_revenue = 0;
        while ($order = $orders_result->fetch_assoc()) {
            $quantity_in_order = $order['quantity_in_order'];
            $discounted_price = $row['original_price'];

            if ($order['discount_code'] && $order['discount_type']) {
                if ($order['discount_type'] == 'PR') {
                    $discounted_price = round($row['original_price'] - ($row['original_price'] * $order['discount_value'] / 100), 0);
                } elseif ($order['discount_type'] == 'AR') {
                    $sql_total_order_qty = "SELECT SUM(quantity) AS total_qty FROM order_details WHERE order_id = ?";
                    $stmt_total_order_qty = $database->conn->prepare($sql_total_order_qty);
                    if ($stmt_total_order_qty === false) {
                        echo "<div id='zero-item'><h2>Lỗi truy vấn: " . htmlspecialchars($database->conn->error) . "</h2></div>";
                        $stmt->close();
                        $stmt_orders->close();
                        $database->close();
                        return;
                    }
                    $stmt_total_order_qty->bind_param("i", $order['order_id']);
                    $stmt_total_order_qty->execute();
                    $total_order_qty_result = $stmt_total_order_qty->get_result();
                    $total_order_qty_row = $total_order_qty_result->fetch_assoc();
                    $total_order_qty = $total_order_qty_row['total_qty'] ?? 1;
                    $stmt_total_order_qty->close();

                    $discounted_price = round($row['original_price'] - ($order['discount_value'] / $total_order_qty), 0);
                }
            }

            $product_revenue += $discounted_price * $quantity_in_order;
        }
        $stmt_orders->close();

        $products[] = [
            'id' => $row['product_id'],
            'name' => $row['name'],
            'image_path' => $row['image_path'],
            'original_price' => $row['original_price'],
            'total_quantity_sold' => $row['total_quantity_sold'],
            'total_revenue' => $product_revenue
        ];

        $total_revenue += $product_revenue;
        $total_quantity += $row['total_quantity_sold'];
    }

    // Sắp xếp sản phẩm theo yêu cầu
    if (!empty($products)) {
        $key_values = array_column($products, $order);
        if ($type == "ASC") {
            array_multisort($key_values, SORT_ASC, $products);
        } else if ($type == "DESC") {
            array_multisort($key_values, SORT_DESC, $products);
        }
    }

    // Hiển thị bảng chi tiết
    echo '
    <table id="content-product">
    <thead class="menu">
        <tr>
            <th data-order="id">Mã SP</th>
            <th>Ảnh</th>
            <th data-order="name">Tên Sản Phẩm</th>
            <th data-order="original_price">Giá</th>
            <th data-order="total_revenue">Tổng doanh thu</th>
        </tr>
    </thead>
    <tbody class="table-content" id="content">';

    foreach ($products as $product) {
        echo '<tr>
            <td class="id">' . htmlspecialchars($product['id']) . '</td>
            <td class="image">
                <img src="../' . htmlspecialchars($product['image_path']) . '" alt="image not found" style="width: 50px; height: auto;">
            </td>
            <td class="name">' . htmlspecialchars($product['name']) . '</td>
            <td class="price">' . money_format($product['original_price']) . '</td>
            <td class="total">' . money_format($product['total_revenue']) . '</td>
        </tr>';
    }

    echo '<tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>' . money_format($total_revenue) . '</td>
    </tr>';

    echo '</tbody></table>';

    $stmt->close();
    $database->close();
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
