<?php include 'inc/header.php'; ?>
<?php
$login_check = Session::get('customer_login');
if ($login_check == false) {
    header('Location: login.php');
    exit;
}

$customer_id = Session::get('customer_id');
$order = new Order(); // Giả định class Order đã có
?>

<div class="main">
    <div class="content">
        <div class="order-history-container">
            <h2>Lịch sử đơn hàng</h2>
            <table class="order-table">
                <thead>
                    <tr>
                        <th width="10%">Mã đơn hàng</th>
                        <th width="20%">Ngày đặt</th>
                        <th width="20%">Tổng tiền</th>
                        <th width="20%">Trạng thái</th>
                        <th width="20%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $get_orders = $order->get_orders_by_customer($customer_id);
                    if ($get_orders && $get_orders->num_rows > 0) {
                        while ($result = $get_orders->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo $result['order_id']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($result['created_at'])); ?></td>
                                <td><?php echo number_format($result['total'], 0, ',', '.') . ' VND'; ?></td>
                                <td><?php
                                    $status = $result['status'];
                                    switch ($status) {
                                        case 0:
                                            echo 'Chờ xử lý';
                                            break;
                                        case 1:
                                            echo 'Đang chuẩn bị';
                                            break;
                                        case 2:
                                            echo 'Đang giao';
                                            break;
                                        case 3:
                                            echo 'Hoàn thành';
                                            break;
                                        case 4:
                                            echo 'Đã hủy';
                                            break;
                                        default:
                                            echo 'Không xác định';
                                    }
                                    ?></td>
                                <td>
                                    <a href="orderdetails_history.php?orderid=<?php echo $result['order_id']; ?>" class="btn">Xem chi tiết</a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="5" class="empty">Bạn chưa có đơn hàng nào!</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
            <div class="back-to-shop">
                <a href="index.php"><img src="images/shop.png" alt="Tiếp tục mua sắm" /></a>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>

<style>
    .main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .order-history-container {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #4203a9;
        font-size: 28px;
        text-align: center;
        margin-bottom: 30px;
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
        font-weight: bold;
    }

    .order-table td {
        color: #555;
    }

    .empty {
        color: #666;
        font-style: italic;
        text-align: center;
    }

    .btn {
        padding: 6px 12px;
        background: #4203a9;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn:hover {
        background: #35028c;
    }

    .back-to-shop {
        text-align: center;
        margin-top: 20px;
    }

    .back-to-shop img {
        max-width: 150px;
    }
</style>