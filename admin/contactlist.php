<?php
include 'inc/header.php';
include 'inc/sidebar.php';
include '../classes/Contact.php';

$contact = new Contact();
$message = '';
if (isset($_GET['delId'])) {
    $id = $_GET['delId'];
    $delContact = $contact->delete_contact($id);
    $message = $delContact['message'];
}

if (isset($_GET['updateId'])) {
    $id = $_GET['updateId'];
    $status = $_GET['status'];
    $updateContact = $contact->update_status($id, $status);
    $message = $updateContact['message'];
}
?>

<div class="content">
    <div class="box">
        <h2>Danh sách liên hệ</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Tin nhắn</th>
                    <th>Ngày gửi</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $contact_list = $contact->show_contact();
                if ($contact_list && $contact_list->num_rows > 0) {
                    $i = 0;
                    while ($result = $contact_list->fetch_assoc()) {
                        $i++;
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $result['name']; ?></td>
                            <td><?php echo $result['email']; ?></td>
                            <td><?php echo $result['phone']; ?></td>
                            <td><?php echo substr($result['message'], 0, 50) . '...'; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($result['created_at'])); ?></td>
                            <td><?php echo $result['status'] == 0 ? 'Chưa xử lý' : 'Đã xử lý'; ?></td>
                            <td>
                                <a href="?updateId=<?php echo $result['contact_id']; ?>&status=<?php echo $result['status'] == 0 ? 1 : 0; ?>" class="btn btn-edit">
                                    <i class="fas fa-edit"></i> <?php echo $result['status'] == 0 ? 'Xử lý' : 'Hủy'; ?>
                                </a>
                                <a href="?delId=<?php echo $result['contact_id']; ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc muốn xóa liên hệ này?');">
                                    <i class="fas fa-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='8'>Chưa có liên hệ nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'inc/footer.php'; ?>