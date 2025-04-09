<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/PostCategory.php'; ?>
<?php
$post = new PostCategory();
if (!isset($_GET['postId']) || empty($_GET['postId'])) {
    echo "<script>window.location='postlist.php';</script>";
    exit;
}
$id = $_GET['postId'];
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $updatePost = $post->update_category($title, $description, $status, $id);
    $message = $updatePost['message'];
}
$post_data = $post->get_category_by_id($id);
if (!$post_data) {
    echo "<script>window.location='postlist.php';</script>";
    exit;
}
$result = $post_data->fetch_assoc();
?>
<div class="content">
    <div class="box">
        <h2>Sửa danh mục tin tức</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="title">Tên danh mục:</label>
                <input type="text" id="title" name="title" value="<?php echo $result['title']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Mô tả:</label>
                <input type="text" id="description" name="description" value="<?php echo $result['description']; ?>">
            </div>
            <div class="form-group">
                <label for="status">Trạng thái:</label>
                <select id="status" name="status" required>
                    <option value="0" <?php if ($result['status'] == 0) echo 'selected'; ?>>Ẩn</option>
                    <option value="1" <?php if ($result['status'] == 1) echo 'selected'; ?>>Hiển thị</option>
                </select>
            </div>
            <button type="submit" class="btn"><i class="fas fa-save"></i> Cập nhật</button>
        </form>
    </div>
</div>
<?php include 'inc/footer.php'; ?>