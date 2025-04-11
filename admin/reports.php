<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php
include '../classes/Product.php';
include '../classes/Order.php';
$pd = new Product();
$order = new Order();
?>

<div class="content">
    <div class="box">
        <h2>Báo cáo thống kê</h2>
        <div class="report-content">
            <!-- Thống kê doanh thu -->
            <div class="revenue-stats">
                <h3>Thống kê doanh thu</h3>
                <div class="tabs">
                    <button class="tab-button active" data-tab="day">Theo ngày</button>
                    <button class="tab-button" data-tab="month">Theo tháng</button>
                    <button class="tab-button" data-tab="year">Theo năm</button>
                </div>
                <div class="tab-content" id="day">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $revenue_by_day = $order->get_revenue_by_day();
                            if ($revenue_by_day && $revenue_by_day->num_rows > 0) {
                                while ($row = $revenue_by_day->fetch_assoc()) {
                            ?>
                                    <tr>
                                        <td><?php echo $row['day']; ?></td>
                                        <td><?php echo number_format($row['total'], 0, ',', '.') . ' VNĐ'; ?></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='2'>Không có dữ liệu doanh thu theo ngày</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-content" id="month" style="display: none;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tháng</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $revenue_by_month = $order->get_revenue_by_month();
                            if ($revenue_by_month && $revenue_by_month->num_rows > 0) {
                                while ($row = $revenue_by_month->fetch_assoc()) {
                            ?>
                                    <tr>
                                        <td><?php echo $row['month']; ?></td>
                                        <td><?php echo number_format($row['total'], 0, ',', '.') . ' VNĐ'; ?></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='2'>Không có dữ liệu doanh thu theo tháng</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-content" id="year" style="display: none;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Năm</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $revenue_by_year = $order->get_revenue_by_year();
                            if ($revenue_by_year && $revenue_by_year->num_rows > 0) {
                                while ($row = $revenue_by_year->fetch_assoc()) {
                            ?>
                                    <tr>
                                        <td><?php echo $row['year']; ?></td>
                                        <td><?php echo number_format($row['total'], 0, ',', '.') . ' VNĐ'; ?></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='2'>Không có dữ liệu doanh thu theo năm</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sản phẩm bán chạy -->
            <div class="report-section">
                <h3>Sản phẩm bán chạy nhất</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng bán</th>
                            <th>Tổng doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $top_products = $pd->get_top_selling_products();
                        if ($top_products && $top_products->num_rows > 0) {
                            $i = 0;
                            while ($result = $top_products->fetch_assoc()) {
                                $i++;
                        ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $result['product_name']; ?></td>
                                    <td><?php echo $result['total_quantity']; ?></td>
                                    <td><?php echo number_format($result['total_revenue'], 0, ',', '.') . ' VNĐ'; ?></td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='4'>Không có dữ liệu sản phẩm bán chạy</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');
            document.getElementById(button.dataset.tab).style.display = 'block';
        });
    });
</script>

<style>
    .content {
        padding: 20px;
    }

    .box {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .box h2 {
        font-size: 24px;
        color: #2c3e50;
        margin-bottom: 20px;
        text-align: center;
    }

    .report-content {
        margin-top: 20px;
    }

    .revenue-stats {
        margin-bottom: 30px;
    }

    .revenue-stats h3,
    .report-section h3 {
        font-size: 20px;
        color: #4203a9;
        margin-bottom: 15px;
    }

    .tabs {
        margin-bottom: 20px;
    }

    .tab-button {
        padding: 10px 20px;
        background: #ddd;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 10px;
    }

    .tab-button.active {
        background: #4203a9;
        color: white;
    }

    .tab-button:hover {
        background: #bbb;
    }

    .tab-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .data-table th,
    .data-table td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    .data-table th {
        background: #f5f5f5;
        color: #333;
        font-weight: bold;
    }

    .data-table tr:hover {
        background: #f9f9f9;
    }

    .report-section {
        margin-top: 30px;
    }
</style>

<?php include 'inc/footer.php'; ?>