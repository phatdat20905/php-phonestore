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
			<h3>Đăng nhập</h3>
			<p>Đăng nhập bằng biểu mẫu dưới đây.</p>
			<?php
			if (isset($loginCustomer)) {
				$class = ($loginCustomer === "Đăng nhập thành công!") ? 'success' : '';
				echo '<span class="message ' . $class . '">' . $loginCustomer . '</span>';
				if ($loginCustomer === "Đăng nhập thành công!") {
					echo "<script>window.location='index.php';</script>";
				}
			}
			?>
			<form action="" method="POST">
				<input name="email" type="text" class="field" placeholder="Nhập Email..." required>
				<input name="password" type="password" class="field" placeholder="Nhập Mật khẩu..." required>
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

<style type="text/css">
	.main {
		max-width: 1200px;
		margin: 0 auto;
		padding: 40px 20px;
		background: #f9f9f9;
	}

	.content {
		display: flex;
		justify-content: center;
	}

	.login_panel {
		background: #fff;
		padding: 30px;
		border-radius: 8px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
		width: 100%;
		max-width: 400px;
	}

	h3 {
		font-size: 28px;
		color: #333;
		margin-bottom: 15px;
		text-align: center;
		font-family: 'Monda', sans-serif;
	}

	p {
		color: #666;
		font-size: 14px;
		margin-bottom: 20px;
		text-align: center;
	}

	.message {
		display: block;
		font-size: 14px;
		margin-bottom: 15px;
		text-align: center;
		color: #ff3333;
	}

	.message.success {
		color: #28a745;
	}

	.field {
		width: 100%;
		padding: 12px;
		margin-bottom: 15px;
		border: 1px solid #ddd;
		border-radius: 5px;
		font-size: 16px;
		transition: border-color 0.3s ease, box-shadow 0.3s ease;
	}

	.field:focus {
		border-color: #ff6600;
		box-shadow: 0 0 5px rgba(255, 102, 0, 0.3);
		outline: none;
	}

	.submit-btn {
		width: 100%;
		padding: 12px;
		background: #ff6600;
		border: none;
		border-radius: 5px;
		color: #fff;
		font-size: 16px;
		cursor: pointer;
		transition: background 0.3s ease;
	}

	.submit-btn:hover {
		background: #e65c00;
	}

	.buttons {
		margin-top: 20px;
	}

	.register-link {
		margin-top: 20px;
		text-align: center;
	}

	.register-link a {
		color: #ff6600;
		text-decoration: none;
		font-weight: bold;
	}

	.register-link a:hover {
		text-decoration: underline;
	}
</style>