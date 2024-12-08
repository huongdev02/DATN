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

            // Kiểm tra sự khớp của mã hash
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

            // Nếu mã thanh toán thành công
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

                $order->message = 'Đã thanh toán'; // Đã thanh toán
                $order->save();

                Log::info('Payment successful', ['order_id' => $order->id]);
                return response()->json([
                    'status'    => true,
                    'message' => 'Thanh toán thành công'
                ], 200);
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

                $order->status = 4; // Trạng thái "Hủy"
                $order->message = 'Đơn hàng của bạn đã bị hủy do thanh toán thất bại'; // Thông báo cho người dùng
                $order->save();

                return response()->json(['message' => 'Thanh toán thất bại, khởi tạo đơn hàng không thành công'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Payment handling error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    private function generateVNPaySecureHash(Request $request, $secretKey)
    {
        // Exclude the 'vnp_SecureHash' parameter
        $vnpParams = $request->except('vnp_SecureHash');

        // Sort the parameters alphabetically by key
        ksort($vnpParams);

        // Build the query string
        $query = '';
        foreach ($vnpParams as $key => $value) {
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }
        // Remove the last '&'
        $query = rtrim($query, '&');

        // Log the query string before generating the hash
        Log::info('Generated query string before hash generation', ['query' => $query]);

        // Generate the secure hash using HMAC-SHA512
        return hash_hmac('sha512', $query, $secretKey);
    }
}
