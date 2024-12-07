<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
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

            if ($vnpSecureHash !== $secureHashCheck) {
                Log::warning('Invalid secure hash', ['vnp_TxnRef' => $vnpTxnRef]);
                return response()->json(['message' => 'Invalid secure hash.'], 400);
            }

            // Kiểm tra mã kết quả thanh toán
            $order = Order::find($vnpTxnRef);

            if (!$order) {
                Log::warning('Order not found', ['vnp_TxnRef' => $vnpTxnRef]);
                return response()->json(['message' => 'Order not found.'], 404);
            }

            if ($vnpResponseCode === '00') {
                Payments::create([
                    'order_id' => $order->id,
                    'transaction_id' => $vnpTransactionNo,
                    'payment_method' => 'online',
                    'amount' => $vnpAmount / 100,
                    'status' => 'success',
                    'response_code' => $vnpResponseCode,
                    'secure_hash' => $vnpSecureHash,
                ]);

                $order->status = 1; // Đã thanh toán
                $order->save();

                Log::info('Payment successful', ['order_id' => $order->id]);
                return response()->json(['message' => 'Payment successful, payment record saved.'], 200);
            } else {
                Payments::create([
                    'order_id' => $order->id,
                    'transaction_id' => $vnpTransactionNo,
                    'payment_method' => 'online',
                    'amount' => $vnpAmount / 100,
                    'status' => 'failed',
                    'response_code' => $vnpResponseCode,
                    'secure_hash' => $vnpSecureHash,
                ]);

                Log::warning('Payment failed', [
                    'order_id' => $order->id,
                    'response_code' => $vnpResponseCode,
                ]);

                return response()->json(['message' => 'Payment failed.'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Payment handling error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    // Hàm kiểm tra và tạo mã hash để xác thực kết quả thanh toán
    private function generateVNPaySecureHash(Request $request, $secretKey)
    {
        // Lấy tất cả các tham số từ request, loại trừ 'vnp_SecureHash'
        $vnpParams = $request->except('vnp_SecureHash');

        // Sắp xếp tham số theo thứ tự bảng chữ cái
        ksort($vnpParams);

        // Tạo chuỗi query
        $query = urldecode(http_build_query($vnpParams));

        Log::info('Generated hash data', ['query' => $query]); // Ghi log chuỗi query

        // Tạo mã bảo mật (Secure Hash) bằng cách sử dụng hash_hmac
        return hash_hmac('sha512', $query, $secretKey);
    }
}
