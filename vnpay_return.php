<?php
include 'inc/header.php';
include 'lib/config_vnpay.php';

$login_check = Session::get('customer_login');
if ($login_check == false) {
    header('Location: login.php');
    exit;
}

$order = new Order();
$ct = new Cart();

$vnp_SecureHash = isset($_GET['vnp_SecureHash']) ? $_GET['vnp_SecureHash'] : '';
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}
unset($inputData['vnp_SecureHash']);
ksort($inputData);
$hashdata = "";
$i = 0;
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}
$secureHash = hash_hmac('sha512', $hashdata, VNPAYConfig::$vnp_HashSecret);

$db = new Database();

if ($secureHash === $vnp_SecureHash) {
    $pending_order = Session::get('pending_order');
    $vnp_response_code = isset($inputData['vnp_ResponseCode']) ? $inputData['vnp_ResponseCode'] : 'N/A';

    if (!$pending_order) {
        $message = "Không tìm thấy thông tin đơn hàng tạm thời. Vui lòng thử lại từ đầu.";
    } else {
        $vnp_txn_ref = $inputData['vnp_TxnRef'];
        $vnp_amount = $inputData['vnp_Amount'];
        $vnp_transaction_status = $inputData['vnp_TransactionStatus'];
        $vnp_bank_code = isset($inputData['vnp_BankCode']) ? $inputData['vnp_BankCode'] : '';
        $vnp_pay_date = isset($inputData['vnp_PayDate']) ? date('Y-m-d H:i:s', strtotime($inputData['vnp_PayDate'])) : NULL;

        if ($vnp_response_code == '00') {
            $result = $order->insert_order($pending_order['customer_id'], $pending_order['products']);
            if ($result['success']) {
                $order_id = $result['order_id'];
                $query = "INSERT INTO vnpay_transactions (order_id, vnp_txn_ref, vnp_amount, vnp_response_code, vnp_transaction_status, vnp_bank_code, vnp_pay_date) 
                          VALUES ('$order_id', '$vnp_txn_ref', '$vnp_amount', '$vnp_response_code', '$vnp_transaction_status', '$vnp_bank_code', " . ($vnp_pay_date ? "'$vnp_pay_date'" : "NULL") . ")";
                $insert_result = $db->insert($query);

                if ($insert_result) {
                    $ct->clear_cart();
                    unset($_SESSION['pending_order']);
                    header("Location: success.php?order_id=" . $order_id);
                    exit();
                } else {
                    $message = "Thanh toán thành công nhưng lỗi khi lưu giao dịch VNPAY.";
                }
            } else {
                $message = "Lỗi khi lưu đơn hàng: " . $result['message'];
            }
        } else {
            // Khi giao dịch thất bại hoặc hủy, dùng NULL cho order_id
            $query = "INSERT INTO vnpay_transactions (order_id, vnp_txn_ref, vnp_amount, vnp_response_code, vnp_transaction_status, vnp_bank_code, vnp_pay_date) 
                      VALUES (NULL, '$vnp_txn_ref', '$vnp_amount', '$vnp_response_code', '$vnp_transaction_status', '$vnp_bank_code', " . ($vnp_pay_date ? "'$vnp_pay_date'" : "NULL") . ")";
            $insert_result = $db->insert($query);

            if ($insert_result) {
                $message = "Thanh toán thất bại! Mã lỗi: " . $vnp_response_code . ". Vui lòng thử lại.";
            } else {
                $message = "Thanh toán thất bại và lỗi khi lưu giao dịch.";
            }
        }
    }
} else {
    $message = "Chữ ký không hợp lệ hoặc lỗi kết nối VNPAY. Vui lòng thử lại.";
}
?>

<div class="main">
    <div class="content">
        <div class="section group">
            <div class="content_top">
                <h3>Kết quả thanh toán</h3>
                <p style="text-align: center; color: <?php echo $vnp_response_code == '00' ? '#28a745' : '#ff3333'; ?>">
                    <?php echo $message; ?>
                </p>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="index.php" class="back-btn">Quay về trang chủ</a>
                    <?php if ($vnp_response_code != '00') { ?>
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