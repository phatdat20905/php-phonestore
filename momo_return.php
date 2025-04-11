<?php
include 'inc/header.php';
include 'lib/config_momo.php';

$login_check = Session::get('customer_login');
if ($login_check == false) {
    header('Location: login.php');
    exit;
}

$order = new Order();
$ct = new Cart();

$orderId = isset($_GET['orderId']) ? $_GET['orderId'] : '';
$resultCode = isset($_GET['resultCode']) ? $_GET['resultCode'] : '';
$signature = isset($_GET['signature']) ? $_GET['signature'] : '';

// Kiểm tra chữ ký bảo mật
$rawHash = "accessKey=" . MoMoConfig::$accessKey .
    "&amount=" . $_GET['amount'] .
    "&extraData=" . $_GET['extraData'] .
    "&message=" . $_GET['message'] .
    "&orderId=" . $orderId .
    "&orderInfo=" . $_GET['orderInfo'] .
    "&orderType=" . $_GET['orderType'] .
    "&partnerCode=" . $_GET['partnerCode'] .
    "&payType=" . $_GET['payType'] .
    "&requestId=" . $_GET['requestId'] .
    "&responseTime=" . $_GET['responseTime'] .
    "&resultCode=" . $resultCode .
    "&transId=" . $_GET['transId'];
$checkSignature = hash_hmac("sha256", $rawHash, MoMoConfig::$secretKey);

$pending_order = Session::get('pending_order');
if (!$pending_order || $pending_order['order_id'] != $orderId) {
    $message = "Không tìm thấy thông tin đơn hàng hoặc mã đơn hàng không khớp.";
} elseif ($signature !== $checkSignature) {
    $message = "Chữ ký không hợp lệ. Giao dịch có thể đã bị thay đổi.";
} else {
    if ($resultCode == '0') { // Thành công
        $result = $order->insert_order($pending_order['customer_id'], $pending_order['products']);
        if ($result['success']) {
            $order_id = $result['order_id'];
            $db = new Database();
            $query = "INSERT INTO momo_transactions (order_id, order_momo_id, amount, result_code, trans_id) 
                      VALUES ('$order_id', '$orderId', '{$pending_order['amount']}', '$resultCode', '{$_GET['transId']}')";
            $insert_result = $db->insert($query);

            if ($insert_result) {
                $ct->clear_cart();
                unset($_SESSION['pending_order']);
                header("Location: success.php?order_id=" . $order_id);
                exit();
            } else {
                $message = "Thanh toán thành công nhưng lỗi khi lưu giao dịch.";
            }
        } else {
            $message = "Lỗi khi lưu đơn hàng: " . $result['message'];
        }
    } else {
        $db = new Database();
        $query = "INSERT INTO momo_transactions (order_id, order_momo_id, amount, result_code, trans_id) 
                  VALUES (0, '$orderId', '{$pending_order['amount']}', '$resultCode', '{$_GET['transId']}')";
        $db->insert($query);
        $message = "Thanh toán thất bại. Mã lỗi: $resultCode. Vui lòng thử lại.";
    }
}
?>

<div class="main">
    <div class="content">
        <div class="section group">
            <div class="content_top">
                <h3>Kết quả thanh toán</h3>
                <p style="text-align: center; color: <?php echo $resultCode == '0' ? '#28a745' : '#ff3333'; ?>">
                    <?php echo $message; ?>
                </p>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="index.php" class="back-btn">Quay về trang chủ</a>
                    <?php if ($resultCode != '0') { ?>
                        <a href="order.php" class="retry-btn">Thử lại giao dịch</a>
                    <?php } ?>
                </div>
            </div>
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

    .content_top {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h3 {
        color: #4203a9;
        font-size: 24px;
        text-align: center;
        margin-bottom: 20px;
    }

    .back-btn,
    .retry-btn {
        display: inline-block;
        padding: 12px 20px;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin: 0 10px;
    }

    .back-btn {
        background: #666;
    }

    .back-btn:hover {
        background: #555;
    }

    .retry-btn {
        background: #ff6600;
    }

    .retry-btn:hover {
        background: #e65c00;
    }
</style>