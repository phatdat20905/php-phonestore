<?php
include 'inc/header.php';

$login_check = Session::get('customer_login');
if ($login_check == false) {
	header('Location: login.php');
	exit;
}

$customer_id = Session::get('customer_id');
$wishlist = new Wishlist();
$product = new Product(); // Giả định class Product đã có

if (isset($_GET['proid'])) {
	$proid = $_GET['proid'];
	$del_wishlist = $wishlist->del_wishlist_product($customer_id, $proid);
	if ($del_wishlist) {
		echo "<script>alert('Đã xóa sản phẩm khỏi danh sách yêu thích!'); window.location='wishlist.php';</script>";
	} else {
		echo '<span class="error">Lỗi khi xóa sản phẩm!</span>';
	}
}
?>

<div class="main">
	<div class="content">
		<div class="cartoption">
			<div class="cartpage">
				<h2>Danh sách yêu thích</h2>
				<table class="tblone">
					<thead>
						<tr>
							<th width="10%">STT</th>
							<th width="20%">Tên sản phẩm</th>
							<th width="30%">Hình ảnh</th>
							<th width="20%">Giá</th>
							<th width="20%">Hành động</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$get_wishlist = $wishlist->get_wishlist($customer_id);
						if ($get_wishlist && $get_wishlist->num_rows > 0) {
							$i = 0;
							while ($result = $get_wishlist->fetch_assoc()) {
								$i++;
								// Kiểm tra các cột có tồn tại không
								$product_id = isset($result['product_id']) ? $result['product_id'] : '';
								$product_name = isset($result['product_name']) ? $result['product_name'] : 'N/A';
								$image = isset($result['image']) ? $result['image'] : 'default.jpg';
								$price = isset($result['price']) ? $result['price'] : 0;
						?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $product_name; ?></td>
									<td><img src="admin/uploads/<?php echo $image; ?>" alt="Ảnh sản phẩm" /></td>
									<td><?php echo number_format($price, 0, ',', '.') . ' VND'; ?></td>
									<td>
										<a href="details.php?proid=<?php echo $product_id; ?>" class="btn">Mua ngay</a>
										<a href="?proid=<?php echo $product_id; ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
									</td>
								</tr>
						<?php
							}
						} else {
							echo '<tr><td colspan="5" class="empty">Chưa có sản phẩm nào trong danh sách yêu thích!</td></tr>';
						}
						?>
					</tbody>
				</table>
				<div class="shopping">
					<a href="index.php" class="shop-btn">Tiếp tục mua sắm</a>
				</div>
			</div>
		</div>
		<div class="clear"></div>
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

	h2 {
		color: #4203a9;
		font-size: 24px;
		text-align: center;
		margin-bottom: 20px;
	}

	.error {
		display: block;
		color: #ff3333;
		text-align: center;
		margin-bottom: 15px;
	}

	.tblone {
		width: 100%;
		border-collapse: collapse;
		margin-bottom: 20px;
	}

	.tblone th,
	.tblone td {
		padding: 12px;
		border: 1px solid #ddd;
		text-align: center;
	}

	.tblone th {
		background: #f8f9fa;
		color: #333;
	}

	.tblone img {
		max-width: 100px;
		height: auto;
	}

	.empty {
		color: #666;
		font-style: italic;
	}

	.btn {
		padding: 6px 12px;
		background: #4203a9;
		color: white;
		text-decoration: none;
		border-radius: 5px;
		margin: 0 5px;
	}

	.btn-delete {
		background: #dc3545;
	}

	.btn:hover {
		opacity: 0.9;
	}

	.shopping {
		text-align: center;
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