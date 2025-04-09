<?php
include '../classes/Admin.php';
include_once($filepath . '/../lib/Session.php');
Session::checkLogin();
$admin = new Admin();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$message = $admin->login_admin($username, $password);
	if (!$message) {
		header("Location: index.php");
		exit;
	}
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Đăng nhập Admin</title>
	<link rel="stylesheet" href="css/admin.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
	<div class="login-container">
		<form action="login.php" method="post" class="login-form">
			<h1>Đăng nhập Admin</h1>
			<?php if ($message): ?>
				<div class="alert alert-error"><?php echo $message; ?></div>
			<?php endif; ?>
			<div class="form-group">
				<input type="text" name="username" placeholder="Tên đăng nhập" required>
			</div>
			<div class="form-group">
				<input type="password" name="password" placeholder="Mật khẩu" required>
			</div>
			<button type="submit" class="btn">Đăng nhập</button>
		</form>
	</div>
	<style>
		.login-container {
			max-width: 400px;
			margin: 50px auto;
			background: white;
			padding: 20px;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		}

		.login-form h1 {
			text-align: center;
			margin-bottom: 20px;
			color: #2c3e50;
		}
	</style>
</body>

</html>