<?php
include 'inc/header.php';
// include 'inc/slider.php';
?>

<?php
if (!isset($_GET['proid']) || $_GET['proid'] == NULL) {
	echo "<script>window.location='404.php';</script>";
} else {
	$id = $_GET['proid'];
}
$customer_id = Session::get('customer_id');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['compare'])) {
	$productid = $_POST['productid'];
	$insertCompare = $compare->add_to_compare($customer_id, $productid);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wishlist'])) {
	$productid = $_POST['productid'];
	$insertWishlist = $wl->add_to_wishlist($customer_id, $productid);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
	$quantity = $_POST['quantity'];
	$AddtoCart = $ct->add_to_cart($quantity, $id);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitComment'])) {
	$data = [
		'product_id' => $_POST['productId'],
		'customer_id' => $customer_id,
		'content' => $_POST['comment']
	];
	$insertComment = $comment->insert_comment($data);
}
?>

<div class="main">
	<div class="content">
		<div class="product-details">
			<?php
			$get_product_details = $product->get_product_details($id);
			if ($get_product_details) {
				while ($result_details = $get_product_details->fetch_assoc()) {
			?>
					<div class="product-info">
						<div class="product-image">
							<img src="admin/uploads/<?php echo $result_details['image'] ?>" alt="<?php echo $result_details['product_name'] ?>" />
						</div>
						<div class="product-content">
							<h1><?php echo $result_details['product_name'] ?></h1>
							<div class="price-info">
								<p>Giá: <span><?php echo number_format($result_details['price'], 0, ',', '.'); ?> VND</span></p>
								<p>Danh mục: <span><?php echo $result_details['cat_name'] ?></span></p>
								<p>Thương hiệu: <span><?php echo $result_details['brand_name'] ?></span></p>
								<p>Tồn kho: <span class="inventory-quantity"><?php echo $result_details['quantity']; ?></span></p>
							</div>
							<div class="actions">
								<form action="" method="post" class="cart-form">
									<input type="number" class="quantity-input" name="quantity" value="1" min="1" />
									<?php
									if ($result_details['quantity'] > 0) {
										echo '<input type="submit" class="btn-add-cart" name="submit" value="Thêm vào giỏ" />';
									} else {
										echo '<p class="out-of-stock">Hết hàng</p>';
									}
									?>
								</form>
								<?php
								if (isset($AddtoCart)) {
									if ($AddtoCart === true) {
										echo '<span class="message-success">Thêm vào giỏ hàng thành công!</span>';
										$updated_product = $product->get_product_details($id)->fetch_assoc();
										echo "<script>document.querySelector('.inventory-quantity').textContent = '{$updated_product['quantity']}';</script>";
									} else {
										echo '<span class="message">' . $AddtoCart . '</span>';
									}
								}
								?>
								<?php if (Session::get('customer_login')) { ?>
									<div class="extra-actions">
										<form action="" method="post">
											<input type="hidden" name="productid" value="<?php echo $result_details['product_id'] ?>">
											<button type="submit" name="compare" class="btn-compare">So sánh sản phẩm</button>
										</form>
										<form action="" method="post">
											<input type="hidden" name="productid" value="<?php echo $result_details['product_id'] ?>">
											<button type="submit" name="wishlist" class="btn-wishlist">Thêm vào yêu thích</button>
										</form>
									</div>
									<?php
									if (isset($insertCompare)) {
										echo '<span class="message">' . $insertCompare . '</span>';
									}
									if (isset($insertWishlist)) {
										echo '<span class="message">' . $insertWishlist . '</span>';
									}
									?>
								<?php } ?>
							</div>
						</div>
					</div>
			<?php
				}
			}
			?>
			<div class="sidebar">
				<h2>Danh mục</h2>
				<ul>
					<?php
					$getall_category = $cat->show_category_frontend();
					if ($getall_category) {
						while ($result_allcat = $getall_category->fetch_assoc()) {
					?>
							<li><a href="productbycat.php?catId=<?php echo $result_allcat['cat_id'] ?>"><?php echo $result_allcat['cat_name'] ?></a></li>
					<?php
						}
					}
					?>
				</ul>
			</div>
		</div>
		<?php
		$get_product_details = $product->get_product_details($id);
		if ($get_product_details) {
			while ($result_details = $get_product_details->fetch_assoc()) {
		?>
				<div class="product-description">
					<h2>Chi tiết sản phẩm</h2>
					<p><?php echo $result_details['product_desc'] ?></p>
				</div>
		<?php
			}
		}
		?>
		<div class="reviews">
			<h2>Đánh giá & Bình luận</h2>
			<div class="rating-section">
				<?php
				$get_ratings = $rating->show_ratings();
				$avg_rating = 0;
				$count = 0;
				$ratings = [];
				if ($get_ratings) {
					while ($result_rating = $get_ratings->fetch_assoc()) {
						if ($result_rating['product_id'] == $id) {
							$ratings[] = $result_rating;
							$avg_rating += $result_rating['rating'];
							$count++;
						}
					}
					$avg_rating = $count ? $avg_rating / $count : 0;
				}
				?>
				<div class="rating-average">
					<p>Đánh giá trung bình: <span><?php echo round($avg_rating, 1); ?>/5 (<?php echo $count; ?> lượt)</span></p>
					<?php
					for ($i = 1; $i <= 5; $i++) {
						$color = $i <= round($avg_rating) ? 'color:#ffcc00;' : 'color:#ccc;';
						echo '<span class="star" style="' . $color . ' font-size:24px;">★</span>';
					}
					?>
				</div>
				<?php if (Session::get('customer_login')) { ?>
					<div class="rating-input">
						<p>Đánh giá của bạn:</p>
						<?php
						for ($i = 1; $i <= 5; $i++) {
							echo '<span class="star-input" data-rating="' . $i . '" data-product_id="' . $id . '" style="font-size:24px; cursor:pointer; color:#ccc;">★</span>';
						}
						?>
						<span class="rating-message"></span>
					</div>
				<?php } ?>
			</div>
			<div class="comment-section">
				<h3>Bình luận</h3>
				<?php
				$comments = $comment->show_approved_comments();
				if ($comments) {
					while ($result_comment = $comments->fetch_assoc()) {
						if ($result_comment['product_id'] == $id) {
				?>
							<div class="comment-item">
								<p><strong><?php echo $result_comment['customer_name']; ?></strong> - <?php echo date('d/m/Y H:i', strtotime($result_comment['created_at'])); ?></p>
								<p><?php echo $result_comment['content']; ?></p>
							</div>
				<?php
						}
					}
				} else {
					echo '<p>Chưa có bình luận nào.</p>';
				}
				?>
				<?php if (Session::get('customer_login')) { ?>
					<?php if (isset($insertComment)) { ?>
						<span class="message"><?php echo $insertComment['message']; ?></span>
					<?php } ?>
					<form action="" method="post" class="comment-form">
						<input type="hidden" name="productId" value="<?php echo $id ?>">
						<div class="form-group">
							<textarea rows="5" name="comment" placeholder="Bình luận của bạn" required></textarea>
						</div>
						<button type="submit" name="submitComment" class="btn-submit-comment">Gửi bình luận</button>
					</form>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php
include 'inc/footer.php';
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.star-input').on('click', function() {
			var rating = $(this).data('rating');
			var product_id = $(this).data('product_id');
			var customer_id = '<?php echo $customer_id; ?>';

			$.ajax({
				url: 'ajax/rating.php',
				type: 'POST',
				data: {
					index: rating,
					product_id: product_id,
					customer_id: customer_id
				},
				success: function(response) {
					if (response === 'successfully') {
						$('.rating-message').text('Cảm ơn bạn đã đánh giá!').css('color', '#28a745');
						setTimeout(function() {
							location.reload();
						}, 1000);
					} else {
						$('.rating-message').text('Đánh giá thất bại!').css('color', '#ff3333');
					}
				}
			});
		});

		$('.star-input').hover(
			function() {
				var rating = $(this).data('rating');
				$('.star-input').each(function() {
					if ($(this).data('rating') <= rating) {
						$(this).css('color', '#ffcc00');
					} else {
						$(this).css('color', '#ccc');
					}
				});
			},
			function() {
				$('.star-input').css('color', '#ccc');
			}
		);
	});
</script>

<style type="text/css">
	.main {
		max-width: 1200px;
		margin: 0 auto;
		padding: 40px 20px;
		background: #f9f9f9;
		min-height: calc(100vh - 200px);
	}

	.content {
		background: #fff;
		padding: 30px;
		border-radius: 10px;
		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
	}

	.product-details {
		display: flex;
		gap: 30px;
		flex-wrap: wrap;
	}

	.product-info {
		display: flex;
		flex: 2;
		gap: 20px;
		flex-wrap: wrap;
	}

	.product-image {
		flex: 1;
		min-width: 300px;
	}

	.product-image img {
		max-width: 100%;
		border-radius: 10px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		transition: transform 0.3s ease;
	}

	.product-image img:hover {
		transform: scale(1.05);
	}

	.product-content {
		flex: 1;
		min-width: 300px;
	}

	.product-content h1 {
		font-size: 28px;
		color: #333;
		margin-bottom: 20px;
		font-family: 'Monda', sans-serif;
	}

	.price-info p {
		font-size: 16px;
		color: #666;
		margin: 10px 0;
	}

	.price-info span {
		color: #ff6600;
		font-weight: bold;
	}

	.actions {
		margin-top: 20px;
	}

	.cart-form {
		display: flex;
		gap: 10px;
		align-items: center;
	}

	.quantity-input {
		width: 70px;
		padding: 10px;
		border: 1px solid #ddd;
		border-radius: 5px;
		font-size: 16px;
	}

	.btn-add-cart {
		padding: 12px 20px;
		background: #ff6600;
		border: none;
		border-radius: 5px;
		color: #fff;
		font-size: 16px;
		cursor: pointer;
		transition: background 0.3s ease;
	}

	.btn-add-cart:hover {
		background: #e65c00;
	}

	.out-of-stock {
		color: #ff3333;
		font-size: 14px;
		margin: 10px 0;
	}

	.extra-actions {
		display: flex;
		gap: 10px;
		margin-top: 15px;
	}

	.btn-compare,
	.btn-wishlist {
		padding: 10px 15px;
		background: #333;
		border: none;
		border-radius: 5px;
		color: #fff;
		font-size: 14px;
		cursor: pointer;
		transition: background 0.3s ease;
	}

	.btn-compare:hover,
	.btn-wishlist:hover {
		background: #ff6600;
	}

	.message {
		display: block;
		color: #ff3333;
		font-size: 14px;
		margin-top: 10px;
	}

	.message-success {
		display: block;
		color: #28a745;
		/* Màu xanh thành công */
		font-size: 14px;
		margin-top: 10px;
	}

	.product-description {
		margin-top: 30px;
		width: 100%;
	}

	.product-description h2 {
		font-size: 22px;
		color: #fff;
		margin-bottom: 15px;
		font-family: 'Monda', sans-serif;
		background-color: #ff6600;
	}

	.product-description p {
		color: #666;
		font-size: 14px;
		line-height: 1.6;
	}

	.sidebar {
		flex: 1;
		min-width: 250px;
	}

	.sidebar h2 {
		font-size: 22px;
		color: #333;
		margin-bottom: 15px;
		font-family: 'Monda', sans-serif;
	}

	.sidebar ul {
		list-style: none;
		padding: 0;
	}

	.sidebar ul li a {
		display: block;
		padding: 12px 15px;
		color: #666;
		text-decoration: none;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	.sidebar ul li a:hover {
		color: #ff6600;
		background: #f5f5f5;
		border-radius: 5px;
	}

	.reviews {
		margin-top: 40px;
		padding-top: 30px;
		border-top: 1px solid #eee;
	}

	.reviews h2 {
		font-size: 22px;
		color: #333;
		margin-bottom: 20px;
		font-family: 'Monda', sans-serif;
	}

	.rating-section {
		margin-bottom: 20px;
	}

	.rating-average p {
		font-size: 16px;
		color: #666;
		margin-bottom: 10px;
	}

	.rating-average span {
		color: #ff6600;
		font-weight: bold;
	}

	.rating-input {
		margin-top: 15px;
	}

	.rating-input p {
		font-size: 14px;
		color: #333;
		margin-bottom: 5px;
	}

	.rating-message {
		display: inline-block;
		margin-left: 10px;
		font-size: 14px;
	}

	.comment-section {
		margin-top: 20px;
	}

	.comment-section h3 {
		font-size: 18px;
		color: #333;
		margin-bottom: 15px;
	}

	.comment-item {
		border-bottom: 1px solid #eee;
		padding: 15px 0;
	}

	.comment-item p {
		margin: 5px 0;
		color: #666;
		font-size: 14px;
	}

	.comment-item p strong {
		color: #333;
	}

	.comment-form {
		margin-top: 20px;
	}

	.form-group {
		margin-bottom: 15px;
	}

	.comment-form textarea {
		width: 100%;
		padding: 12px;
		border: 1px solid #ddd;
		border-radius: 5px;
		font-size: 16px;
		transition: border-color 0.3s ease;
	}

	.comment-form textarea:focus {
		border-color: #ff6600;
		outline: none;
	}

	.comment-form textarea {
		resize: vertical;
	}

	.btn-submit-comment {
		padding: 12px 20px;
		background: #ff6600;
		border: none;
		border-radius: 5px;
		color: #fff;
		font-size: 16px;
		cursor: pointer;
		transition: background 0.3s ease;
	}

	.btn-submit-comment:hover {
		background: #e65c00;
	}

	@media (max-width: 768px) {
		.product-details {
			flex-direction: column;
		}

		.product-info {
			flex-direction: column;
		}

		.extra-actions {
			flex-direction: column;
			gap: 10px;
		}

		.btn-compare,
		.btn-wishlist {
			width: 100%;
		}
	}
</style>