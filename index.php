<?php
include 'inc/header.php';
include 'inc/slider.php';
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['themgiohang'])) {
	$quantity = $_POST['quantity'];
	$product_id = $_POST['product_id'];
	$result = $ct->add_to_cart($quantity, $product_id);
	if ($result === true) {
		echo "<script>window.location='cart.php';</script>";
	} else {
		echo "<p style='color: red;'>$result</p>";
	}
}
?>

<div class="main">
	<div class="content">
		<div class="content_top">
			<div class="heading">
				<h3>Sản phẩm nổi bật</h3>
			</div>
			<div class="clear"></div>
		</div>
		<div class="section group">
			<?php
			$featured_products = $product->get_featured_products();
			if ($featured_products) {
				while ($result = $featured_products->fetch_assoc()) {
			?>
					<div class="grid_1_of_4 images_1_of_4">
						<a href="details.php?proid=<?php echo $result['product_id']; ?>">
							<img src="admin/uploads/<?php echo $result['image']; ?>" alt="<?php echo $result['product_name']; ?>" />
						</a>
						<h2><?php echo $result['product_name']; ?></h2>
						<p><?php echo $fm->textShorten($result['product_desc'], 30); ?></p>
						<p><span class="price"><?php echo number_format($result['price'], 0, ',', '.'); ?> VND</span></p>
						<form method="post">
							<input type="hidden" name="product_id" value="<?php echo $result['product_id']; ?>">
							<input type="number" name="quantity" value="1" min="1">
							<input type="submit" name="themgiohang" value="Thêm giỏ hàng" class="button" />
						</form>
						<div class="button"><span><a href="details.php?proid=<?php echo $result['product_id']; ?>" class="details">Chi tiết</a></span></div>
					</div>
			<?php
				}
			}
			?>
		</div>
		<div class="content_bottom">
			<div class="heading">
				<h3>Sản phẩm mới</h3>
			</div>
			<div class="clear"></div>
		</div>
		<div class="section group">
			<?php
			$new_products = $product->get_new_products();
			if ($new_products) {
				while ($result = $new_products->fetch_assoc()) {
			?>
					<div class="grid_1_of_4 images_1_of_4">
						<a href="details.php?proid=<?php echo $result['product_id']; ?>">
							<img src="admin/uploads/<?php echo $result['image']; ?>" alt="<?php echo $result['product_name']; ?>" />
						</a>
						<h2><?php echo $result['product_name']; ?></h2>
						<p><?php echo $fm->textShorten($result['product_desc'], 30); ?></p>
						<p><span class="price"><?php echo number_format($result['price'], 0, ',', '.'); ?> VND</span></p>
						<form method="post">
							<input type="hidden" name="product_id" value="<?php echo $result['product_id']; ?>">
							<input type="number" name="quantity" value="1" min="1">
							<input type="submit" name="themgiohang" value="Thêm giỏ hàng" class="button" />
						</form>
						<div class="button"><span><a href="details.php?proid=<?php echo $result['product_id']; ?>" class="details">Chi tiết</a></span></div>
					</div>
			<?php
				}
			}
			?>
		</div>
	</div>
</div>

<?php include 'inc/footer.php'; ?>