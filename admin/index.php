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
          <h4>Khách hàng mới</h4>
          <p class="stat-number"><?php echo $cs->get_new_customers(); ?></p>
        </div>
      </div>
    </div>
  </div>
</div>
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
</style>
<?php include 'inc/footer.php'; ?>