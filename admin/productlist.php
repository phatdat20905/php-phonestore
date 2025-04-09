<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Product.php'; ?>
<?php
$pd = new Product();
$message = '';
if (isset($_GET['productId'])) {
	$id = $_GET['productId'];
	$delpro = $pd->delete_product($id);
	$message = $delpro['message'];
}
?>
<div class="content">
	<div class="box">
		<h2>Danh sách sản phẩm</h2>
		<?php if ($message): ?>
			<div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
		<table class="data-table">
			<thead>
				<tr>
					<th>STT</th>
					<th>Tên sản phẩm</th>
					<th>Giá</th>
					<th>Hình ảnh</th>
					<th>Danh mục</th>
					<th>Thương hiệu</th>
					<th>Mô tả</th>
					<th>Loại</th>
					<th>Hành động</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$pdlist = $pd->show_product();
				if ($pdlist) {
					$i = 0;
					while ($result = $pdlist->fetch_assoc()) {
						$i++;
				?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $result['product_name']; ?></td>
							<td><?php echo number_format($result['price'], 0, ',', '.') . ' VND'; ?></td>
							<td><img src="uploads/<?php echo $result['image']; ?>" alt="Product Image" width="80"></td>
							<td><?php echo $result['cat_name']; ?></td>
							<td><?php echo $result['brand_name']; ?></td>
							<td><?php echo substr($result['product_desc'], 0, 50) . '...'; ?></td>
							<td><?php echo $result['type'] == 0 ? 'Non-Featured' : 'Featured'; ?></td>
							<td>
								<a href="productedit.php?productId=<?php echo $result['product_id']; ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Sửa</a>
								<a href="?productId=<?php echo $result['product_id']; ?>" class="btn btn-delete"><i class="fas fa-trash"></i> Xóa</a>
							</td>
						</tr>
				<?php
					}
				}
				?>
			</tbody>
		</table>
	</div>
</div>
<?php include 'inc/footer.php'; ?>