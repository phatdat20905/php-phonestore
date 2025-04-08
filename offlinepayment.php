<?php
include 'inc/header.php';
// include 'inc/slider.php';
?>

<?php
if (isset($_GET['orderid']) && $_GET['orderid'] == 'order') {
	$customer_id = Session::get('customer_id');
	$inserOrder = $ct->insertOrder($customer_id);
	// $delcart = $ct->del_all_data_cart();
	// header('Location:success.php');
}
?>

<div class="main">
	<div class="content">
		<h3>Thanh toán ngoại tuyến</h3>
		<form action="" method="POST">
			<div class="section group">
				<div class="box_left">
					<div class="cartpage">
						<table class="cart-table">
							<thead>
								<tr>
									<th width="5%">ID</th>
									<th width="15%">Tên sản phẩm</th>
									<th width="15%">Giá</th>
									<th width="25%">Số lượng</th>
									<th width="20%">Tổng tiền</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$get_product_cart = $ct->get_product_cart_checkout();
								if ($get_product_cart) {
									$subtotal = 0;
									$qty = 0;
									$i = 0;
									while ($result = $get_product_cart->fetch_assoc()) {
										$i++;
								?>
										<tr>
											<td><?php echo $i ?></td>
											<td><?php echo $result['productName'] ?></td>
											<td><?php echo $fm->format_currency($result['price']) . ' VND' ?></td>
											<td><?php echo $result['quantity'] ?></td>
											<td><?php
												$total = $result['price'] * $result['quantity'];
												echo $fm->format_currency($total) . ' VND';
												?></td>
										</tr>
								<?php
										$subtotal += $total;
										$qty += $result['quantity'];
									}
								}
								?>
							</tbody>
						</table>
						<?php
						$check_cart = $ct->check_cart();
						if ($check_cart) {
						?>
							<div class="cart-summary">
								<table>
									<tr>
										<th>Tổng phụ:</th>
										<td><?php echo $fm->format_currency($subtotal) . ' VND';
											Session::set('sum', $subtotal);
											Session::set('qty', $qty); ?></td>
									</tr>
									<tr>
										<th>VAT:</th>
										<td>10% (<?php echo $fm->format_currency($subtotal * 0.1) . ' VND'; ?>)</td>
									</tr>
									<tr>
										<th>Tổng cộng:</th>
										<td><?php $vat = $subtotal * 0.1;
											echo $fm->format_currency($subtotal + $vat) . ' VND'; ?></td>
									</tr>
								</table>
							</div>
						<?php
						} else {
							echo '<p class="empty-cart">Giỏ hàng của bạn đang trống! Vui lòng mua sắm ngay!</p>';
						}
						?>
					</div>
				</div>
				<div class="box_right">
					<table class="customer-info">
						<?php
						$id = Session::get('customer_id');
						$get_customers = $cs->getCustomerById($id);
						if ($get_customers) {
							while ($result = $get_customers->fetch_assoc()) {
						?>
								<tr>
									<td>Họ tên</td>
									<td>:</td>
									<td><?php echo $result['name'] ?></td>
								</tr>
								<tr>
									<td>Địa chỉ</td>
									<td>:</td>
									<td><?php echo $result['address'] ?></td>
								</tr>
								<tr>
									<td>Thành phố</td>
									<td>:</td>
									<td><?php echo $result['city'] ?></td>
								</tr>
								<tr>
									<td>Quốc gia</td>
									<td>:</td>
									<td><?php echo $result['country'] ?></td>
								</tr>
								<tr>
									<td>Mã bưu điện</td>
									<td>:</td>
									<td><?php echo $result['zipcode'] ?></td>
								</tr>
								<tr>
									<td>Số điện thoại</td>
									<td>:</td>
									<td><?php echo $result['phone'] ?></td>
								</tr>
								<tr>
									<td>Email</td>
									<td>:</td>
									<td><?php echo $result['email'] ?></td>
								</tr>
								<tr>
									<td colspan="3"><a href="editprofile.php" class="edit-link">Cập nhật thông tin</a></td>
								</tr>
						<?php
							}
						}
						?>
					</table>
				</div>
			</div>
			<div class="order-btn-wrapper">
				<a href="order.php" class="submit_order">Đặt hàng ngay</a>
			</div>
		</form>
	</div>
</div>

<?php
include 'inc/footer.php';
?>

<style>
	.main {
		max-width: 1200px;
		margin: 0 auto;
		padding: 40px 20px;
	}

	h3 {
		color: #4203a9;
		font-size: 24px;
		text-align: center;
		margin-bottom: 20px;
	}

	.section {
		display: flex;
		gap: 20px;
		flex-wrap: wrap;
	}

	.box_left,
	.box_right {
		background: #fff;
		padding: 20px;
		border-radius: 10px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
	}

	.box_left {
		flex: 1;
		min-width: 300px;
	}

	.box_right {
		flex: 1;
		min-width: 300px;
	}

	.cart-table {
		width: 100%;
		border-collapse: collapse;
	}

	.cart-table th,
	.cart-table td {
		padding: 12px;
		border: 1px solid #ddd;
		text-align: center;
	}

	.cart-table th {
		background: #f8f9fa;
		color: #333;
	}

	.cart-summary {
		margin-top: 20px;
	}

	.cart-summary table {
		width: 100%;
	}

	.cart-summary th {
		text-align: left;
	}

	.empty-cart {
		text-align: center;
		color: #666;
	}

	.customer-info {
		width: 100%;
		border-collapse: collapse;
	}

	.customer-info td {
		padding: 10px;
		border-bottom: 1px solid #ddd;
	}

	.edit-link {
		color: #4203a9;
		text-decoration: none;
	}

	.edit-link:hover {
		text-decoration: underline;
	}

	.order-btn-wrapper {
		text-align: center;
		margin-top: 20px;
	}

	.submit_order {
		padding: 12px 20px;
		background: #4203a9;
		color: white;
		text-decoration: none;
		border-radius: 5px;
	}

	.submit_order:hover {
		background: #35028c;
	}
</style>