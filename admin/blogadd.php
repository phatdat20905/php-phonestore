<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Blog.php'; ?>
<?php include '../classes/PostCategory.php'; ?>
<?php
$blog = new Blog();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $insertBlog = $blog->insert_blog($_POST, $_FILES);
    $message = $insertBlog['message'];
}
?>
<div class="content">
    <div class="box">
        <h2>Thêm tin tức</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="blogadd.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="blog_title">Tiêu đề:</label>
                <input type="text" id="blog_title" name="blog_title" placeholder="Nhập tiêu đề..." required>
            </div>
            <div class="form-group">
                <label for="post_cat_id">Danh mục tin tức:</label>
                <select id="post_cat_id" name="post_cat_id" required>
                    <option value="">Chọn danh mục</option>
                    <?php
                    $post = new PostCategory();
                    $postlist = $post->show_category();
                    if ($postlist) {
                        while ($result = $postlist->fetch_assoc()) {
                    ?>
                            <option value="<?php echo $result['post_cat_id']; ?>"><?php echo $result['title']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Mô tả ngắn:</label>
                <input type="text" id="description" name="description" placeholder="Nhập mô tả ngắn...">
            </div>
            <div class="form-group">
                <label for="content">Nội dung:</label>
                <textarea id="content" name="content" class="tinymce" placeholder="Nhập nội dung..."></textarea>
            </div>
            <div class="form-group">
                <label for="image">Hình ảnh:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="status">Trạng thái:</label>
                <select id="status" name="status" required>
                    <option value="">Chọn trạng thái</option>
                    <option value="0">Ẩn</option>
                    <option value="1">Hiển thị</option>
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