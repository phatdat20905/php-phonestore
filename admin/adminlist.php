<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Admin.php'; ?>
<?php
$admin = new Admin();
$message = '';
if (isset($_GET['delId'])) {
    $id = $_GET['delId'];
    $delAdmin = $admin->delete_admin($id);
    $message = $delAdmin['message'];
}
?>
<div class="content">
    <div class="box">
        <h2>Danh sách Admin</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên đăng nhập</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Ảnh</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $adminlist = $admin->show_admin();
                if ($adminlist) {
                    $i = 0;
                    while ($result = $adminlist->fetch_assoc()) {
                        $i++;
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $result['username']; ?></td>
                            <td><?php echo $result['name']; ?></td>
                            <td><?php echo $result['email']; ?></td>
                            <td><?php echo $result['image'] ? '<img src="../uploads/' . $result['image'] . '" width="50">' : 'Không có'; ?></td>
                            <td><?php echo $result['created_at']; ?></td>
                            <td>
                                <a href="adminedit.php?admin_id=<?php echo $result['admin_id']; ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Sửa</a>
                                <a href="?delId=<?php echo $result['admin_id']; ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?');"><i class="fas fa-trash"></i> Xóa</a>
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