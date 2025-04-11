<?php include 'inc/header.php'; ?>
<?php
$login_check = Session::get('customer_login');
if ($login_check == false) {
	header('Location: login.php');
	exit;
}

$customer_id = Session::get('customer_id');
$cs = new Customer();
?>

<div class="main">
	<div class="content">
		<div class="profile-container">
			<h2>Thông tin cá nhân</h2>
			<?php
			$get_customers = $cs->get_customer_by_id($customer_id);
			if ($get_customers && $get_customers->num_rows > 0) {
				$result = $get_customers->fetch_assoc();
			?>
				<div class="profile-card">
					<div class="profile-image">
						<img src="admin/uploads/<?php echo $result['image'] ? $result['image'] : 'default.jpg'; ?>" alt="Ảnh đại diện" />
					</div>
					<div class="profile-details">
						<table class="profile-table">
							<tr>
								<td>Họ tên:</td>
								<td><?php echo $result['name']; ?></td>
							</tr>
							<tr>
								<td>Địa chỉ:</td>
								<td><?php echo $result['address']; ?></td>
							</tr>
							<tr>
								<td>Thành phố:</td>
								<td><?php echo $result['city']; ?></td>
							</tr>
							<tr>
								<td>Quốc gia:</td>
								<td><?php echo $result['country']; ?></td>
							</tr>
							<tr>
								<td>Mã bưu điện:</td>
								<td><?php echo $result['zipcode']; ?></td>
							</tr>
							<tr>
								<td>Số điện thoại:</td>
								<td><?php echo $result['phone']; ?></td>
							</tr>
							<tr>
								<td>Email:</td>
								<td><?php echo $result['email']; ?></td>
							</tr>
						</table>
						<a href="editprofile.php" class="btn">Chỉnh sửa thông tin</a>
					</div>
				</div>
			<?php
			} else {
				echo '<p class="error">Không tìm thấy thông tin khách hàng!</p>';
			}
			?>
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

	.profile-container {
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

	.profile-card {
		display: flex;
		align-items: flex-start;
		gap: 30px;
	}

	.profile-image img {
		width: 150px;
		height: 150px;
		object-fit: cover;
		border-radius: 50%;
		border: 2px solid #4203a9;
	}

	.profile-details {
		flex: 1;
	}

	.profile-table {
		width: 100%;
		border-collapse: collapse;
	}

	.profile-table td {
		padding: 12px;
		border-bottom: 1px solid #eee;
	}

	.profile-table td:first-child {
		width: 30%;
		font-weight: bold;
		color: #333;
	}

	.error {
		color: #ff3333;
		text-align: center;
		font-size: 16px;
	}

	.btn {
		display: inline-block;
		padding: 10px 20px;
		background: #4203a9;
		color: white;
		text-decoration: none;
		border-radius: 5px;
		margin-top: 20px;
		text-align: center;
	}

	.btn:hover {
		background: #35028c;
	}
</style>