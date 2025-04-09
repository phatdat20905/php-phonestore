<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Admin.php'; ?>
<?php include_once($filepath . '/../lib/Session.php'); ?>
<?php
Session::init();
$admin = new Admin();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $admin_id = Session::get('adminId');
    if (!$admin_id) {
        header("Location: login.php");
        exit;
    }
    $changePass = $admin->change_password($admin_id, $old_password, $new_password, $confirm_password);
    $message = $changePass['message'];
}
?>
<div class="content">
    <div class="box">
        <h2>Đổi mật khẩu</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="changepassword.php" method="post">
            <div class="form-group">
                <label for="old_password">Mật khẩu cũ:</label>
                <input type="password" id="old_password" name="old_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">Mật khẩu mới:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn"><i class="fas fa-save"></i> Cập nhật</button>
        </form>
    </div>
</div>
<?php include 'inc/footer.php'; ?>