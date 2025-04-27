<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php
include '../classes/Product.php';
include '../classes/Order.php';
include '../classes/Customer.php';
$pd = new Product();
$order = new Order();
$cs = new Customer();
?>
<div class="content">
  <div class="box">
    <h2>Dashboard</h2>
    <div class="dashboard-content">
      <div class="welcome-card">
        <h3>Chào mừng đến với Admin Panel</h3>
        <p>Quản lý cửa hàng điện thoại của bạn một cách hiệu quả</p>
      </div>
      <div class="stats-grid">
        <div class="stat-card">
          <h4>Tổng sản phẩm</h4>
          <p class="stat-number"><?php echo $pd->get_total_products(); ?></p>
        </div>
        <div class="stat-card">
          <h4>Đơn hàng chờ xử lý</h4>
          <p class="stat-number"><?php echo $order->get_pending_orders(); ?></p>
        </div>
        <div class="stat-card">
          <h4>Tổng doanh thu</h4>
          <p class="stat-number"><?php echo number_format($order->get_total_revenue(), 0, ',', '.') . ' VND'; ?></p>
        </div>
        <div class="stat-card">
          <h4>Tổng khách hàng</h4>
          <p class="stat-number"><?php echo $cs->get_total_customers(); ?></p>
        </div>
        <div class="stat-card">
          <h4>Khách hàng mới hôm nay</h4>
          <p class="stat-number"><?php echo $cs->get_new_customers(); ?></p>
        </div>
      </div>
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
              if ($revenue_by_day) {
                while ($row = $revenue_by_day->fetch_assoc()) {
              ?>
                  <tr>
                    <td><?php echo $row['day']; ?></td>
                    <td><?php echo number_format($row['total'], 0, ',', '.') . ' VND'; ?></td>
                  </tr>
              <?php
                }
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
              if ($revenue_by_month) {
                while ($row = $revenue_by_month->fetch_assoc()) {
              ?>
                  <tr>
                    <td><?php echo $row['month']; ?></td>
                    <td><?php echo number_format($row['total'], 0, ',', '.') . ' VND'; ?></td>
                  </tr>
              <?php
                }
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
              if ($revenue_by_year) {
                while ($row = $revenue_by_year->fetch_assoc()) {
              ?>
                  <tr>
                    <td><?php echo $row['year']; ?></td>
                    <td><?php echo number_format($row['total'], 0, ',', '.') . ' VND'; ?></td>
                  </tr>
              <?php
                }
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
  .dashboard-content {
    margin-top: 20px;
  }

  .welcome-card {
    background: #2c3e50;
    color: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    margin-bottom: 20px;
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }

  .stat-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  .stat-card h4 {
    color: #666;
    font-size: 16px;
    margin-bottom: 10px;
  }

  .stat-number {
    color: #2c3e50;
    font-size: 24px;
    font-weight: bold;
  }

  .revenue-stats {
    margin-top: 30px;
    margin-bottom: 30px;
  }

  .revenue-stats h3 {
    color: #4203a9;
    font-size: 20px;
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
  }

  .data-table th,
  .data-table td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: center;
  }

  .data-table th {
    background: #f8f9fa;
    color: #333;
  }
</style>
<?php include 'inc/footer.php'; ?>