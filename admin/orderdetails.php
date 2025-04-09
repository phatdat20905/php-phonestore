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
$orderInfo = $order->show_orders()->fetch_assoc();
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
                    while ($result = $orderDetails->fetch_assoc()) {
                        $i++;
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $result['product_name']; ?></td>
                            <td><img src="uploads/<?php echo $result['image']; ?>" alt="Product Image" width="80"></td>
                            <td><?php echo $result['quantity']; ?></td>
                            <td><?php echo number_format($result['price'], 0, ',', '.') . ' VND'; ?></td>
                            <td><?php echo number_format($result['price'] * $result['quantity'], 0, ',', '.') . ' VND'; ?></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="order-summary" style="margin-top: 20px;">
            <p><strong>Tổng tiền:</strong> <?php echo number_format($orderInfo['total'], 0, ',', '.') . ' VND'; ?></p>
            <p><strong>Trạng thái:</strong> <?php echo $orderInfo['status'] == 0 ? 'Mới' : ($orderInfo['status'] == 1 ? 'Đang vận chuyển' : 'Đã nhận'); ?></p>
        </div>
    </div>
</div>
<?php include 'inc/footer.php'; ?>