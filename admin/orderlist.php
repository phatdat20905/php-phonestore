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
?>
<div class="content">
	<div class="box">
		<h2>Danh sách đơn hàng</h2>
		<?php if ($message): ?>
			<div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
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
				$orderlist = $order->show_orders();
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
				}
				?>
			</tbody>
		</table>
	</div>
</div>
<?php include 'inc/footer.php'; ?>