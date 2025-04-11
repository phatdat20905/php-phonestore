<?php
include 'inc/header.php';
include 'inc/slider.php';
?>

<?php
if (!isset($_GET['postCatId']) || $_GET['postCatId'] == NULL) {
    echo "<script>window.location='404.php';</script>";
} else {
    $id = $_GET['postCatId'];
}
?>

<div class="main">
    <div class="content">
        <div class="content_top">
            <div class="heading">
                <h3>Danh mục tin tức: <?php echo $postCat->get_category_name($id); ?></h3>
            </div>
            <div class="clear"></div>
        </div>
        <div class="section group">
            <?php
            $blogs_by_cat = $blog->get_blogs_by_category($id);
            if ($blogs_by_cat && $blogs_by_cat->num_rows > 0) {
                while ($result = $blogs_by_cat->fetch_assoc()) {
            ?>
                    <div class="grid_1_of_4 images_1_of_4">
                        <a href="blog_details.php?blogId=<?php echo $result['blog_id']; ?>">
                            <img src="admin/uploads/<?php echo $result['image']; ?>" alt="<?php echo $result['blog_title']; ?>" />
                        </a>
                        <h2><?php echo $result['blog_title']; ?></h2>
                        <p><?php echo $fm->textShorten($result['description'], 30); ?></p>
                        <div class="button"><span><a href="blog_details.php?blogId=<?php echo $result['blog_id']; ?>" class="details">Chi tiết</a></span></div>
                    </div>
            <?php
                }
            } else {
                echo "<p style='text-align: center;'>No posts found in this category.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php
include 'inc/footer.php';
?>