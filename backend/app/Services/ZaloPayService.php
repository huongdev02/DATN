<?php

namespace App\Services;

use GuzzleHttp\Client;

class ZaloPayService
{
    protected $appId;
    protected $macKey;
    protected $callbackKey;
    protected $rsaPublicKey;
    
    public function __construct()
    {
        // Nhập các thông tin từ trang quản lý ZaloPay của bạn
        $this->appId = env('ZALOPAY_APP_ID'); // App ID từ ZaloPay
        $this->macKey = env('ZALOPAY_MAC_KEY'); // Mac Key từ ZaloPay
        $this->callbackKey = env('ZALOPAY_CALLBACK_KEY'); // Callback Key từ ZaloPay
        $this->rsaPublicKey = env('ZALOPAY_RSA_PUBLIC_KEY'); // RSA Public Key từ ZaloPay
    }

    public function createOrder($amount, $orderId)
    {
        $client = new Client();
        
        $data = [
            'app_id' => $this->appId,
            'amount' => $amount,
            'order_id' => $orderId,
            'description' => 'Payment for order ' . $orderId,
            'return_url' => env('ZALOPAY_RETURN_URL'), // URL trả về sau khi thanh toán
            'cancel_url' => env('ZALOPAY_CANCEL_URL'), // URL khi người dùng hủy thanh toán
        ];

        // Tạo chữ ký MAC cho yêu cầu (Cách tạo chữ ký sẽ được cung cấp từ tài liệu của ZaloPay)
        $mac = $this->generateMac($data);
        $data['mac'] = $mac;

        // Gửi yêu cầu tới ZaloPay API
        $response = $client->post('https://sandbox.zalopay.vn/v2/create', [
            'json' => $data,
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);

        return $responseData;
    }

    // Phương thức để tạo chữ ký MAC
    private function generateMac($data)
    {
        ksort($data); // Sắp xếp dữ liệu theo thứ tự tăng dần theo khóa

        $macString = '';
        foreach ($data as $key => $value) {
            $macString .= $key . '=' . $value . '&';
        }
        $macString = rtrim($macString, '&');

        return strtoupper(hash_hmac('sha256', $macString, $this->macKey));
    }
}
