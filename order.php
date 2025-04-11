<?php
include 'inc/header.php';

$login_check = Session::get('customer_login');
if ($login_check == false) {
	header('Location:login.php');
	exit;
}

$ct = new Cart();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
	// Không gọi insert_order() ở đây, chỉ chuyển sang payment.php
	header("Location: payment.php");
	exit;
}
?>

<div class="main">
	<div class="content">
		<div class="cartpage">
			<h3>Xác nhận đơn hàng</h3>
			<form method="post">
				<table class="cart-table">
					<thead>
						<tr>
							<th>Tên sản phẩm</th>
							<th>Hình ảnh</th>
							<th>Giá</th>
							<th>Số lượng</th>
							<th>Tổng tiền</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$get_cart_items = $ct->get_cart_items();
						$subtotal = 0;
						if ($get_cart_items) {
							while ($item = $get_cart_items->fetch_assoc()) {
								$total = $item['price'] * $item['quantity'];
								$subtotal += $total;
						?>
								<tr>
									<td><?php echo $item['product_name'] ?></td>
									<td><img src="admin/uploads/<?php echo $item['image'] ?>" alt="Ảnh sản phẩm" /></td>
									<td><?php echo number_format($item['price'], 0, ',', '.') . ' VND' ?></td>
									<td><?php echo $item['quantity'] ?></td>
									<td><?php echo $fm->format_currency($total) . ' VND' ?></td>
								</tr>
						<?php
							}
						}
						?>
					</tbody>
				</table>
				<?php if ($subtotal > 0) { ?>
					<div class="cart-summary">
						<table>
							<tr>
								<th>Tổng phụ:</th>
								<td><?php echo $fm->format_currency($subtotal) . ' VND'; ?></td>
							</tr>
							<tr>
								<th>VAT (10%):</th>
								<td><?php echo $fm->format_currency($subtotal * 0.1) . ' VND'; ?></td>
							</tr>
							<tr>
								<th>Tổng cộng:</th>
								<td><?php echo $fm->format_currency($subtotal + $subtotal * 0.1) . ' VND'; ?></td>
							</tr>
						</table>
						<div class="order-actions">
							<button type="submit" name="confirm_order" class="submit-btn">Xác nhận đặt hàng</button>
							<a href="cart.php" class="back-btn">Quay lại giỏ hàng</a>
						</div>
					</div>
				<?php } else { ?>
					<p class="empty-cart">Giỏ hàng của bạn trống! Vui lòng thêm sản phẩm.</p>
					<div class="shopping"><a href="index.php" class="shop-btn">Mua sắm ngay</a></div>
				<?php } ?>
			</form>
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

	.message {
		display: block;
		color: #ff3333;
		text-align: center;
		margin-bottom: 15px;
	}

	.cart-table {
		width: 100%;
		border-collapse: collapse;
		margin-bottom: 20px;
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

	.cart-table img {
		max-width: 50px;
		height: auto;
	}

	.cart-summary {
		float: right;
		width: 40%;
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

	.order-actions {
		margin-top: 20px;
		display: flex;
		justify-content: space-between;
	}

	.submit-btn {
		padding: 10px 20px;
		background: #4203a9;
		color: white;
		border: none;
		border-radius: 5px;
		cursor: pointer;
	}

	.submit-btn:hover {
		background: #35028c;
	}

	.back-btn,
	.shop-btn {
		padding: 10px 20px;
		background: #666;
		color: white;
		text-decoration: none;
		border-radius: 5px;
	}

	.back-btn:hover,
	.shop-btn:hover {
		background: #555;
	}

	.shopping {
		text-align: center;
		margin-top: 20px;
	}
</style>