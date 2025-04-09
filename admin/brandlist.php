<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Brand.php'; ?>
<?php
$brand = new Brand();
$message = '';
if (isset($_GET['delId'])) {
	$id = $_GET['delId'];
	$delbrand = $brand->delete_brand($id);
	$message = $delbrand['message'];
}
?>
<div class="content">
	<div class="box">
		<h2>Danh sách thương hiệu sản phẩm</h2>
		<?php if ($message): ?>
			<div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
		<table class="data-table">
			<thead>
				<tr>
					<th>STT</th>
					<th>Tên thương hiệu</th>
					<th>Hành động</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$brandlist = $brand->show_brand();
				if ($brandlist) {
					$i = 0;
					while ($result = $brandlist->fetch_assoc()) {
						$i++;
				?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $result['brand_name']; ?></td>
							<td>
								<a href="brandedit.php?brandId=<?php echo $result['brand_id']; ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Sửa</a>
								<a href="?delId=<?php echo $result['brand_id']; ?>" class="btn btn-delete"><i class="fas fa-trash"></i> Xóa</a>
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