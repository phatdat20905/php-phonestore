<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Compare.php'; ?>
<?php
$compare = new Compare();
$message = '';
if (isset($_GET['delId'])) {
    $id = $_GET['delId'];
    $delCompare = $compare->del_compare($id);
    $message = $delCompare ? 'Xóa mục so sánh thành công!' : 'Xóa mục so sánh thất bại!';
}
?>
<div class="content">
    <div class="box">
        <h2>Danh sách so sánh</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên khách hàng</th>
                    <th>Tên sản phẩm</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $comparelist = $compare->get_all_compare();
                if ($comparelist) {
                    $i = 0;
                    while ($result = $comparelist->fetch_assoc()) {
                        $i++;
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $result['customer_name']; ?></td>
                            <td><?php echo $result['product_name']; ?></td>
                            <td><?php echo $result['created_at']; ?></td>
                            <td>
                                <a href="?delId=<?php echo $result['compare_id']; ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?');"><i class="fas fa-trash"></i> Xóa</a>
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