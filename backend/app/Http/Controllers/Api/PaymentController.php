<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Voucher_usage;
use Illuminate\Http\Request;
use App\Models\Payments;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function handlePaymentResult(Request $request)
    {
        try {
            Log::info('Payment result received', ['data' => $request->all()]);

            // Lấy các tham số trả về từ VNPay
            $vnpAmount = $request->input('vnp_Amount');
            $vnpTransactionNo = $request->input('vnp_TransactionNo');
            $vnpResponseCode = $request->input('vnp_ResponseCode');
            $vnpTxnRef = $request->input('vnp_TxnRef');
            $vnpSecureHash = $request->input('vnp_SecureHash');

            // Lấy secret key từ file .env
            $vnpHashSecret = env('VNP_HASH_SECRET');

            // Kiểm tra chữ ký bảo mật
            $secureHashCheck = $this->generateVNPaySecureHash($request, $vnpHashSecret);

            Log::info('Secure hash comparison', [
                'vnp_SecureHash' => $vnpSecureHash,
                'generated_hash' => $secureHashCheck,
            ]);

            // Kiểm tra sự khớp của mã hash
            if ($vnpSecureHash !== $secureHashCheck) {
                Log::warning('Invalid secure hash', ['vnp_TxnRef' => $vnpTxnRef]);
                return response()->json(['message' => 'Invalid secure hash.'], 400);
            }

            // Kiểm tra mã kết quả thanh toán
            $order = Order::find($vnpTxnRef);

            if (!$order) {
                Log::warning('Order not found', ['vnp_TxnRef' => $vnpTxnRef]);
                return redirect('http://localhost:3000/order-error');
            }

            // Nếu mã thanh toán thành công
            if ($vnpResponseCode === '00') {
                Payment::create([
                    'order_id' => $order->id,
                    'transaction_id' => $vnpTransactionNo,
                    'payment_method' => 'online',
                    'amount' => $vnpAmount / 100,
                    'status' => 'success',
                    'response_code' => $vnpResponseCode,
                    'secure_hash' => $vnpSecureHash,
                ]);

                $order->message = 'Đã thanh toán'; // Đã thanh toán
                $order->save();

                Log::info('Payment successful', ['order_id' => $order->id]);
                return redirect('http://localhost:3000/thank');
            } else {
                // Tạo bản ghi thanh toán thất bại
                Payment::create([
                    'order_id' => $order->id,
                    'transaction_id' => $vnpTransactionNo,
                    'payment_method' => 'online',
                    'amount' => $vnpAmount / 100,
                    'status' => 'failed',
                    'response_code' => $vnpResponseCode,
                    'secure_hash' => $vnpSecureHash,
                ]);
            
                // Kiểm tra và xóa voucher nếu có
                $voucherUsage = Voucher_usage::where('order_id', $order->id)->first();
                if ($voucherUsage) {
                    // Xóa voucher sử dụng
                    $voucherUsage->delete();
                }
            
                // Cập nhật trạng thái đơn hàng và thông báo
                $order->status = 4; // Trạng thái "Hủy"
                $order->message = 'Đơn hàng của bạn đã bị hủy do thanh toán thất bại'; // Thông báo cho người dùng
                $order->save();
            
                return redirect('http://localhost:3000/order-error');
            }
            
        } catch (\Exception $e) {
            Log::error('Payment handling error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return redirect('http://localhost:3000');
        }
    }
    private function generateVNPaySecureHash(Request $request, $secretKey)
    {
        $vnpParams = $request->except('vnp_SecureHash');
        ksort($vnpParams);
        $query = '';
        foreach ($vnpParams as $key => $value) {
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }
        $query = rtrim($query, '&');
        return hash_hmac('sha512', $query, $secretKey);
    }
}
