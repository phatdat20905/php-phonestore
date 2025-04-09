<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Product.php'; ?>
<?php include '../classes/Category.php'; ?>
<?php include '../classes/Brand.php'; ?>
<?php
$pd = new Product();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $insertProduct = $pd->insert_product($_POST, $_FILES);
    $message = $insertProduct['message'];
}
?>
<div class="content">
    <div class="box">
        <h2>Thêm sản phẩm</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="productadd.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_name">Tên sản phẩm:</label>
                <input type="text" id="product_name" name="product_name" placeholder="Nhập tên sản phẩm..." required>
            </div>
            <div class="form-group">
                <label for="cat_id">Danh mục:</label>
                <select id="cat_id" name="cat_id" required>
                    <option value="">Chọn danh mục</option>
                    <?php
                    $cat = new Category();
                    $catlist = $cat->show_category();
                    if ($catlist) {
                        while ($result = $catlist->fetch_assoc()) {
                    ?>
                            <option value="<?php echo $result['cat_id']; ?>"><?php echo $result['cat_name']; ?></option>
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
                        while ($result = $brandlist->fetch_assoc()) {
                    ?>
                            <option value="<?php echo $result['brand_id']; ?>"><?php echo $result['brand_name']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="product_desc">Mô tả:</label>
                <textarea id="product_desc" name="product_desc" class="tinymce" placeholder="Nhập mô tả..."></textarea>
            </div>
            <div class="form-group">
                <label for="price">Giá:</label>
                <input type="text" id="price" name="price" placeholder="Nhập giá..." required>
            </div>
            <div class="form-group">
                <label for="image">Hình ảnh:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="type">Loại sản phẩm:</label>
                <select id="type" name="type" required>
                    <option value="">Chọn loại</option>
                    <option value="0">Non-Featured</option>
                    <option value="1">Featured</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn"><i class="fas fa-save"></i> Lưu</button>
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