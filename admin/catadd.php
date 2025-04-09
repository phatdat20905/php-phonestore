<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Category.php'; ?>
<?php
$cat = new Category();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_name = $_POST['cat_name'];
    $insertCat = $cat->insert_category($cat_name);
    $message = $insertCat['message'];
}
?>
<div class="content">
    <div class="box">
        <h2>Thêm danh mục sản phẩm</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="catadd.php" method="post">
            <div class="form-group">
                <label for="cat_name">Tên danh mục:</label>
                <input type="text" id="cat_name" name="cat_name" placeholder="Nhập tên danh mục..." required>
            </div>
            <button type="submit" class="btn"><i class="fas fa-save"></i> Lưu</button>
        </form>
    </div>
</div>
<?php include 'inc/footer.php'; ?>