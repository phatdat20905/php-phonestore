<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Customer.php'; ?>
<?php
$customer = new Customer();
$message = '';
if (isset($_GET['delId'])) {
    $id = $_GET['delId'];
    $delCustomer = $customer->delete_customer($id);
    $message = $delCustomer['message'];
}
?>
<div class="content">
    <div class="box">
        <h2>Danh sách khách hàng</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Địa chỉ</th>
                    <th>Ảnh</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $customerlist = $customer->show_customer();
                if ($customerlist) {
                    $i = 0;
                    while ($result = $customerlist->fetch_assoc()) {
                        $i++;
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $result['name']; ?></td>
                            <td><?php echo $result['email']; ?></td>
                            <td><?php echo $result['phone']; ?></td>
                            <td><?php echo $result['address']; ?></td>
                            <td><?php echo $result['image'] ? '<img src="../uploads/' . $result['image'] . '" width="50">' : 'Không có'; ?></td>
                            <td><?php echo $result['created_at']; ?></td>
                            <td>
                                <a href="customeredit.php?customer_id=<?php echo $result['customer_id']; ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Sửa</a>
                                <a href="?delId=<?php echo $result['customer_id']; ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?');"><i class="fas fa-trash"></i> Xóa</a>
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