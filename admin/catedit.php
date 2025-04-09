<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Category.php'; ?>
<?php
$cat = new Category();
if (!isset($_GET['catId']) || empty($_GET['catId'])) {
    echo "<script>window.location='catlist.php';</script>";
    exit;
}
$id = $_GET['catId'];
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_name = $_POST['cat_name'];
    $updateCat = $cat->update_category($cat_name, $id);
    $message = $updateCat['message'];
}
$cat_data = $cat->get_category_by_id($id);
if (!$cat_data) {
    echo "<script>window.location='catlist.php';</script>";
    exit;
}
$result = $cat_data->fetch_assoc();
?>
<div class="content">
    <div class="box">
        <h2>Sửa danh mục sản phẩm</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="cat_name">Tên danh mục:</label>
                <input type="text" id="cat_name" name="cat_name" value="<?php echo $result['cat_name']; ?>" required>
            </div>
            <button type="submit" class="btn"><i class="fas fa-save"></i> Cập nhật</button>
        </form>
    </div>
</div>
<?php include 'inc/footer.php'; ?>