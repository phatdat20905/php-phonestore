<?php
include 'inc/header.php';

$login_check = Session::get('customer_login');
if ($login_check == false) {
	header('Location: login.php');
	exit;
}

$order = new Order();

if (isset($_GET['confirmid'])) {
	$customer_id = $_GET['confirmid'];
	$order_id = $_GET['orderid'];
	$result = $order->update_status($order_id, 2); // Status 2 = Đã nhận
	if ($result['success']) {
		$message = "Xác nhận nhận hàng thành công!";
	} else {
		$message = $result['message'];
	}
}

if (isset($_GET['cancelid'])) {
	$order_id = $_GET['cancelid'];
	$result = $order->cancel_order($order_id); // Khách hủy đơn hàng
	if ($result['success']) {
		$message = "Hủy đơn hàng thành công!";
	} else {
		$message = $result['message'];
	}
}

if (!isset($_GET['orderid']) || empty($_GET['orderid'])) {
	header('Location: index.php');
	exit;
}

$order_id = $_GET['orderid'];
$customer_id = Session::get('customer_id');

// Kiểm tra đơn hàng có thuộc về khách hàng không
$check_order = $order->get_order_by_id_and_customer($order_id, $customer_id);
if (!$check_order || $check_order->num_rows == 0) {
	header('Location: order_history.php');
	exit;
}
?>

<div class="main">
	<div class="content">
		<div class="cartpage">
			<h3>Chi tiết đơn hàng của bạn</h3>
			<?php if (isset($message)) echo '<span class="message">' . $message . '</span>'; ?>
			<table class="order-table">
				<thead>
					<tr>
						<th width="5%">STT</th>
						<th width="20%">Tên sản phẩm</th>
						<th width="10%">Hình ảnh</th>
						<th width="15%">Giá</th>
						<th width="10%">Số lượng</th>
						<th width="15%">Ngày đặt</th>
						<th width="15%">Mã đơn hàng</th>
						<th width="10%">Trạng thái</th>
						<th width="10%">Hành động</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$get_order_details = $order->get_order_details($order_id);
					if ($get_order_details && $get_order_details->num_rows > 0) {
						$i = 0;
						while ($result = $get_order_details->fetch_assoc()) {
							$i++;
							$order_info = $order->get_order_by_id_and_customer($order_id, $customer_id)->fetch_assoc();
					?>
							<tr>
								<td><?php echo $i ?></td>
								<td><?php echo $result['product_name'] ?></td>
								<td><img src="admin/uploads/<?php echo $result['image'] ?>" alt="Ảnh sản phẩm" /></td>
								<td><?php echo number_format($result['price'], 0, ',', '.') . ' VND' ?></td>
								<td><?php echo $result['quantity'] ?></td>
								<td><?php echo $fm->formatDate($order_info['created_at']) ?></td>
								<td><?php echo $order_info['order_code'] ?></td>
								<td>
									<?php
									if ($order_info['status'] == 0) echo "Đang xử lý";
									elseif ($order_info['status'] == 1) echo "Đang giao";
									elseif ($order_info['status'] == 2) echo "Đã nhận";
									elseif ($order_info['status'] == 3) echo "Thành công";
									?>
								</td>
								<td>
									<?php
									if ($order_info['status'] == 0) {
										echo '<a href="?cancelid=' . $order_info['order_id'] . '" class="cancel-link" onclick="return confirm(\'Bạn có chắc muốn hủy đơn hàng ' . $order_info['order_code'] . ' không?\')">Hủy</a>';
									} elseif ($order_info['status'] == 1) {
										echo '<a href="?confirmid=' . $customer_id . '&orderid=' . $order_info['order_id'] . '" class="action-link">Đã nhận</a>';
									} elseif ($order_info['status'] == 2) {
										echo "Đã nhận";
									}
									?>
								</td>
							</tr>
					<?php
						}
					} else {
						echo '<tr><td colspan="9">Không có sản phẩm trong đơn hàng này!</td></tr>';
					}
					?>
				</tbody>
			</table>
			<div class="shopping">
				<a href="order_history.php" class="shop-btn">Quay lại lịch sử đơn hàng</a>
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

	.action-link {
		color: #4203a9;
		text-decoration: none;
	}

	.action-link:hover {
		text-decoration: underline;
	}

	.cancel-link {
		color: #ff3333;
		text-decoration: none;
	}

	.cancel-link:hover {
		text-decoration: underline;
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