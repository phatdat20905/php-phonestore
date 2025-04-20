<?php include 'inc/header.php'; ?>
<?php
$login_check = Session::get('customer_login');
if ($login_check == false) {
    header('Location: login.php');
    exit;
}

$customer_id = Session::get('customer_id');
$cs = new Customer();

// Xử lý đổi mật khẩu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra mật khẩu
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $password_message = "Vui lòng điền đầy đủ các trường!";
    } elseif ($new_password !== $confirm_password) {
        $password_message = "Mật khẩu mới và xác nhận mật khẩu không khớp!";
    } elseif (strlen($new_password) < 6) {
        $password_message = "Mật khẩu mới phải có ít nhất 6 ký tự!";
    } else {
        // Kiểm tra mật khẩu cũ
        $check_old_password = $cs->check_old_password($customer_id, $old_password);
        if ($check_old_password) {
            // Cập nhật mật khẩu mới
            $update_password = $cs->update_password($customer_id, $new_password);
            if ($update_password) {
                $password_message = "Đổi mật khẩu thành công!";
            } else {
                $password_message = "Lỗi khi cập nhật mật khẩu. Vui lòng thử lại!";
            }
        } else {
            $password_message = "Mật khẩu cũ không đúng!";
        }
    }
}
?>

<div class="main">
    <div class="content">
        <div class="change-password-container">
            <h2>Đổi mật khẩu</h2>
            <?php
            if (isset($password_message)) {
                echo '<p class="message" style="color: ' . (strpos($password_message, 'thành công') !== false ? '#28a745' : '#ff3333') . ';">' . $password_message . '</p>';
            }
            ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="old_password">Mật khẩu cũ:</label>
                    <input type="password" id="old_password" name="old_password" required />
                </div>
                <div class="form-group">
                    <label for="new_password">Mật khẩu mới:</label>
                    <input type="password" id="new_password" name="new_password" required />
                </div>
                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required />
                </div>
                <button type="submit" name="change_password" class="btn">Đổi mật khẩu</button>
                <a href="profile.php" class="btn back-btn">Quay lại hồ sơ</a>
            </form>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>

<style>
    .main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .change-password-container {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: 0 auto;
    }

    h2 {
        color: #4203a9;
        font-size: 28px;
        text-align: center;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .message {
        text-align: center;
        font-size: 16px;
        margin-bottom: 20px;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        background: #4203a9;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin: 10px 5px;
        text-align: center;
        border: none;
        cursor: pointer;
    }

    .btn:hover {
        background: #35028c;
    }

    .back-btn {
        background: #666;
    }

    .back-btn:hover {
        background: #555;
    }
</style>