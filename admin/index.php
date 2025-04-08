<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<div class="grid_10">
  <div class="box round first grid">
    <h2> Dashbord</h2>
    <div class="dashboard-content">
      <!-- Welcome Section -->
      <div class="welcome-card">
        <h3>Welcome to Admin Panel</h3>
        <p>Manage your phone store efficiently</p>
      </div>

      <!-- Quick Stats Cards -->
      <div class="stats-grid">
        <div class="stat-card">
          <h4>Total Products</h4>
          <p class="stat-number">150</p>
        </div>
        <div class="stat-card">
          <h4>Pending Orders</h4>
          <p class="stat-number">23</p>
        </div>
        <div class="stat-card">
          <h4>Total Revenue</h4>
          <p class="stat-number">$12,450</p>
        </div>
        <div class="stat-card">
          <h4>New Users</h4>
          <p class="stat-number">45</p>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'inc/footer.php'; ?>
<style>
  /* Dashboard Container */
  .dashboard-container {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
  }

  .dashboard-container h2 {
    color: #333;
    font-size: 24px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #4203a9;
  }

  .dashboard-content {
    padding: 20px;
  }

  /* Welcome Card */
  .welcome-card {
    background: linear-gradient(135deg, #4203a9, #90bafc);
    color: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    text-align: center;
  }

  .welcome-card h3 {
    font-size: 20px;
    margin-bottom: 10px;
  }

  .welcome-card p {
    font-size: 14px;
    opacity: 0.9;
  }

  /* Stats Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
  }

  .stat-card {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease;
  }

  .stat-card:hover {
    transform: translateY(-5px);
  }

  .stat-card h4 {
    color: #666;
    font-size: 16px;
    margin-bottom: 10px;
  }

  .stat-card .stat-number {
    color: #4203a9;
    font-size: 24px;
    font-weight: bold;
  }
</style>