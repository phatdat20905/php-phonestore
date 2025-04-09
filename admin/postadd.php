<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/PostCategory.php'; ?>
<?php
$post = new PostCategory();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $insertPost = $post->insert_category($title, $description, $status);
    $message = $insertPost['message'];
}
?>
<div class="content">
    <div class="box">
        <h2>Thêm danh mục tin tức</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="postadd.php" method="post">
            <div class="form-group">
                <label for="title">Tên danh mục:</label>
                <input type="text" id="title" name="title" placeholder="Nhập tên danh mục..." required>
            </div>
            <div class="form-group">
                <label for="description">Mô tả:</label>
                <input type="text" id="description" name="description" placeholder="Nhập mô tả...">
            </div>
            <div class="form-group">
                <label for="status">Trạng thái:</label>
                <select id="status" name="status" required>
                    <option value="">Chọn trạng thái</option>
                    <option value="0">Ẩn</option>
                    <option value="1">Hiển thị</option>
                </select>
            </div>
            <button type="submit" class="btn"><i class="fas fa-save"></i> Lưu</button>
        </form>
    </div>
</div>
<?php include 'inc/footer.php'; ?>