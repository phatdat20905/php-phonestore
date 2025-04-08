<?php
include 'inc/header.php';
// include 'inc/slider.php';
?>

<?php
$login_check = Session::get('customer_login');
if ($login_check == false) {
	header('Location:login.php');
}
?>

<div class="main">
	<div class="content">
		<div class="cartpage">
			<div class="order-page">
				<h3>Trang đặt hàng</h3>
				<p>Vui lòng kiểm tra giỏ hàng và tiến hành thanh toán.</p>
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

	.order-page h3 {
		color: #4203a9;
		font-size: 30px;
		font-weight: bold;
		margin-bottom: 15px;
	}

	.order-page p {
		color: #666;
		font-size: 16px;
	}
</style>