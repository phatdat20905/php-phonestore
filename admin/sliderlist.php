<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Slider.php'; ?>
<?php
$slider = new Slider();
$message = '';
if (isset($_GET['delId'])) {
	$id = $_GET['delId'];
	$delSlider = $slider->delete_slider($id);
	$message = $delSlider['message'];
} elseif (isset($_GET['type']) && isset($_GET['id'])) {
	$id = $_GET['id'];
	$type = $_GET['type'];
	$updateSlider = $slider->update_slider($id, $type);
	$message = $updateSlider['message'];
}
?>
<div class="content">
	<div class="box">
		<h2>Danh sách slider</h2>
		<?php if ($message): ?>
			<div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
		<table class="data-table">
			<thead>
				<tr>
					<th>STT</th>
					<th>Tên slider</th>
					<th>Hình ảnh</th>
					<th>Trạng thái</th>
					<th>Hành động</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$sliderlist = $slider->show_slider();
				if ($sliderlist) {
					$i = 0;
					while ($result = $sliderlist->fetch_assoc()) {
						$i++;
				?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $result['slider_name']; ?></td>
							<td><img src="uploads/<?php echo $result['slider_image']; ?>" alt="Slider Image" width="80"></td>
							<td>
								<?php if ($result['type'] == 0): ?>
									<a href="?id=<?php echo $result['slider_id']; ?>&type=1" class="btn">Tắt</a>
								<?php else: ?>
									<a href="?id=<?php echo $result['slider_id']; ?>&type=0" class="btn btn-edit">Bật</a>
								<?php endif; ?>
							</td>
							<td>
								<a href="?delId=<?php echo $result['slider_id']; ?>" class="btn btn-delete"><i class="fas fa-trash"></i> Xóa</a>
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