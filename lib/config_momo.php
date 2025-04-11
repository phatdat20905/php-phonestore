<?php
class MoMoConfig
{
    public static $partnerCode = 'MOMOBKUN20180529'; // Thay bằng Partner Code từ MoMo
    public static $accessKey = 'klm05TvNBzhg7h7j';     // Thay bằng Access Key từ MoMo
    public static $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';     // Thay bằng Secret Key từ MoMo
    public static $momo_Url = 'https://test-payment.momo.vn/v2/gateway/api/create'; // URL Sandbox
    public static $returnUrl = 'http://localhost/phonestore/momo_return.php'; // URL trả về
    public static $notifyUrl = 'http://localhost/phonestore/momo_return.php'; // URL thông báo
}
