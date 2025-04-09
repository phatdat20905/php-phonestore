<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Category.php'; ?>
<?php
$cat = new Category();
$message = '';
if (isset($_GET['delId'])) {
	$id = $_GET['delId'];
	$delcat = $cat->delete_category($id);
	$message = $delcat['message'];
}
?>
<div class="content">
	<div class="box">
		<h2>Danh sách danh mục sản phẩm</h2>
		<?php if ($message): ?>
			<div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
		<table class="data-table">
			<thead>
				<tr>
					<th>STT</th>
					<th>Tên danh mục</th>
					<th>Hành động</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$catlist = $cat->show_category();
				if ($catlist) {
					$i = 0;
					while ($result = $catlist->fetch_assoc()) {
						$i++;
				?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $result['cat_name']; ?></td>
							<td>
								<a href="catedit.php?catId=<?php echo $result['cat_id']; ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Sửa</a>
								<a href="?delId=<?php echo $result['cat_id']; ?>" class="btn btn-delete"><i class="fas fa-trash"></i> Xóa</a>
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