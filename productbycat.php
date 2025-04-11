<?php
include 'inc/header.php';
include 'inc/slider.php';
?>

<?php
if (!isset($_GET['catId']) || $_GET['catId'] == NULL) {
	echo "<script>window.location='404.php';</script>";
} else {
	$id = $_GET['catId'];
}

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
				<h3>Danh mục sản phẩm: <?php echo $cat->get_category_name($id); ?></h3>
			</div>
			<div class="clear"></div>
		</div>
		<div class="section group">
			<?php
			$products_by_cat = $product->get_product_by_cat($id);
			if ($products_by_cat && $products_by_cat->num_rows > 0) {
				while ($result = $products_by_cat->fetch_assoc()) {
					// Lấy số lượng từ bảng inventory
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
				echo "<p style='text-align: center;'>No product found in this category.</p>";
			}
			?>
		</div>
	</div>
</div>

<?php
include 'inc/footer.php';
?>