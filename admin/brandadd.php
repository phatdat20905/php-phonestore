<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Brand.php'; ?>
<?php
$brand = new Brand();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_name = $_POST['brand_name'];
    $insertBrand = $brand->insert_brand($brand_name);
    $message = $insertBrand['message'];
}
?>
<div class="content">
    <div class="box">
        <h2>Thêm thương hiệu sản phẩm</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="brandadd.php" method="post">
            <div class="form-group">
                <label for="brand_name">Tên thương hiệu:</label>
                <input type="text" id="brand_name" name="brand_name" placeholder="Nhập tên thương hiệu..." required>
            </div>
            <button type="submit" class="btn"><i class="fas fa-save"></i> Lưu</button>
        </form>
    </div>
</div>
<?php include 'inc/footer.php'; ?>