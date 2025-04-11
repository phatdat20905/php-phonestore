<?php
include 'inc/header.php';
include_once 'classes/Product.php';
include_once 'classes/Cart.php';

$product = new Product();
$cart = new Cart();

// Xử lý thêm giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['themgiohang'])) {
	$product_id = $_POST['product_id'];
	$quantity = (int)$_POST['quantity'];
	$add_to_cart = $cart->add_to_cart($product_id, $quantity);
	if ($add_to_cart['success']) {
		echo "<script>alert('{$add_to_cart['message']}');</script>";
	} else {
		echo "<script>alert('{$add_to_cart['message']}');</script>";
	}
}

// Lấy từ khóa tìm kiếm từ form
$search_keyword = isset($_POST['tukhoa']) ? $_POST['tukhoa'] : '';
$search_keyword = trim($search_keyword); // Xóa khoảng trắng thừa

// Phân trang
$limit = 8; // Số sản phẩm mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
?>

<div class="main">
	<div class="content">
		<h1>Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($search_keyword); ?>"</h1>
		<?php if (empty($search_keyword)): ?>
			<p>Vui lòng nhập từ khóa để tìm kiếm sản phẩm.</p>
		<?php else: ?>
			<?php
			// Gọi hàm tìm kiếm sản phẩm trong class Product
			$search_result = $product->search_product($search_keyword, $limit, $offset);
			$total_products = $product->count_search_products($search_keyword); // Tổng số sản phẩm tìm được
			$total_pages = ceil($total_products / $limit);
			?>

			<?php if ($search_result && $search_result->num_rows > 0): ?>
				<div class="section group">
					<?php while ($result = $search_result->fetch_assoc()): ?>
						<div class="grid_1_of_4 images_1_of_4">
							<a href="details.php?proid=<?php echo $result['product_id']; ?>">
								<img src="admin/uploads/<?php echo $result['image']; ?>" alt="<?php echo $result['product_name']; ?>" />
							</a>
							<h2><?php echo $result['product_name']; ?></h2>
							<p><?php echo $fm->textShorten($result['product_desc'], 30); ?></p>
							<p><span class="price"><?php echo number_format($result['price'], 0, ',', '.') . " VND"; ?></span></p>
							<form method="post">
								<input type="hidden" name="product_id" value="<?php echo $result['product_id']; ?>">
								<input type="number" name="quantity" value="1" min="1">
								<input type="submit" name="themgiohang" value="Thêm giỏ hàng" class="button" />
							</form>
							<div class="button">
								<span><a href="details.php?proid=<?php echo $result['product_id']; ?>" class="details">Chi tiết</a></span>
							</div>
						</div>
					<?php endwhile; ?>
				</div>

				<!-- Phân trang -->
				<?php if ($total_pages > 1): ?>
					<div class="pagination">
						<?php if ($page > 1): ?>
							<a href="search.php?page=<?php echo $page - 1; ?>" class="page_link">Trang trước</a>
						<?php endif; ?>
						<?php for ($i = 1; $i <= $total_pages; $i++): ?>
							<a href="search.php?page=<?php echo $i; ?>" class="page_link <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
						<?php endfor; ?>
						<?php if ($page < $total_pages): ?>
							<a href="search.php?page=<?php echo $page + 1; ?>" class="page_link">Trang sau</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			<?php else: ?>
				<p>Không tìm thấy sản phẩm nào phù hợp với từ khóa "<?php echo htmlspecialchars($search_keyword); ?>".</p>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>

<?php include 'inc/footer.php'; ?>