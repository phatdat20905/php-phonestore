<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Order.php'; ?>
<?php include '../classes/Product.php'; ?>
<?php
$order = new Order();
$pd = new Product();
?>
<div class="content">
    <div class="box">
        <h2>Báo cáo</h2>
        <div class="report-section">
            <h3>Doanh thu theo tháng</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tháng</th>
                        <th>Tổng doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $revenue = $order->get_revenue_by_month();
                    if ($revenue) {
                        while ($result = $revenue->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo $result['month']; ?></td>
                                <td><?php echo number_format($result['total'], 0, ',', '.') . ' VND'; ?></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='2'>Không có dữ liệu</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="report-section" style="margin-top: 20px;">
            <h3>Sản phẩm bán chạy</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng bán</th>
                        <th>Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $top_products = $pd->get_top_selling_products();
                    if ($top_products) {
                        $i = 0;
                        while ($result = $top_products->fetch_assoc()) {
                            $i++;
                    ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $result['product_name']; ?></td>
                                <td><?php echo $result['total_quantity']; ?></td>
                                <td><?php echo number_format($result['total_revenue'], 0, ',', '.') . ' VND'; ?></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='4'>Không có dữ liệu</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    .report-section {
        margin-bottom: 30px;
    }

    .report-section h3 {
        margin-bottom: 15px;
        color: #2c3e50;
    }
</style>
<?php include 'inc/footer.php'; ?>