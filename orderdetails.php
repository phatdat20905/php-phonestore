<?php
include 'inc/header.php';
// include 'inc/slider.php';
?>

<?php
$login_check = Session::get('customer_login');
if ($login_check == false) {
	header('Location:login.php');
}
if (isset($_GET['confirmid'])) {
	$id = $_GET['confirmid'];
	$time = $_GET['time'];
	$price = $_GET['price'];
	$shifted_confirm = $ct->shifted_confirm($id, $time, $price);
}
?>

<div class="main">
	<div class="content">
		<div class="cartpage">
			<h3>Chi tiết đơn hàng của bạn</h3>
			<table class="order-table">
				<thead>
					<tr>
						<th width="10%">STT</th>
						<th width="20%">Tên sản phẩm</th>
						<th width="10%">Hình ảnh</th>
						<th width="15%">Giá</th>
						<th width="15%">Số lượng</th>
						<th width="10%">Ngày đặt</th>
						<th width="10%">Trạng thái</th>
						<th width="10%">Hành động</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$customer_id = Session::get('customer_id');
					$get_cart_ordered = $ct->get_cart_ordered($customer_id);
					if ($get_cart_ordered) {
						$i = 0;
						while ($result = $get_cart_ordered->fetch_assoc()) {
							$i++;
					?>
							<tr>
								<td><?php echo $i ?></td>
								<td><?php echo $result['productName'] ?></td>
								<td><img src="admin/uploads/<?php echo $result['image'] ?>" alt="Ảnh sản phẩm" /></td>
								<td><?php echo $fm->format_currency($result['price']) . ' VND' ?></td>
								<td><?php echo $result['quantity'] ?></td>
								<td><?php echo $fm->formatDate($result['date_order']) ?></td>
								<td>
									<?php
									if ($result['status'] == 0) echo "Đang xử lý";
									elseif ($result['status'] == 1) echo "Đã giao";
									elseif ($result['status'] == 2) echo "Đã nhận";
									?>
								</td>
								<td>
									<?php
									if ($result['status'] == 0) echo "N/A";
									elseif ($result['status'] == 1) {
										echo '<a href="?confirmid=' . $customer_id . '&price=' . $result['price'] . '&time=' . $result['date_order'] . '" class="action-link">Xác nhận</a>';
									} elseif ($result['status'] == 2) echo "Đã nhận";
									?>
								</td>
							</tr>
					<?php
						}
					}
					?>
				</tbody>
			</table>
			<div class="shopping">
				<a href="index.php" class="shop-btn">Tiếp tục mua sắm</a>
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

	.shopping {
		margin-top: 20px;
		text-align: center;
	}

	.shop-btn {
		display: inline-flex;
		align-items: center;
		gap: 10px;
		padding: 10px 20px;
		background: #4203a9;
		color: white;
		text-decoration: none;
		border-radius: 5px;
	}

	.shop-btn:hover {
		background: #35028c;
	}

	.shop-btn img {
		width: 20px;
		height: auto;
	}
</style>