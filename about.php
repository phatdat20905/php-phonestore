<?php
include 'inc/header.php';
?>

<div class="main">
    <div class="content">
        <div class="about-section">
            <h1>Giới thiệu về chúng tôi</h1>
            <p>Chào mừng bạn đến với <strong>Web Bán Hàng Online</strong> - địa chỉ mua sắm trực tuyến đáng tin cậy tại Việt Nam!</p>
            <p>Chúng tôi tự hào là đơn vị cung cấp các sản phẩm chất lượng cao, từ thiết bị điện tử, thời trang, đến đồ gia dụng với giá cả cạnh tranh và dịch vụ tận tâm. Được thành lập vào năm 2020, sứ mệnh của chúng tôi là mang đến trải nghiệm mua sắm tiện lợi, nhanh chóng và an toàn cho mọi khách hàng.</p>
            <h2>Tại sao chọn chúng tôi?</h2>
            <ul>
                <li><strong>Sản phẩm chính hãng:</strong> Cam kết 100% hàng hóa từ các thương hiệu uy tín.</li>
                <li><strong>Giao hàng nhanh chóng:</strong> Ship COD toàn quốc, nhận hàng trong 24-48 giờ.</li>
                <li><strong>Hỗ trợ tận tình:</strong> Đội ngũ chăm sóc khách hàng luôn sẵn sàng 24/7.</li>
                <li><strong>Chính sách đổi trả:</strong> Đổi trả dễ dàng trong 7 ngày nếu sản phẩm lỗi.</li>
            </ul>
            <h2>Đội ngũ của chúng tôi</h2>
            <p>Với đội ngũ hơn 50 nhân viên giàu kinh nghiệm, chúng tôi không ngừng nỗ lực để cải thiện dịch vụ và đáp ứng nhu cầu ngày càng cao của khách hàng.</p>
            <p>Hãy theo dõi chúng tôi trên các kênh mạng xã hội hoặc liên hệ qua <a href="contact.php">trang liên hệ</a> để biết thêm chi tiết!</p>
        </div>
    </div>
</div>

<?php
include 'inc/footer.php';
?>

<style>
    .about-section {
        padding: 20px;
        max-width: 800px;
        margin: 0 auto;
    }

    .about-section h1 {
        font-size: 28px;
        color: #ff6600;
        text-align: center;
        margin-bottom: 20px;
    }

    .about-section h2 {
        font-size: 20px;
        color: #333;
        margin: 20px 0 10px;
    }

    .about-section p {
        font-size: 16px;
        color: #666;
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .about-section ul {
        list-style: none;
        padding: 0;
    }

    .about-section ul li {
        font-size: 16px;
        color: #333;
        margin-bottom: 10px;
        padding-left: 20px;
        position: relative;
    }

    .about-section ul li:before {
        content: "✔";
        color: #ff6600;
        position: absolute;
        left: 0;
    }

    .about-section a {
        color: #ff6600;
        text-decoration: none;
    }

    .about-section a:hover {
        text-decoration: underline;
    }
</style>