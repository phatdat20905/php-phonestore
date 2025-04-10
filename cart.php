<?php
include 'inc/header.php';

$login_check = Session::get('customer_login');
if ($login_check == false) {
	header('Location:login.php');
	exit;
}

$ct = new Cart();

if (isset($_GET['cartid'])) {
	$cartId = $_GET['cartid'];
	$delcart = $ct->delete_product_cart($cartId);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
	$cartId = $_POST['cartId'];
	$stock = $_POST['stock'];
	$quantity = $_POST['quantity'];
	$updateQuantityCart = $ct->update_quantity_cart($quantity, $cartId, $stock);
	if ($quantity == 0) {
		$delcart = $ct->delete_product_cart($cartId);
	}
}

if (!isset($_GET['id'])) {
	echo "<meta http-equiv='refresh' content='0;URL=?id=live'>";
}
?>

<div class="main">
	<div class="content">
		<div class="cartoption">
			<div class="cartpage">
				<h2>Giỏ hàng của bạn</h2>
				<?php
				if (isset($updateQuantityCart)) {
					echo '<span class="message">' . $updateQuantityCart . '</span>';
				}
				if (isset($delcart)) {
					echo '<span class="message">' . $delcart . '</span>';
				}
				?>
				<table class="cart-table">
					<thead>
						<tr>
							<th width="20%">Tên sản phẩm</th>
							<th width="20%">Hình ảnh</th>
							<th width="15%">Giá</th>
							<th width="20%">Số lượng</th>
							<th width="20%">Tổng tiền</th>
							<th width="5%">Hành động</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$get_product_cart = $ct->get_cart_items();
						if ($get_product_cart) {
							$subtotal = 0;
							$qty = 0;
							while ($result = $get_product_cart->fetch_assoc()) {
						?>
								<tr>
									<td><?php echo $result['product_name'] ?></td>
									<td><img src="admin/uploads/<?php echo $result['image'] ?>" alt="Ảnh sản phẩm" /></td>
									<td><?php echo $fm->format_currency($result['price']) . ' VND' ?></td>
									<td>
										<form action="" method="post">
											<input type="hidden" name="cartId" value="<?php echo $result['cart_id'] ?>" />
											<input type="hidden" name="stock" value="<?php echo $result['stock'] ?>" />
											<input type="number" name="quantity" min="0" value="<?php echo $result['quantity'] ?>" />
											<input type="submit" name="submit" value="Cập nhật" class="update-btn" />
										</form>
									</td>
									<td><?php
										$total = $result['price'] * $result['quantity'];
										echo $fm->format_currency($total) . ' VND';
										?></td>
									<td><a onclick="return confirm('Bạn có chắc muốn xóa <?php echo $result['product_name'] ?> không?')"
											href="?cartid=<?php echo $result['cart_id'] ?>" class="delete-link">Xóa</a></td>
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
					<div class="shopping">
						<a href="index.php" class="shop-btn">Tiếp tục mua sắm</a>
						<a href="order.php" class="check-btn">Đặt hàng</a>
					</div>
				<?php
				} else {
					echo '<p class="empty-cart">Giỏ hàng của bạn đang trống! Vui lòng mua sắm ngay!</p>';
					echo '<div class="shopping"><a href="index.php" class="shop-btn">Mua sắm ngay</a></div>';
				}
				?>
			</div>
		</div>
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

	.cartpage {
		background: #fff;
		padding: 20px;
		border-radius: 10px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
	}

	h2 {
		color: #4203a9;
		font-size: 24px;
		margin-bottom: 20px;
		text-align: center;
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

	.cart-table input[type="number"] {
		width: 60px;
		padding: 5px;
	}

	.update-btn {
		padding: 5px 10px;
		background: #4203a9;
		color: white;
		border: none;
		border-radius: 5px;
		cursor: pointer;
	}

	.update-btn:hover {
		background: #35028c;
	}

	.delete-link {
		color: #ff3333;
		text-decoration: none;
	}

	.delete-link:hover {
		text-decoration: underline;
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

	.shopping {
		display: flex;
		justify-content: space-between;
		margin-top: 100px;
	}

	.shop-btn,
	.check-btn {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 10px 20px;
		background: #4203a9;
		color: white;
		text-decoration: none;
		border-radius: 5px;
	}

	.shop-btn:hover,
	.check-btn:hover {
		background: #35028c;
	}
</style>