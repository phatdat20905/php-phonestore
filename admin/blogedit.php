<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Blog.php'; ?>
<?php include '../classes/PostCategory.php'; ?>
<?php
$blog = new Blog();
if (!isset($_GET['blogId']) || empty($_GET['blogId'])) {
    echo "<script>window.location='bloglist.php';</script>";
    exit;
}
$id = $_GET['blogId'];
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $insert_data = $_POST;
    $insert_data['current_image'] = $_POST['current_image'];
    $updateBlog = $blog->update_blog($insert_data, $_FILES, $id);
    $message = $updateBlog['message'];
}
$blog_data = $blog->get_blog_by_id($id);
if (!$blog_data) {
    echo "<script>window.location='bloglist.php';</script>";
    exit;
}
$result = $blog_data->fetch_assoc();
?>
<div class="content">
    <div class="box">
        <h2>Sửa tin tức</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="blog_title">Tiêu đề:</label>
                <input type="text" id="blog_title" name="blog_title" value="<?php echo $result['blog_title']; ?>" required>
            </div>
            <div class="form-group">
                <label for="post_cat_id">Danh mục tin tức:</label>
                <select id="post_cat_id" name="post_cat_id" required>
                    <option value="">Chọn danh mục</option>
                    <?php
                    $post = new PostCategory();
                    $postlist = $post->show_category();
                    if ($postlist) {
                        while ($post_result = $postlist->fetch_assoc()) {
                    ?>
                            <option value="<?php echo $post_result['post_cat_id']; ?>" <?php if ($post_result['post_cat_id'] == $result['post_cat_id']) echo 'selected'; ?>><?php echo $post_result['title']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Mô tả ngắn:</label>
                <input type="text" id="description" name="description" value="<?php echo $result['description']; ?>">
            </div>
            <div class="form-group">
                <label for="content">Nội dung:</label>
                <textarea id="content" name="content" class="tinymce"><?php echo $result['content']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="image">Hình ảnh:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <input type="hidden" name="current_image" value="<?php echo $result['image']; ?>">
                <img src="uploads/<?php echo $result['image']; ?>" alt="Current Image" width="100" style="margin-top: 10px;">
            </div>
            <div class="form-group">
                <label for="status">Trạng thái:</label>
                <select id="status" name="status" required>
                    <option value="0" <?php if ($result['status'] == 0) echo 'selected'; ?>>Ẩn</option>
                    <option value="1" <?php if ($result['status'] == 1) echo 'selected'; ?>>Hiển thị</option>
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