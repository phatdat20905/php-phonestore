<?php
include 'inc/header.php';
// include 'inc/slider.php';
?>

<?php
$login_check = Session::get('customer_login');
if ($login_check == true) {
    header('Location:index.php');
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $insertCustomer = $cs->insert_customer($_POST, $_FILES);
}
?>

<div class="main">
    <div class="content">
        <div class="register-container">
            <h2>Đăng ký tài khoản mới</h2>
            <?php
            if (isset($insertCustomer)) {
                $class = $insertCustomer['success'] ? 'success' : '';
                echo '<span class="message ' . $class . '">' . $insertCustomer['message'] . '</span>';
                if ($insertCustomer['success']) {
                    echo "<script>window.location='login.php';</script>";
                }
            }
            ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Họ tên <span class="required">*</span></label>
                        <input type="text" id="name" name="name" placeholder="Nhập họ tên..." required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="Nhập email..." required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Số điện thoại <span class="required">*</span></label>
                        <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại..." required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu <span class="required">*</span></label>
                        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu..." required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="address">Địa chỉ <span class="required">*</span></label>
                        <input type="text" id="address" name="address" placeholder="Nhập địa chỉ..." required>
                    </div>
                    <div class="form-group">
                        <label for="city">Thành phố <span class="required">*</span></label>
                        <input type="text" id="city" name="city" placeholder="Nhập thành phố..." required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="zipcode">Mã bưu điện</label>
                        <input type="text" id="zipcode" name="zipcode" placeholder="Nhập mã bưu điện...">
                    </div>
                    <div class="form-group">
                        <label for="country">Quốc gia <span class="required">*</span></label>
                        <select id="country" name="country" required>
                            <option value="">Chọn quốc gia</option>
                            <option value="VN">Việt Nam</option>
                            <!-- Thêm các quốc gia khác nếu cần -->
                        </select>
                    </div>
                </div>
                <div class="form-group full-width">
                    <label for="image">Ảnh đại diện</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
                <button type="submit" name="submit" class="submit-btn">Tạo tài khoản</button>
                <p class="login-link">Đã có tài khoản? <a href="login.php">Đăng nhập tại đây</a></p>
            </form>
        </div>
    </div>
</div>

<?php
include 'inc/footer.php';
?>

<style type="text/css">
    .main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
        background: #f9f9f9;
        min-height: calc(100vh - 200px);
        /* Đảm bảo chiều cao tối thiểu */
    }

    .content {
        display: flex;
        justify-content: center;
    }

    .register-container {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 700px;
    }

    h2 {
        font-size: 28px;
        color: #333;
        text-align: center;
        margin-bottom: 20px;
        font-family: 'Monda', sans-serif;
    }

    .message {
        display: block;
        font-size: 14px;
        text-align: center;
        margin-bottom: 20px;
        color: #ff3333;
    }

    .message.success {
        color: #28a745;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 1;
    }

    .form-group.full-width {
        flex: none;
        width: 100%;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        color: #333;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .form-group .required {
        color: #ff3333;
    }

    .form-group input,
    .form-group select {
        width: 90%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #ff6600;
        box-shadow: 0 0 5px rgba(255, 102, 0, 0.3);
        outline: none;
    }

    .form-group input[type="file"] {
        padding: 8px;
        border: 1px dashed #ddd;
        background: #fafafa;
    }

    .submit-btn {
        width: 100%;
        padding: 12px;
        background: #ff6600;
        border: none;
        border-radius: 5px;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s ease;
        margin-top: 20px;
    }

    .submit-btn:hover {
        background: #e65c00;
    }

    .login-link {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        color: #666;
    }

    .login-link a {
        color: #ff6600;
        text-decoration: none;
        font-weight: bold;
    }

    .login-link a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 15px;
        }

        .register-container {
            padding: 20px;
        }
    }
</style>