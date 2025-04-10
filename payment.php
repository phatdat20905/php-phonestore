<?php
include 'inc/header.php';

$login_check = Session::get('customer_login');
if ($login_check == false) {
	header('Location: login.php');
	exit;
}
?>

<div class="main">
	<div class="content">
		<div class="section group">
			<div class="content_top">
				<h3>Phương thức thanh toán</h3>
				<div class="wrapper_method">
					<h4>Chọn phương thức thanh toán của bạn:</h4>
					<a href="offlinepayment.php" class="payment-btn">Thanh toán ngoại tuyến</a>
					<a href="onlinepayment.php" class="payment-btn">Thanh toán trực tuyến</a>
				</div>
				<div style="text-align: center;">
					<a href="cart.php" class="back-btn">Quay lại giỏ hàng</a>
				</div>
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

	.content_top {
		background: #fff;
		padding: 30px;
		border-radius: 10px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
	}

	h3 {
		color: #4203a9;
		font-size: 24px;
		text-align: center;
		margin-bottom: 20px;
	}

	.wrapper_method {
		text-align: center;
		padding: 20px;
	}

	h4 {
		color: #333;
		margin-bottom: 20px;
	}

	.payment-btn {
		display: inline-block;
		padding: 12px 20px;
		background: #4203a9;
		color: white;
		text-decoration: none;
		border-radius: 5px;
		margin: 10px;
	}

	.payment-btn:hover {
		background: #35028c;
	}

	.back-btn {
		display: inline-block;
		padding: 12px 20px;
		background: #666;
		color: white;
		text-decoration: none;
		border-radius: 5px;
		margin-top: 20px;
	}

	.back-btn:hover {
		background: #555;
	}
</style>