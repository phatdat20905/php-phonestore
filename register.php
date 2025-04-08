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
    $insertCustomer = $cs->insert_customer($_POST);
}
?>

<div class="main">
    <div class="content">
        <div class="register_account">
            <h3>Đăng ký tài khoản mới</h3>
            <?php
            if (isset($insertCustomer)) {
                echo '<span class="error-message">' . $insertCustomer . '</span>';
            }
            ?>
            <form action="" method="POST">
                <div class="form-grid">
                    <div class="form-column">
                        <input type="text" name="name" placeholder="Nhập Họ tên...">
                        <input type="text" name="city" placeholder="Nhập Thành phố...">
                        <input type="text" name="zipcode" placeholder="Nhập Mã bưu điện...">
                        <input type="text" name="email" placeholder="Nhập Email...">
                    </div>
                    <div class="form-column">
                        <input type="text" name="address" placeholder="Nhập Địa chỉ...">
                        <select id="country" name="country">
                            <option value="null">Chọn Quốc gia</option>
                            <option value="VN">Việt Nam</option>
                            <!-- Thêm các quốc gia khác nếu cần -->
                        </select>
                        <input type="text" name="phone" placeholder="Nhập Số điện thoại...">
                        <input type="text" name="password" placeholder="Nhập Mật khẩu...">
                    </div>
                </div>
                <div class="search">
                    <input type="submit" class="submit-btn" name="submit" value="Tạo tài khoản">
                </div>
                <p class="login-link">Đã có tài khoản? <a href="login.php">Đăng nhập tại đây</a></p>
            </form>
        </div>
    </div>
</div>

<?php
include 'inc/footer.php';
?>

<style>
    .main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .content {
        display: flex;
        justify-content: center;
    }

    .register_account {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 750px;
    }

    h3 {
        color: #4203a9;
        font-size: 24px;
        margin-bottom: 15px;
        text-align: center;
    }

    .error-message {
        display: block;
        color: #ff3333;
        font-size: 14px;
        margin-bottom: 15px;
        text-align: center;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    input:focus {
        outline: none;
        border-color: #4203a9;
    }

    select {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        background: #fff;
    }

    .submit-btn {
        width: 100%;
        padding: 12px;
        background: #4203a9;
        border: none;
        border-radius: 5px;
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .submit-btn:hover {
        background: #35028c;
    }

    .search {
        margin-top: 20px;
        text-align: center;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-column {
        display: flex;
        flex-direction: column;
    }

    .login-link {
        margin-top: 20px;
        text-align: center;
        color: #666;
        font-size: 14px;
    }

    .login-link a {
        color: #4203a9;
        text-decoration: none;
    }

    .login-link a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>