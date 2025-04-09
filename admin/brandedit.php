<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Brand.php'; ?>
<?php
$brand = new Brand();
if (!isset($_GET['brandId']) || empty($_GET['brandId'])) {
    echo "<script>window.location='brandlist.php';</script>";
    exit;
}
$id = $_GET['brandId'];
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_name = $_POST['brand_name'];
    $updateBrand = $brand->update_brand($brand_name, $id);
    $message = $updateBrand['message'];
}
$brand_data = $brand->get_brand_by_id($id);
if (!$brand_data) {
    echo "<script>window.location='brandlist.php';</script>";
    exit;
}
$result = $brand_data->fetch_assoc();
?>
<div class="content">
    <div class="box">
        <h2>Sửa thương hiệu sản phẩm</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="brand_name">Tên thương hiệu:</label>
                <input type="text" id="brand_name" name="brand_name" value="<?php echo $result['brand_name']; ?>" required>
            </div>
            <button type="submit" class="btn"><i class="fas fa-save"></i> Cập nhật</button>
        </form>
    </div>
</div>
<?php include 'inc/footer.php'; ?>