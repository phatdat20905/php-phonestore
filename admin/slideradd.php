<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Slider.php'; ?>
<?php
$slider = new Slider();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $insertSlider = $slider->insert_slider($_POST, $_FILES);
    $message = $insertSlider['message'];
}
?>
<div class="content">
    <div class="box">
        <h2>Thêm slider</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="slideradd.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="slider_name">Tên slider:</label>
                <input type="text" id="slider_name" name="slider_name" placeholder="Nhập tên slider..." required>
            </div>
            <div class="form-group">
                <label for="image">Hình ảnh:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="type">Trạng thái:</label>
                <select id="type" name="type" required>
                    <option value="">Chọn trạng thái</option>
                    <option value="0">Tắt</option>
                    <option value="1">Bật</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn"><i class="fas fa-save"></i> Lưu</button>
        </form>
    </div>
</div>
<?php include 'inc/footer.php'; ?>