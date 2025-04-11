<?php
include 'inc/header.php';
include 'inc/slider.php';
?>

<?php
if (!isset($_GET['blogId']) || $_GET['blogId'] == NULL) {
    echo "<script>window.location='404.php';</script>";
} else {
    $id = $_GET['blogId'];
}

$blog_detail = $blog->get_blog_by_id($id);
if (!$blog_detail || $blog_detail->num_rows == 0) {
    echo "<script>window.location='404.php';</script>";
} else {
    $result = $blog_detail->fetch_assoc();
}
?>

<div class="main">
    <div class="content">
        <div class="content_top">
            <div class="heading">
                <h3><?php echo $result['blog_title']; ?></h3>
            </div>
            <div class="clear"></div>
        </div>
        <div class="section group">
            <div class="blog_detail">
                <div class="blog_image">
                    <img src="admin/uploads/<?php echo $result['image']; ?>" alt="<?php echo $result['blog_title']; ?>" />
                </div>
                <div class="blog_info">
                    <p><strong>Danh mục:</strong> <?php echo $postCat->get_category_name($result['post_cat_id']); ?></p>
                    <p><strong>Mô tả:</strong> <?php echo $result['description']; ?></p>
                    <div class="blog_content">
                        <h4>Nội dung:</h4>
                        <?php echo $result['content']; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="back_link">
            <a href="blogbycat.php?postCatId=<?php echo $result['post_cat_id']; ?>" class="button">Quay lại danh mục</a>
        </div>
    </div>
</div>

<?php
include 'inc/footer.php';
?>

<style>
    .blog_detail {
        width: 100%;
        margin: 20px 0;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .blog_image {
        flex: 1;
        min-width: 300px;
    }

    .blog_image img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
    }

    .blog_info {
        flex: 2;
        min-width: 300px;
    }

    .blog_info p {
        margin: 10px 0;
        font-size: 16px;
    }

    .blog_content {
        margin-top: 20px;
    }

    .blog_content h4 {
        font-size: 18px;
        margin-bottom: 10px;
        color: #4203a9;
    }

    .back_link {
        margin-top: 20px;
        text-align: center;
    }

    .back_link .button {
        padding: 10px 20px;
        background: #4203a9;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .back_link .button:hover {
        background: #35028c;
    }
</style>