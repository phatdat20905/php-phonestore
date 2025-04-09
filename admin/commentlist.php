<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Comment.php'; ?>
<?php
$comment = new Comment();
$message = '';
if (isset($_GET['approveId'])) {
    $id = $_GET['approveId'];
    $approve = $comment->approve_comment($id);
    $message = $approve['message'];
} elseif (isset($_GET['delId'])) {
    $id = $_GET['delId'];
    $delete = $comment->delete_comment($id);
    $message = $delete['message'];
}
?>
<div class="content">
    <div class="box">
        <h2>Danh sách bình luận</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Sản phẩm</th>
                    <th>Khách hàng</th>
                    <th>Nội dung</th>
                    <th>Ngày</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $commentlist = $comment->show_comments();
                if ($commentlist) {
                    $i = 0;
                    while ($result = $commentlist->fetch_assoc()) {
                        $i++;
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $result['product_name']; ?></td>
                            <td><?php echo $result['customer_name']; ?></td>
                            <td><?php echo $result['content']; ?></td>
                            <td><?php echo $result['created_at']; ?></td>
                            <td><?php echo $result['status'] == 0 ? 'Chưa duyệt' : 'Đã duyệt'; ?></td>
                            <td>
                                <?php if ($result['status'] == 0): ?>
                                    <a href="?approveId=<?php echo $result['comment_id']; ?>" class="btn btn-edit"><i class="fas fa-check"></i> Duyệt</a>
                                <?php endif; ?>
                                <a href="?delId=<?php echo $result['comment_id']; ?>" class="btn btn-delete"><i class="fas fa-trash"></i> Xóa</a>
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