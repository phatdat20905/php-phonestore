<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Admin.php'; ?>
<?php
$admin = new Admin();
if (!isset($_GET['admin_id']) || empty($_GET['admin_id'])) {
    echo "<script>window.location='adminlist.php';</script>";
    exit;
}
$id = $_GET['admin_id'];
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $updateAdmin = $admin->update_admin($_POST, $_FILES, $id);
    $message = $updateAdmin['message'];
}
$admin_data = $admin->get_admin_by_id($id);
if (!$admin_data) {
    echo "<script>window.location='adminlist.php';</script>";
    exit;
}
$result = $admin_data->fetch_assoc();
?>
<div class="content">
    <div class="box">
        <h2>Sửa Admin</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" id="username" name="username" value="<?php echo $result['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="name">Họ tên:</label>
                <input type="text" id="name" name="name" value="<?php echo $result['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $result['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Ảnh hiện tại:</label>
                <?php echo $result['image'] ? '<img src="../uploads/' . $result['image'] . '" width="100">' : 'Không có'; ?><br>
                <label for="image">Thay ảnh:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <input type="hidden" name="current_image" value="<?php echo $result['image']; ?>">
            </div>
            <button type="submit" name="submit" class="btn"><i class="fas fa-save"></i> Cập nhật</button>
        </form>
    </div>
</div>
<?php include 'inc/footer.php'; ?>