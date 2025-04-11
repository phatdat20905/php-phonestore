<?php include 'inc/header.php'; ?>

<?php
$login_check = Session::get('customer_login');
if ($login_check == false) {
    header('Location: login.php');
    exit;
}

$customer_id = Session::get('customer_id');
$order = new Order();

if (!isset($_GET['orderid']) || empty($_GET['orderid'])) {
    header('Location: order_history.php');
    exit;
}

$order_id = $_GET['orderid'];

// Kiểm tra đơn hàng có thuộc về khách hàng không
$check_order = $order->get_order_by_id_and_customer($order_id, $customer_id);
if (!$check_order || $check_order->num_rows == 0) {
    header('Location: order_history.php');
    exit;
}

$order_info = $check_order->fetch_assoc();
?>

<div class="main">
    <div class="content">
        <div class="cartpage">
            <h3>Chi tiết đơn hàng #<?php echo $order_info['order_code']; ?></h3>
            <?php if (isset($message)) echo '<span class="message">' . $message . '</span>'; ?>
            <table class="order-table">
                <thead>
                    <tr>
                        <th width="5%">STT</th>
                        <th width="25%">Tên sản phẩm</th>
                        <th width="15%">Hình ảnh</th>
                        <th width="15%">Giá</th>
                        <th width="10%">Số lượng</th>
                        <th width="15%">Thành tiền</th>
                        <th width="15%">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $get_order_details = $order->get_order_details($order_id);
                    if ($get_order_details && $get_order_details->num_rows > 0) {
                        $i = 0;
                        $total = 0;
                        while ($result = $get_order_details->fetch_assoc()) {
                            $i++;
                            $subtotal = $result['price'] * $result['quantity'];
                            $total += $subtotal;
                    ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $result['product_name']; ?></td>
                                <td><img src="admin/uploads/<?php echo $result['image']; ?>" alt="Ảnh sản phẩm" /></td>
                                <td><?php echo number_format($result['price'], 0, ',', '.') . ' VND'; ?></td>
                                <td><?php echo $result['quantity']; ?></td>
                                <td><?php echo $fm->format_currency($subtotal) . ' VND'; ?></td>
                                <td>
                                    <?php
                                    if ($order_info['status'] == 0) echo "Đang xử lý";
                                    elseif ($order_info['status'] == 1) echo "Đang giao";
                                    elseif ($order_info['status'] == 2) echo "Đã nhận";
                                    elseif ($order_info['status'] == 3) echo "Thành công";
                                    ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td colspan="5" style="text-align: right; font-weight: bold;">Tổng tiền:</td>
                            <td><?php echo $fm->format_currency($total) . ' VND'; ?></td>
                            <td></td>
                        </tr>
                    <?php
                    } else {
                        echo '<tr><td colspan="7">Không có sản phẩm trong đơn hàng này!</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
            <div class="order-info">
                <p><strong>Mã đơn hàng:</strong> <?php echo $order_info['order_code']; ?></p>
                <p><strong>Ngày đặt:</strong> <?php echo $fm->formatDate($order_info['created_at']); ?></p>
                <p><strong>Trạng thái:</strong>
                    <?php
                    if ($order_info['status'] == 0) echo "Đang xử lý";
                    elseif ($order_info['status'] == 1) echo "Đang giao";
                    elseif ($order_info['status'] == 2) echo "Đã nhận";
                    elseif ($order_info['status'] == 3) echo "Thành công";
                    ?>
                </p>
            </div>
            <div class="shopping">
                <a href="order_history.php" class="shop-btn">Quay lại lịch sử đơn hàng</a>
                <a href="/" class="shop-btn">Tiếp tục mua hàng</a>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>

<style>
    .cartpage {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h3 {
        color: #4203a9;
        font-size: 24px;
        text-align: center;
        margin-bottom: 20px;
    }

    .message {
        display: block;
        color: #ff3333;
        text-align: center;
        margin-bottom: 15px;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .order-table th,
    .order-table td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
    }

    .order-table th {
        background: #f8f9fa;
        color: #333;
    }

    .order-table img {
        max-width: 50px;
        height: auto;
    }

    .order-info {
        margin: 20px 0;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }

    .order-info p {
        margin: 5px 0;
        font-size: 16px;
    }

    .order-info strong {
        color: #333;
    }

    .shopping {
        margin-top: 20px;
        text-align: center;
    }

    .shop-btn {
        padding: 10px 20px;
        background: #4203a9;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .shop-btn:hover {
        background: #35028c;
    }
</style>