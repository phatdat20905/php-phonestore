<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Rating.php'; ?>
<?php include '../classes/Product.php'; ?>
<?php include '../classes/Customer.php'; ?>
<?php
$rating = new Rating();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $product_id = $_POST['product_id'];
    $customer_id = $_POST['customer_id'];
    $rating_value = $_POST['rating'];
    $comment = $_POST['comment'];
    $insertRating = $rating->insert_rating($product_id, $customer_id, $rating_value, $comment);
    $message = $insertRating['message'];
}
?>
<div class="content">
    <div class="box">
        <h2>Thêm đánh giá</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="ratingadd.php" method="post">
            <div class="form-group">
                <label for="product_id">Sản phẩm:</label>
                <select id="product_id" name="product_id" required>
                    <option value="">Chọn sản phẩm</option>
                    <?php
                    $product = new Product();
                    $productlist = $product->show_product();
                    if ($productlist) {
                        while ($result = $productlist->fetch_assoc()) {
                    ?>
                            <option value="<?php echo $result['product_id']; ?>"><?php echo $result['product_name']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="customer_id">Khách hàng:</label>
                <select id="customer_id" name="customer_id" required>
                    <option value="">Chọn khách hàng</option>
                    <?php
                    $customer = new Customer();
                    $customerlist = $customer->show_customer();
                    if ($customerlist) {
                        while ($result = $customerlist->fetch_assoc()) {
                    ?>
                            <option value="<?php echo $result['customer_id']; ?>"><?php echo $result['name']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="rating">Số sao:</label>
                <select id="rating" name="rating" required>
                    <option value="">Chọn số sao</option>
                    <option value="1">1 sao</option>
                    <option value="2">2 sao</option>
                    <option value="3">3 sao</option>
                    <option value="4">4 sao</option>
                    <option value="5">5 sao</option>
                </select>
            </div>
            <div class="form-group">
                <label for="comment">Bình luận:</label>
                <textarea id="comment" name="comment" placeholder="Nhập bình luận..." rows="4"></textarea>
            </div>
            <button type="submit" name="submit" class="btn"><i class="fas fa-save"></i> Lưu</button>
        </form>
    </div>
</div>
<?php include 'inc/footer.php'; ?>