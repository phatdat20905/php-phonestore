<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Order.php'; ?>
<?php
$order = new Order();
$message = '';
if (isset($_GET['delId'])) {
    $id = $_GET['delId'];
    $delOrder = $order->delete_order($id);
    $message = $delOrder['message'];
} elseif (isset($_GET['status']) && isset($_GET['orderId'])) {
    $id = $_GET['orderId'];
    $status = $_GET['status'];
    $updateOrder = $order->update_status($id, $status);
    $message = $updateOrder['message'];
}

// Lấy dữ liệu lọc
$status = isset($_GET['status']) ? $_GET['status'] : '';
$customer = isset($_GET['customer']) ? $_GET['customer'] : '';
$order_code = isset($_GET['order_code']) ? $_GET['order_code'] : '';
?>
<div class="content">
    <div class="box">
        <h2>Danh sách đơn hàng</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <!-- Form lọc -->
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label for="status">Trạng thái:</label>
                <select name="status" id="status">
                    <option value="">Tất cả</option>
                    <option value="0" <?php echo $status == '0' ? 'selected' : ''; ?>>Mới</option>
                    <option value="1" <?php echo $status == '1' ? 'selected' : ''; ?>>Đang vận chuyển</option>
                    <option value="2" <?php echo $status == '2' ? 'selected' : ''; ?>>Đã nhận</option>
                    <option value="3" <?php echo $status == '3' ? 'selected' : ''; ?>>Thành công</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="customer">Khách hàng:</label>
                <input type="text" name="customer" id="customer" value="<?php echo htmlspecialchars($customer); ?>" placeholder="Nhập tên khách hàng">
            </div>
            <div class="filter-group">
                <label for="order_code">Mã đơn hàng:</label>
                <input type="text" name="order_code" id="order_code" value="<?php echo htmlspecialchars($order_code); ?>" placeholder="Nhập mã đơn hàng">
            </div>
            <button type="submit" class="btn btn-filter">Lọc</button>
        </form>
        <table class="data-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã đơn hàng</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $orderlist = $order->filter_orders($status, $customer, $order_code);
                if ($orderlist) {
                    $i = 0;
                    while ($result = $orderlist->fetch_assoc()) {
                        $i++;
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $result['order_code']; ?></td>
                            <td><?php echo $result['name']; ?></td>
                            <td><?php echo number_format($result['total'], 0, ',', '.') . ' VND'; ?></td>
                            <td><?php echo $result['created_at']; ?></td>
                            <td>
                                <?php
                                if ($result['status'] == 0) echo 'Mới';
                                elseif ($result['status'] == 1) echo 'Đang vận chuyển';
                                elseif ($result['status'] == 2) echo 'Đã nhận';
                                elseif ($result['status'] == 3) echo 'Thành công';
                                ?>
                            </td>
                            <td>
                                <a href="orderdetails.php?orderId=<?php echo $result['order_id']; ?>" class="btn btn-edit"><i class="fas fa-eye"></i> Xem</a>
                                <?php if ($result['status'] == 0): ?>
                                    <a href="?orderId=<?php echo $result['order_id']; ?>&status=1" class="btn btn-confirm">Xác nhận</a>
                                <?php elseif ($result['status'] == 1): ?>
                                    <a href="?orderId=<?php echo $result['order_id']; ?>&status=2" class="btn btn-delivered">Đã nhận</a>
                                <?php elseif ($result['status'] == 2): ?>
                                    <a href="?orderId=<?php echo $result['order_id']; ?>&status=3" class="btn btn-complete">Hoàn thành</a>
                                <?php endif; ?>
                                <a href="?delId=<?php echo $result['order_id']; ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')"><i class="fas fa-trash"></i> Xóa</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='7'>Không tìm thấy đơn hàng.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<style>
.filter-form {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}
.filter-group {
    display: flex;
    flex-direction: column;
}
.filter-group label {
    margin-bottom: 5px;
}
.filter-group select, .filter-group input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
.btn-filter {
    padding: 8px 15px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.btn-filter:hover {
    background-color: #218838;
}
</style>
<?php include 'inc/footer.php'; ?>