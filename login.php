<?php
include 'inc/header.php';
// include 'inc/slider.php';
?>

<?php
$login_check = Session::get('customer_login');
if ($login_check == true) {
	header('Location:index.php');
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
	$loginCustomer = $cs->login_customer($_POST);
}
?>

<div class="main">
	<div class="content">
		<div class="login_panel">
			<h3>Khách hàng hiện tại</h3>
			<p>Đăng nhập bằng biểu mẫu dưới đây.</p>
			<?php
			if (isset($loginCustomer)) {
				echo '<span class="error-message">' . $loginCustomer . '</span>';
			}
			?>
			<form action="" method="POST">
				<input name="email" type="text" class="field" placeholder="Nhập Email...">
				<input name="password" type="password" class="field" placeholder="Nhập Mật khẩu...">
				<div class="buttons">
					<input type="submit" class="submit-btn" name="login" value="Đăng nhập">
				</div>
			</form>
			<p class="register-link">Chưa có tài khoản? <a href="register.php">Đăng ký tại đây</a></p>
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

	.content {
		display: flex;
		justify-content: center;
	}

	.login_panel {
		background: #fff;
		padding: 30px;
		border-radius: 10px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		width: 100%;
		max-width: 400px;
	}

	h3 {
		color: #4203a9;
		font-size: 24px;
		margin-bottom: 15px;
		text-align: center;
	}

	p {
		color: #666;
		font-size: 14px;
		margin-bottom: 20px;
		text-align: center;
	}

	.error-message {
		display: block;
		color: #ff3333;
		font-size: 14px;
		margin-bottom: 15px;
		text-align: center;
	}

	.field {
		width: 100%;
		padding: 12px 15px;
		margin-bottom: 15px;
		border: 1px solid #ddd;
		border-radius: 5px;
		font-size: 16px;
		transition: border-color 0.3s ease;
	}

	.field:focus {
		outline: none;
		border-color: #4203a9;
	}

	.submit-btn {
		width: 100%;
		padding: 12px;
		background: #4203a9;
		border: none;
		border-radius: 5px;
		color: white;
		font-size: 16px;
		cursor: pointer;
		transition: background 0.3s ease;
	}

	.submit-btn:hover {
		background: #35028c;
	}

	.buttons {
		margin-top: 20px;
		text-align: center;
	}

	.register-link {
		margin-top: 20px;
	}

	.register-link a {
		color: #4203a9;
		text-decoration: none;
	}

	.register-link a:hover {
		text-decoration: underline;
	}
</style>