<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

class VNPAYConfig
{
    public static $vnp_TmnCode = "R407PX71"; // Thay bằng TmnCode từ sandbox VNPAY
    public static $vnp_HashSecret = "9Q62N043W9UI969VT44KESCVNB5NBRPV"; // Thay bằng HashSecret từ sandbox VNPAY
    public static $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"; // URL Sandbox
    public static $vnp_ReturnUrl = "http://localhost/phonestore/vnpay_return.php"; // Thay bằng URL Ngrok
    public static $vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
    public static $apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";

    // Thời gian hết hạn (15 phút từ khi tạo)
    public static function getExpireTime()
    {
        $startTime = date("YmdHis");
        return date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
    }
}
