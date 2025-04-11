<?php
include 'inc/header.php';
// include 'inc/slider.php';
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
	$name = $fm->validation($_POST['name']);
	$email = $fm->validation($_POST['email']);
	$phone = $fm->validation($_POST['phone']);
	$message = $fm->validation($_POST['message']);

	$result = $contact->insert_contact($name, $email, $phone, $message);
	if ($result['success']) {
		$success_msg = $result['message'];
	} else {
		$error_msg = $result['message'];
	}
}
?>

<div class="main">
	<div class="content">
		<div class="support">
			<div class="support_desc">
				<h3>Hỗ trợ trực tuyến</h3>
				<p><span>24 giờ | 7 ngày/tuần | 365 ngày/năm - Hỗ trợ kỹ thuật trực tiếp</span></p>
				<p>Chúng tôi luôn sẵn sàng hỗ trợ bạn bất cứ lúc nào. Đội ngũ chuyên nghiệp của chúng tôi cam kết mang đến giải pháp nhanh chóng và hiệu quả cho mọi thắc mắc của bạn. Hãy liên hệ với chúng tôi để nhận được sự hỗ trợ tốt nhất!</p>
			</div>
			<img src="images/contact.png" alt="Hỗ trợ" />
			<div class="clear"></div>
		</div>
		<div class="section group">
			<div class="col span_2_of_3">
				<div class="contact-form">
					<h2>Liên hệ với chúng tôi</h2>
					<?php
					if (isset($success_msg)) {
						echo "<p style='color: green; text-align: center;'>$success_msg</p>";
					}
					if (isset($error_msg)) {
						echo "<p style='color: red; text-align: center;'>$error_msg</p>";
					}
					?>
					<form method="post" action="">
						<div>
							<span><label>HỌ VÀ TÊN</label></span>
							<span><input type="text" name="name" required placeholder="Nhập họ và tên" /></span>
						</div>
						<div>
							<span><label>E-MAIL</label></span>
							<span><input type="email" name="email" required placeholder="Nhập email" /></span>
						</div>
						<div>
							<span><label>SỐ ĐIỆN THOẠI</label></span>
							<span><input type="text" name="phone" required placeholder="Nhập số điện thoại" /></span>
						</div>
						<div>
							<span><label>TIN NHẮN</label></span>
							<span><textarea name="message" required placeholder="Nhập tin nhắn của bạn"></textarea></span>
						</div>
						<div>
							<span><input type="submit" name="submit" value="GỬI" class="submit_btn" /></span>
						</div>
					</form>
				</div>
			</div>
			<div class="col span_1_of_3">
				<div class="company_address">
					<h2>Thông tin công ty</h2>
					<p>123 Đường Công Nghệ Cao,</p>
					<p>Phường Tân Phú, Quận 9,</p>
					<p>TP. Hồ Chí Minh, Việt Nam</p>
					<p>Điện thoại: (+84) 123 456 789</p>
					<p>Email: <span>support@mycompany.com</span></p>
					<p>Theo dõi chúng tôi trên: <span>Facebook</span>, <span>Instagram</span></p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
include 'inc/footer.php';
?>