<?php include 'inc/header.php'; ?>
<?php
$login_check = Session::get('customer_login');
if ($login_check == false) {
	header('Location: login.php');
	exit;
}

$customer_id = Session::get('customer_id');
$cs = new Customer();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
	$updateCustomer = $cs->update_customer($_POST, $_FILES, $customer_id);
	$message = $updateCustomer['message'];
	if ($updateCustomer['message'] === 'Cập nhật khách hàng thành công') {
		echo "<script>alert('Cập nhật thành công!'); window.location='profile.php';</script>";
	}
}
?>

<div class="main">
	<div class="content">
		<div class="edit-profile-container">
			<h2>Chỉnh sửa thông tin cá nhân</h2>
			<?php if ($message) echo "<p class='message " . ($message === 'Cập nhật khách hàng thành công' ? 'success' : 'error') . "'>$message</p>"; ?>
			<form action="" method="POST" enctype="multipart/form-data">
				<?php
				$get_customers = $cs->get_customer_by_id($customer_id);
				if ($get_customers && $get_customers->num_rows > 0) {
					$result = $get_customers->fetch_assoc();
				?>
					<div class="edit-profile-card">
						<div class="profile-image">
							<img src="admin/uploads/<?php echo $result['image'] ? $result['image'] : 'default.jpg'; ?>" alt="Ảnh đại diện" id="preview-image" />
							<input type="file" name="image" accept="image/*" onchange="previewImage(event)" />
						</div>
						<div class="edit-form">
							<table class="edit-table">
								<tr>
									<td>Họ tên:</td>
									<td><input type="text" name="name" value="<?php echo $result['name']; ?>" required></td>
								</tr>
								<tr>
									<td>Địa chỉ:</td>
									<td><input type="text" name="address" value="<?php echo $result['address']; ?>" required></td>
								</tr>
								<tr>
									<td>Thành phố:</td>
									<td><input type="text" name="city" value="<?php echo $result['city']; ?>" required></td>
								</tr>
								<tr>
									<td>Quốc gia:</td>
									<td><input type="text" name="country" value="<?php echo $result['country']; ?>" required></td>
								</tr>
								<tr>
									<td>Mã bưu điện:</td>
									<td><input type="text" name="zipcode" value="<?php echo $result['zipcode']; ?>" required></td>
								</tr>
								<tr>
									<td>Số điện thoại:</td>
									<td><input type="text" name="phone" value="<?php echo $result['phone']; ?>" required></td>
								</tr>
								<tr>
									<td>Email:</td>
									<td><input type="email" name="email" value="<?php echo $result['email']; ?>" required></td>
								</tr>
							</table>
							<input type="submit" name="save" value="Lưu thay đổi" class="btn">
						</div>
					</div>
				<?php
				} else {
					echo '<p class="error">Không tìm thấy thông tin khách hàng!</p>';
				}
				?>
			</form>
		</div>
	</div>
</div>

<?php include 'inc/footer.php'; ?>

<script>
	function previewImage(event) {
		const reader = new FileReader();
		reader.onload = function() {
			const output = document.getElementById('preview-image');
			output.src = reader.result;
		};
		reader.readAsDataURL(event.target.files[0]);
	}
</script>

<style>
	.main {
		max-width: 1200px;
		margin: 0 auto;
		padding: 40px 20px;
	}

	.edit-profile-container {
		background: #fff;
		padding: 30px;
		border-radius: 10px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
	}

	h2 {
		color: #4203a9;
		font-size: 28px;
		text-align: center;
		margin-bottom: 30px;
	}

	.message {
		text-align: center;
		margin-bottom: 20px;
		font-size: 16px;
	}

	.success {
		color: #28a745;
	}

	.error {
		color: #ff3333;
	}

	.edit-profile-card {
		display: flex;
		align-items: flex-start;
		gap: 30px;
	}

	.profile-image {
		text-align: center;
	}

	.profile-image img {
		width: 150px;
		height: 150px;
		object-fit: cover;
		border-radius: 50%;
		border: 2px solid #ff6600;
		margin-bottom: 10px;
	}

	.profile-image input[type="file"] {
		display: block;
		margin: 0 auto;
	}

	.edit-form {
		flex: 1;
	}

	.edit-table {
		width: 100%;
		border-collapse: collapse;
	}

	.edit-table td {
		padding: 12px;
		border-bottom: 1px solid #eee;
	}

	.edit-table td:first-child {
		width: 30%;
		font-weight: bold;
		color: #333;
	}

	.edit-table input[type="text"],
	.edit-table input[type="email"] {
		width: 100%;
		padding: 8px;
		border: 1px solid #ddd;
		border-radius: 5px;
	}

	.btn {
		display: block;
		padding: 10px 20px;
		background: #4203a9;
		color: white;
		border: none;
		border-radius: 5px;
		margin-top: 20px;
		cursor: pointer;
		width: 200px;
		text-align: center;
	}

	.btn:hover {
		background: #35028c;
	}
</style>