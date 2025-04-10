<?php
include 'inc/header.php';

$login_check = Session::get('customer_login');
if ($login_check == false) {
	header('Location:login.php');
	exit;
}
?>

<div class="main">
	<div class="content">
		<div class="cartpage">
			<h3>Đặt hàng thành công!</h3>
			<p>Cảm ơn bạn đã đặt hàng. Mã đơn hàng của bạn là:
				<strong><?php echo isset($_GET['order_id']) ? 'ORD' . $_GET['order_id'] : 'N/A'; ?></strong>.
				Bạn có thể xem chi tiết đơn hàng tại <a href="orderdetails.php">đây</a>.
			</p>
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
		text-align: center;
	}

	h3 {
		color: #4203a9;
		font-size: 24px;
		margin-bottom: 20px;
	}

	p {
		color: #666;
		font-size: 16px;
	}

	.shopping {
		margin-top: 20px;
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