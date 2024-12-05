<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Payments;

class PaymentController extends Controller
{
    public function handlePaymentResult(Request $request)
    {
        try {
            // Lấy các tham số trả về từ VNPay
            $vnpAmount = $request->input('vnp_Amount'); // Số tiền thanh toán
            $vnpTransactionNo = $request->input('vnp_TransactionNo'); // Mã giao dịch VNPay
            $vnpResponseCode = $request->input('vnp_ResponseCode'); // Mã kết quả thanh toán
            $vnpTxnRef = $request->input('vnp_TxnRef'); // ID đơn hàng
            $vnpSecureHash = $request->input('vnp_SecureHash'); // Mã hash bảo mật
    
            // Kiểm tra chữ ký bảo mật (vnp_SecureHash) có hợp lệ không
            $vnpHashSecret = 'IIFJR43KSJ1C5KVRPSO3PJTU8DN4EKJ5'; // Secret key của bạn
            $secureHashCheck = $this->generateVNPaySecureHash($request, $vnpHashSecret);
    
            if ($vnpSecureHash !== $secureHashCheck) {
                return response()->json(['message' => 'Invalid secure hash.'], 400);
            }
    
            // Kiểm tra mã kết quả thanh toán từ VNPay
            if ($vnpResponseCode === '00') {
                // Thanh toán thành công, tạo bản ghi thanh toán trong bảng payments
                Payments::create([
                    'order_id' => $vnpTxnRef, // Gán ID đơn hàng
                    'transaction_id' => $vnpTransactionNo, // Lưu mã giao dịch
                    'payment_method' => 'online', // Phương thức thanh toán online
                    'amount' => $vnpAmount / 100, // Số tiền thanh toán (lưu dưới dạng decimal)
                    'status' => 'success', // Trạng thái thanh toán
                    'response_code' => $vnpResponseCode, // Mã phản hồi từ VNPay
                    'secure_hash' => $vnpSecureHash, // Lưu mã bảo mật
                ]);
    
                // Cập nhật trạng thái đơn hàng
                $order = Order::find($vnpTxnRef);
                if ($order) {
                    $order->message = 'đã thanh toán'; // Cập nhật trạng thái đơn hàng thành "Đã thanh toán"
                    $order->save();
                }
    
                return response()->json(['message' => 'Payment successful, payment record saved.'], 200);
            } else {
                // Thanh toán thất bại
                return response()->json(['message' => 'Payment failed.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    

    // Hàm kiểm tra và tạo mã hash để xác thực kết quả thanh toán
    private function generateVNPaySecureHash(Request $request, $secretKey)
    {
        $vnpParams = $request->except('vnp_SecureHash'); // Loại bỏ tham số vnp_SecureHash
        ksort($vnpParams); // Sắp xếp các tham số theo thứ tự
    
        // Xây dựng chuỗi query
        $query = http_build_query($vnpParams);
        // Tạo mã hash bảo mật
        $hashData = $query . '&' . 'vnp_HashSecret=' . $secretKey;
        return hash('sha256', $hashData); // Tạo mã hash bằng SHA-256
    }
    
}
