<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Product.php'; ?>
<?php include '../classes/Category.php'; ?>
<?php include '../classes/Brand.php'; ?>
<?php
$pd = new Product();
if (!isset($_GET['productId']) || empty($_GET['productId'])) {
    echo "<script>window.location='productlist.php';</script>";
    exit;
}
$id = $_GET['productId'];
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $insert_data = $_POST;
    $insert_data['current_image'] = $_POST['current_image'];
    $updateProduct = $pd->update_product($insert_data, $_FILES, $id);
    $message = $updateProduct['message'];
}
$product_data = $pd->get_product_by_id($id);
if (!$product_data) {
    echo "<script>window.location='productlist.php';</script>";
    exit;
}
$result = $product_data->fetch_assoc();
?>
<div class="content">
    <div class="box">
        <h2>Sửa sản phẩm</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_name">Tên sản phẩm:</label>
                <input type="text" id="product_name" name="product_name" value="<?php echo $result['product_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="cat_id">Danh mục:</label>
                <select id="cat_id" name="cat_id" required>
                    <option value="">Chọn danh mục</option>
                    <?php
                    $cat = new Category();
                    $catlist = $cat->show_category();
                    if ($catlist) {
                        while ($cat_result = $catlist->fetch_assoc()) {
                    ?>
                            <option value="<?php echo $cat_result['cat_id']; ?>" <?php if ($cat_result['cat_id'] == $result['cat_id']) echo 'selected'; ?>><?php echo $cat_result['cat_name']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="brand_id">Thương hiệu:</label>
                <select id="brand_id" name="brand_id" required>
                    <option value="">Chọn thương hiệu</option>
                    <?php
                    $brand = new Brand();
                    $brandlist = $brand->show_brand();
                    if ($brandlist) {
                        while ($brand_result = $brandlist->fetch_assoc()) {
                    ?>
                            <option value="<?php echo $brand_result['brand_id']; ?>" <?php if ($brand_result['brand_id'] == $result['brand_id']) echo 'selected'; ?>><?php echo $brand_result['brand_name']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="product_desc">Mô tả:</label>
                <textarea id="product_desc" name="product_desc" class="tinymce"><?php echo $result['product_desc']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Giá:</label>
                <input type="text" id="price" name="price" value="<?php echo $result['price']; ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Hình ảnh:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <input type="hidden" name="current_image" value="<?php echo $result['image']; ?>">
                <img src="uploads/<?php echo $result['image']; ?>" alt="Current Image" width="100" style="margin-top: 10px;">
            </div>
            <div class="form-group">
                <label for="type">Loại sản phẩm:</label>
                <select id="type" name="type" required>
                    <option value="0" <?php if ($result['type'] == 0) echo 'selected'; ?>>Non-Featured</option>
                    <option value="1" <?php if ($result['type'] == 1) echo 'selected'; ?>>Featured</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn"><i class="fas fa-save"></i> Cập nhật</button>
        </form>
    </div>
</div>
<script src="https://cdn.tiny.cloud/1/vukmun3n8gubat35b16n5hf9ydy5ad8jttk5tod69hwx8z2s/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea.tinymce',
        height: 300,
        menubar: false,
        plugins: ['advlist autolink lists link image charmap print preview anchor', 'searchreplace visualblocks code fullscreen', 'insertdatetime media table paste code help wordcount'],
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code'
    });
</script>
<?php include 'inc/footer.php'; ?>