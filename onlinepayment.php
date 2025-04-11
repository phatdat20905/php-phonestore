<?php
include 'inc/header.php';
include 'lib/config_vnpay.php'; // Cấu hình VNPAY
include 'lib/config_momo.php'; // Cấu hình MoMo

$login_check = Session::get('customer_login');
if ($login_check == false) {
	header('Location: login.php');
	exit;
}

$ct = new Cart();
$order = new Order();

// Hàm từ mẫu GitHub của MoMo
function execPostRequest($url, $data)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt(
		$ch,
		CURLOPT_HTTPHEADER,
		array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data)
		)
	);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

// Xử lý thanh toán VNPAY
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vnpay_payment'])) {
	error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
	date_default_timezone_set('Asia/Ho_Chi_Minh');

	$customer_id = Session::get('customer_id');
	$subtotal = Session::get('sum');
	$vnp_Amount = $subtotal + $subtotal * 0.1; // Số tiền (bao gồm 10% phí nếu có)
	$vnp_Locale = "vn"; // Ngôn ngữ mặc định
	$vnp_BankCode = ""; // Để trống để người dùng chọn ngân hàng
	$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

	$vnp_TxnRef = time() . "_" . $customer_id; // Mã giao dịch duy nhất
	$expire = VNPAYConfig::getExpireTime(); // Thời gian hết hạn

	$cart_items = $ct->get_cart_items();
	$products = [];
	while ($item = $cart_items->fetch_assoc()) {
		$products[] = [
			'product_id' => $item['product_id'],
			'product_name' => $item['product_name'],
			'quantity' => $item['quantity'],
			'price' => $item['price']
		];
	}
	Session::set('pending_order', [
		'customer_id' => $customer_id,
		'products' => $products,
		'txn_ref' => $vnp_TxnRef,
		'amount' => $vnp_Amount
	]);

	$inputData = array(
		"vnp_Version" => "2.1.0",
		"vnp_TmnCode" => VNPAYConfig::$vnp_TmnCode,
		"vnp_Amount" => $vnp_Amount * 100, // Nhân 100 theo chuẩn VNPAY
		"vnp_Command" => "pay",
		"vnp_CreateDate" => date('YmdHis'),
		"vnp_CurrCode" => "VND",
		"vnp_IpAddr" => $vnp_IpAddr,
		"vnp_Locale" => $vnp_Locale,
		"vnp_OrderInfo" => "Thanh toan GD: " . $vnp_TxnRef,
		"vnp_OrderType" => "other",
		"vnp_ReturnUrl" => VNPAYConfig::$vnp_ReturnUrl,
		"vnp_TxnRef" => $vnp_TxnRef,
		"vnp_ExpireDate" => $expire
	);

	if (!empty($vnp_BankCode)) {
		$inputData['vnp_BankCode'] = $vnp_BankCode;
	}

	ksort($inputData);
	$query = "";
	$i = 0;
	$hashdata = "";
	foreach ($inputData as $key => $value) {
		if ($i == 1) {
			$hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
		} else {
			$hashdata .= urlencode($key) . "=" . urlencode($value);
			$i = 1;
		}
		$query .= urlencode($key) . "=" . urlencode($value) . '&';
	}

	$vnp_Url = VNPAYConfig::$vnp_Url . "?" . rtrim($query, '&');
	$vnpSecureHash = hash_hmac('sha512', $hashdata, VNPAYConfig::$vnp_HashSecret);
	$vnp_Url .= "&vnp_SecureHash=" . $vnpSecureHash;

	header('Location: ' . $vnp_Url);
	die();
}

// Xử lý thanh toán MoMo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['momo_payment'])) {
	$customer_id = Session::get('customer_id');
	$subtotal = Session::get('sum');
	$amount = $subtotal + $subtotal * 0.1;

	$orderId = time() . "_" . $customer_id;
	$requestId = $orderId;
	$orderInfo = "Thanh toán đơn hàng #$orderId";
	$requestType = "captureWallet";
	$extraData = "";

	$cart_items = $ct->get_cart_items();
	$products = [];
	while ($item = $cart_items->fetch_assoc()) {
		$products[] = [
			'product_id' => $item['product_id'],
			'product_name' => $item['product_name'],
			'quantity' => $item['quantity'],
			'price' => $item['price']
		];
	}
	Session::set('pending_order', [
		'customer_id' => $customer_id,
		'products' => $products,
		'order_id' => $orderId,
		'amount' => $amount
	]);

	$rawHash = "accessKey=" . MoMoConfig::$accessKey .
		"&amount=" . $amount .
		"&extraData=" . $extraData .
		"&ipnUrl=" . MoMoConfig::$notifyUrl .
		"&orderId=" . $orderId .
		"&orderInfo=" . $orderInfo .
		"&partnerCode=" . MoMoConfig::$partnerCode .
		"&redirectUrl=" . MoMoConfig::$returnUrl .
		"&requestId=" . $requestId .
		"&requestType=" . $requestType;
	$signature = hash_hmac("sha256", $rawHash, MoMoConfig::$secretKey);

	$data = array(
		'partnerCode' => MoMoConfig::$partnerCode,
		'partnerName' => "WebBanHang",
		'storeId' => "MoMoTestStore",
		'requestId' => $requestId,
		'amount' => $amount,
		'orderId' => $orderId,
		'orderInfo' => $orderInfo,
		'redirectUrl' => MoMoConfig::$returnUrl,
		'ipnUrl' => MoMoConfig::$notifyUrl,
		'lang' => 'vi',
		'extraData' => $extraData,
		'requestType' => $requestType,
		'signature' => $signature
	);

	$jsonData = json_encode($data);
	$result = execPostRequest(MoMoConfig::$momo_Url, $jsonData);
	$jsonResult = json_decode($result, true);

	if (isset($jsonResult['payUrl'])) {
		header('Location: ' . $jsonResult['payUrl']);
		exit();
	} else {
		echo "Lỗi khi kết nối MoMo: " . $result;
	}
}
?>

<div class="main">
	<div class="content">
		<div class="section group">
			<div class="content_top">
				<h3>Phương thức thanh toán Online</h3>
				<div class="wrapper_method">
					<h4>Chọn cổng thanh toán:</h4>
					<form method="post" style="display: inline;">
						<button type="submit" name="vnpay_payment" class="payment-btn">Thanh toán bằng VNPAY</button>
					</form>
					<form method="post" style="display: inline;">
						<button type="submit" name="momo_payment" class="payment-btn">Thanh toán bằng MoMo</button>
					</form>
				</div>
				<div style="text-align: center;">
					<a href="payment.php" class="back-btn">Quay lại chọn phương thức</a>
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

	.wrapper_method {
		text-align: center;
		padding: 20px;
	}

	h4 {
		color: #333;
		margin-bottom: 20px;
	}

	.payment-btn {
		display: inline-block;
		padding: 12px 20px;
		background: #4203a9;
		color: white;
		text-decoration: none;
		border: none;
		border-radius: 5px;
		margin: 10px;
		cursor: pointer;
	}

	.payment-btn:hover {
		background: #35028c;
	}

	.back-btn {
		display: inline-block;
		padding: 12px 20px;
		background: #666;
		color: white;
		text-decoration: none;
		border-radius: 5px;
		margin-top: 20px;
	}

	.back-btn:hover {
		background: #555;
	}
</style>