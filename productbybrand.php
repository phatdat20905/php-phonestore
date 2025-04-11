<?php
include 'inc/header.php';
include 'inc/slider.php';
?>

<?php
if (!isset($_GET['brandId']) || $_GET['brandId'] == NULL) {
    echo "<script>window.location='404.php';</script>";
} else {
    $id = $_GET['brandId'];
}

// Phân trang
$products_per_page = 8; // Số sản phẩm mỗi trang
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $products_per_page;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['themgiohang'])) {
    $product_stock = $inventory->get_quantity_by_product($_POST['product_id']);
    $stock = ($product_stock && $product_stock->num_rows > 0) ? $product_stock->fetch_assoc()['quantity'] : 0;
    $quantity = $_POST['quantity'];
    $id = $_POST['product_id'];
    $AddtoCart = $ct->add_to_cart($quantity, $id);
    if ($AddtoCart) {
        echo "<script>window.location='cart.php';</script>";
    }
}
?>

<div class="main">
    <div class="content">
        <div class="content_top">
            <div class="heading">
                <h3>Thương hiệu: <?php echo $brand->get_brand_name($id); ?></h3>
            </div>
            <div class="clear"></div>
        </div>
        <div class="section group">
            <?php
            $products_by_brand = $product->get_product_by_brand($id, $products_per_page, $offset);
            if ($products_by_brand && $products_by_brand->num_rows > 0) {
                while ($result = $products_by_brand->fetch_assoc()) {
                    $inventory_qty = $inventory->get_quantity_by_product($result['product_id']);
                    $stock = ($inventory_qty && $inventory_qty->num_rows > 0) ? $inventory_qty->fetch_assoc()['quantity'] : 0;
            ?>
                    <div class="grid_1_of_4 images_1_of_4">
                        <a href="details.php?proid=<?php echo $result['product_id']; ?>">
                            <img src="admin/uploads/<?php echo $result['image']; ?>" alt="<?php echo $result['product_name']; ?>" />
                        </a>
                        <h2><?php echo $result['product_name']; ?></h2>
                        <p><?php echo $fm->textShorten($result['product_desc'], 30); ?></p>
                        <p><span class="price"><?php echo number_format($result['price'], 0, ',', '.') . ' VND'; ?></span></p>
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?php echo $result['product_id']; ?>">
                            <input type="number" name="quantity" value="1" min="1" max="<?php echo $stock; ?>">
                            <input type="submit" name="themgiohang" value="Thêm giỏ hàng" class="button" />
                        </form>
                        <div class="button"><span><a href="details.php?proid=<?php echo $result['product_id']; ?>" class="details">Chi tiết</a></span></div>
                    </div>
            <?php
                }
            } else {
                echo "<p style='text-align: center;'>No product found in this brand.</p>";
            }
            ?>
        </div>

        <!-- Phân trang -->
        <?php
        $total_products = $product->count_products_by_brand($id);
        $total_pages = ceil($total_products / $products_per_page);
        if ($total_pages > 1) {
        ?>
            <div class="pagination">
                <?php if ($page > 1) { ?>
                    <a href="productbybrand.php?brandId=<?php echo $id; ?>&page=<?php echo $page - 1; ?>" class="page-link">« Trước</a>
                <?php } ?>
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <a href="productbybrand.php?brandId=<?php echo $id; ?>&page=<?php echo $i; ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php } ?>
                <?php if ($page < $total_pages) { ?>
                    <a href="productbybrand.php?brandId=<?php echo $id; ?>&page=<?php echo $page + 1; ?>" class="page-link">Tiếp »</a>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>

<style>
    .pagination {
        text-align: center;
        margin-top: 20px;
    }

    .page-link {
        display: inline-block;
        padding: 8px 12px;
        margin: 0 5px;
        text-decoration: none;
        color: #ff6600;
        border: 1px solid #ddd;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background: #ff6600;
        color: #fff;
        border-color: #ff6600;
    }

    .page-link.active {
        background: #ff6600;
        color: #fff;
        border-color: #ff6600;
    }
</style>

<?php
include 'inc/footer.php';
?>