<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Cart.php'; ?>
<?php
$cart = new Cart();
$message = '';
if (isset($_GET['delId'])) {
    $id = $_GET['delId'];
    $delCart = $cart->del_cart($id);
    $message = $delCart ? 'Xóa giỏ hàng thành công!' : 'Xóa giỏ hàng thất bại!';
}
?>
<div class="content">
    <div class="box">
        <h2>Danh sách giỏ hàng</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Session ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Ảnh</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cartlist = $cart->get_all_cart();
                if ($cartlist) {
                    $i = 0;
                    while ($result = $cartlist->fetch_assoc()) {
                        $i++;
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $result['sId']; ?></td>
                            <td><?php echo $result['product_name']; ?></td>
                            <td><?php echo number_format($result['price'], 0, ',', '.') . ' VND'; ?></td>
                            <td><?php echo $result['quantity']; ?></td>
                            <td><img src="uploads/<?php echo $result['image']; ?>" alt="Product Image" width="80"></td>
                            <td><?php echo $result['created_at']; ?></td>
                            <td>
                                <a href="?delId=<?php echo $result['cart_id']; ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?');"><i class="fas fa-trash"></i> Xóa</a>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'inc/footer.php'; ?>