<?php
include '../lib/session.php';
Session::checkSession();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị - Web Bán Hàng Online</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- jQuery và DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <header>
        <div class="container">
            <div class="header-left">
                <div class="img">
                    <img src="img/logo-admin.png" alt="Logo" class="logo">
                </div>
                <div class="brand-info">
                    <h1>Web Bán Hàng Online</h1>
                    <p>Chuyên bán thiết bị điện tử</p>
                </div>
            </div>
            <div class="header-right">
                <img src="img/img-profile.jpg" alt="Profile" class="profile-pic">
                <span>Xin chào, <?php echo Session::get('adminName'); ?></span>
                <a href="?action=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                <?php
                if (isset($_GET['action']) && $_GET['action'] == 'logout') {
                    Session::destroy();
                }
                ?>
            </div>
        </div>
    </header>
    <nav>
        <div class="container">
            <ul class="nav-list">
                <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="inbox.php"><i class="fas fa-inbox"></i> Inbox</a></li>
                <li><a href="changepassword.php"><i class="fas fa-key"></i> Đổi mật khẩu</a></li>
                <li><a href="../index.php" target="_blank"><i class="fas fa-globe"></i> Xem website</a></li>
            </ul>
        </div>
    </nav>
    <div class="main-container">