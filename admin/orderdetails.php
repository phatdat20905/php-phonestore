<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Order.php'; ?>
<?php
$order = new Order();
if (!isset($_GET['orderId']) || empty($_GET['orderId'])) {
    echo "<script>window.location='inbox.php';</script>";
    exit;
}
$orderId = $_GET['orderId'];
$orderDetails = $order->get_order_details($orderId);
$orderInfoQuery = $order->show_orders();
$orderInfo = null;
while ($row = $orderInfoQuery->fetch_assoc()) {
    if ($row['order_id'] == $orderId) {
        $orderInfo = $row;
        break;
    }
}
if (!$orderInfo) {
    echo "<script>window.location='inbox.php';</script>";
    exit;
}
?>
<div class="content">
    <div class="box">
        <h2>Chi tiết đơn hàng #<?php echo $orderInfo['order_code']; ?></h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($orderDetails) {
                    $i = 0;
                    $subtotal = 0;
                    while ($result = $orderDetails->fetch_assoc()) {
                        $i++;
                        $item_total = $result['price'] * $result['quantity'];
                        $subtotal += $item_total;
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $result['product_name']; ?></td>
                            <td><img src="uploads/<?php echo $result['image']; ?>" alt="Product Image" width="80"></td>
                            <td><?php echo $result['quantity']; ?></td>
                            <td><?php echo number_format($result['price'], 0, ',', '.') . ' VND'; ?></td>
                            <td><?php echo number_format($item_total, 0, ',', '.') . ' VND'; ?></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="order-summary" style="margin-top: 20px;">
            <table class="summary-table">
                <tr>
                    <th>Tổng phụ:</th>
                    <td><?php echo number_format($subtotal, 0, ',', '.') . ' VND'; ?></td>
                </tr>
                <tr>
                    <th>Thuế VAT (10%):</th>
                    <td><?php echo number_format($subtotal * 0.1, 0, ',', '.') . ' VND'; ?></td>
                </tr>
                <tr>
                    <th>Tổng tiền:</th>
                    <td><strong><?php echo number_format($orderInfo['total'], 0, ',', '.') . ' VND'; ?></strong></td>
                </tr>
                <tr>
                    <th>Trạng thái:</th>
                    <td>
                        <?php
                        if ($orderInfo['status'] == 0) echo 'Mới';
                        elseif ($orderInfo['status'] == 1) echo 'Đang vận chuyển';
                        elseif ($orderInfo['status'] == 2) echo 'Đã nhận';
                        elseif ($orderInfo['status'] == 3) echo 'Thành công';
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?php include 'inc/footer.php'; ?>

<style>
    .order-summary {
        float: right;
        width: 40%;
    }

    .summary-table {
        width: 100%;
        border-collapse: collapse;
    }

    .summary-table th,
    .summary-table td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    .summary-table th {
        text-align: left;
        color: #333;
    }

    .summary-table td {
        text-align: right;
    }
</style>