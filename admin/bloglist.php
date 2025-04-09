<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Blog.php'; ?>
<?php
$blog = new Blog();
$message = '';
if (isset($_GET['delId'])) {
	$id = $_GET['delId'];
	$delBlog = $blog->delete_blog($id);
	$message = $delBlog['message'];
}
?>
<div class="content">
	<div class="box">
		<h2>Danh sách tin tức</h2>
		<?php if ($message): ?>
			<div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
		<table class="data-table">
			<thead>
				<tr>
					<th>STT</th>
					<th>Tiêu đề</th>
					<th>Danh mục</th>
					<th>Mô tả</th>
					<th>Hình ảnh</th>
					<th>Trạng thái</th>
					<th>Hành động</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$bloglist = $blog->show_blog();
				if ($bloglist) {
					$i = 0;
					while ($result = $bloglist->fetch_assoc()) {
						$i++;
				?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $result['blog_title']; ?></td>
							<td><?php echo $result['title']; ?></td>
							<td><?php echo substr($result['description'], 0, 50) . '...'; ?></td>
							<td><img src="uploads/<?php echo $result['image']; ?>" alt="Blog Image" width="80"></td>
							<td><?php echo $result['status'] == 0 ? 'Ẩn' : 'Hiển thị'; ?></td>
							<td>
								<a href="blogedit.php?blogId=<?php echo $result['blog_id']; ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Sửa</a>
								<a href="?delId=<?php echo $result['blog_id']; ?>" class="btn btn-delete"><i class="fas fa-trash"></i> Xóa</a>
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