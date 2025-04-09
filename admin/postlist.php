<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/PostCategory.php'; ?>
<?php
$post = new PostCategory();
$message = '';
if (isset($_GET['delId'])) {
	$id = $_GET['delId'];
	$delPost = $post->delete_category($id);
	$message = $delPost['message'];
}
?>
<div class="content">
	<div class="box">
		<h2>Danh sách danh mục tin tức</h2>
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
					<th>Mô tả</th>
					<th>Trạng thái</th>
					<th>Hành động</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$postlist = $post->show_category();
				if ($postlist) {
					$i = 0;
					while ($result = $postlist->fetch_assoc()) {
						$i++;
				?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $result['title']; ?></td>
							<td><?php echo $result['description']; ?></td>
							<td><?php echo $result['status'] == 0 ? 'Ẩn' : 'Hiển thị'; ?></td>
							<td>
								<a href="postedit.php?postId=<?php echo $result['post_cat_id']; ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Sửa</a>
								<a href="?delId=<?php echo $result['post_cat_id']; ?>" class="btn btn-delete"><i class="fas fa-trash"></i> Xóa</a>
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