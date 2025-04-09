<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Inventory.php'; ?>
<?php include '../classes/Product.php'; ?>
<?php
$inventory = new Inventory();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity_change = $_POST['quantity_change'];
    $note = $_POST['note'];
    $updateInventory = $inventory->update_inventory($product_id, $quantity_change, $note);
    $message = $updateInventory['message'];
}
?>
<div class="content">
    <div class="box">
        <h2>Quản lý kho</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="inventory.php" method="post">
            <div class="form-group">
                <label for="product_id">Sản phẩm:</label>
                <select id="product_id" name="product_id" required>
                    <option value="">Chọn sản phẩm</option>
                    <?php
                    $pd = new Product();
                    $pdlist = $pd->show_product();
                    if ($pdlist) {
                        while ($result = $pdlist->fetch_assoc()) {
                    ?>
                            <option value="<?php echo $result['product_id']; ?>"><?php echo $result['product_name']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity_change">Số lượng thay đổi (+ nhập, - xuất):</label>
                <input type="number" id="quantity_change" name="quantity_change" placeholder="Nhập số lượng..." required>
            </div>
            <div class="form-group">
                <label for="note">Ghi chú:</label>
                <input type="text" id="note" name="note" placeholder="Nhập ghi chú...">
            </div>
            <button type="submit" class="btn"><i class="fas fa-save"></i> Cập nhật</button>
        </form>
        <h3 style="margin-top: 20px;">Tình trạng kho</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng hiện tại</th>
                    <th>Cập nhật cuối</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $invlist = $inventory->show_inventory();
                if ($invlist) {
                    $i = 0;
                    while ($result = $invlist->fetch_assoc()) {
                        $i++;
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $result['product_name']; ?></td>
                            <td><?php echo $result['quantity']; ?></td>
                            <td><?php echo $result['last_updated']; ?></td>
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