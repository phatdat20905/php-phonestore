<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Customer.php'; ?>
<?php
$customer = new Customer();
if (!isset($_GET['customer_id']) || empty($_GET['customer_id'])) {
    echo "<script>window.location='customerlist.php';</script>";
    exit;
}
$id = $_GET['customer_id'];
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $updateCustomer = $customer->update_customer($_POST, $_FILES, $id);
    $message = $updateCustomer['message'];
}
$customer_data = $customer->get_customer_by_id($id);
if (!$customer_data) {
    echo "<script>window.location='customerlist.php';</script>";
    exit;
}
$result = $customer_data->fetch_assoc();
?>
<div class="content">
    <div class="box">
        <h2>Sửa khách hàng</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Tên:</label>
                <input type="text" id="name" name="name" value="<?php echo $result['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $result['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="text" id="phone" name="phone" value="<?php echo $result['phone']; ?>">
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <input type="text" id="address" name="address" value="<?php echo $result['address']; ?>">
            </div>
            <div class="form-group">
                <label for="city">Thành phố:</label>
                <input type="text" id="city" name="city" value="<?php echo $result['city']; ?>">
            </div>
            <div class="form-group">
                <label for="country">Quốc gia:</label>
                <input type="text" id="country" name="country" value="<?php echo $result['country']; ?>">
            </div>
            <div class="form-group">
                <label for="zipcode">Mã bưu điện:</label>
                <input type="text" id="zipcode" name="zipcode" value="<?php echo $result['zipcode']; ?>">
            </div>
            <div class="form-group">
                <label for="image">Ảnh hiện tại:</label>
                <img src="uploads/<?php echo $result['image']; ?>" alt="Customer Image" width="80"><br>
                <label for="image">Thay ảnh:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <input type="hidden" name="current_image" value="<?php echo $result['image']; ?>">
            </div>
            <button type="submit" name="submit" class="btn"><i class="fas fa-save"></i> Cập nhật</button>
        </form>
    </div>
</div>
<?php include 'inc/footer.php'; ?>