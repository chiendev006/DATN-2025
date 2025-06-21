<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    public function redirectToVnpay(Request $request)
    {
        try {
            $order = session('vnp_order');
            if (!$order) {
                Log::error('VNPAY Redirect: No order information in session');
                return redirect()->route('checkout.index')->with('error', 'Không tìm thấy thông tin đơn hàng');
            }

            $total = $order['total'] ?? 0;
            if ($total <= 0) {
                Log::error('VNPAY Redirect: Invalid order total', ['total' => $total]);
                return redirect()->route('checkout.index')->with('error', 'Số tiền thanh toán không hợp lệ');
            }

            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = route('vnpay.return');
            $vnp_TmnCode = "ZMYT5AE4";
            $vnp_HashSecret = "1UCXBCKLKT6HJBREEWRX1FS5HHR6YVEX";

            $vnp_TxnRef = time() . "-" . rand(1000,9999);
            $vnp_OrderInfo = 'Thanh toán đơn hàng qua VNPAY';
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $total * 100;
            $vnp_Locale = 'vn';
            $vnp_IpAddr = request()->ip();

            $inputData = [
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef
            ];

            ksort($inputData);
            $query = "";
            $hashdata = "";
            $i = 0;
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url .= "?" . $query;
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

            session([
                'vnp_transaction' => [
                    'amount' => $vnp_Amount,
                    'txnRef' => $vnp_TxnRef
                ]
            ]);

            return redirect($vnp_Url);
        } catch (\Exception $e) {
            Log::error('VNPAY Redirect Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('checkout.index')->with('error', 'Có lỗi xảy ra khi kết nối với VNPAY');
        }
    }

    public function vnpayReturn(Request $request)
    {
        try {
            $vnpData = $request->all();
            Log::info('VNPay Return Data:', $vnpData);

            $vnpOrder = session('vnp_order');
            $vnpTransaction = session('vnp_transaction');

            if (!$vnpOrder || !$vnpTransaction) {
                Log::error('VNPAY Return: Missing session data', [
                    'has_order' => !empty($vnpOrder),
                    'has_transaction' => !empty($vnpTransaction)
                ]);
                return redirect()->route('checkout.index')->with('error', 'Phiên giao dịch không hợp lệ!');
            }

            $vnp_HashSecret = "1UCXBCKLKT6HJBREEWRX1FS5HHR6YVEX";
            $inputData = [];
            foreach ($vnpData as $key => $value) {
                if (substr($key, 0, 4) == "vnp_") {
                    $inputData[$key] = $value;
                }
            }
            unset($inputData['vnp_SecureHash']);
            ksort($inputData);
            $hashData = "";
            $i = 0;
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashData = urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }
            $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

            if ($secureHash === $vnpData['vnp_SecureHash'] && $vnpData['vnp_ResponseCode'] == '00') {
                try {
                    DB::beginTransaction();

                    $existingOrder = \App\Models\Order::where('transaction_id', $vnpData['vnp_TransactionNo'])->first();
                    if ($existingOrder) {
                        Log::warning('VNPAY Return: Transaction already processed', [
                            'transaction_no' => $vnpData['vnp_TransactionNo']
                        ]);
                        return redirect()->route('checkout.index')->with('error', 'Giao dịch này đã được xử lý trước đó');
                    }

                   $order = new \App\Models\Order();
                    $order->user_id = $vnpOrder['user_id'];
                    $order->name = $vnpOrder['name'];
                    $order->phone = $vnpOrder['phone'];
                    $order->email = $vnpOrder['email'];
                    $order->address_id = $vnpOrder['address_id'];
                    $order->address_detail = $vnpOrder['address_detail'];
                    $order->district_name = $vnpOrder['district_name'];
                    $order->payment_method = 'banking';
                    $order->status =  'pending';
                    $order->total = $vnpOrder['total'];
                    $order->shipping_fee = $vnpOrder['shipping_fee'];
                    $order->pay_status = '1';
                    $order->coupon_summary = $vnpOrder['coupon_summary'];
                    $order->coupon_total_discount = $vnpOrder['coupon_total_discount'];
                    $order->note = $vnpOrder['note'];
                    $order->transaction_id = $vnpData['vnp_TransactionNo'];

                    if (!$order->save()) {
                        throw new \Exception('Không thể lưu đơn hàng');
                    }

                    foreach ($vnpOrder['details'] as $detail) {
                        $orderDetail = new \App\Models\OrderDetail();
                        $orderDetail->order_id = $order->id;
                        $orderDetail->product_id = $detail['product_id'];
                        $orderDetail->product_name = $detail['product_name'];
                        $orderDetail->product_price = $detail['product_price'];
                        $orderDetail->quantity = $detail['quantity'];
                        $orderDetail->total = $detail['total'];
                        $orderDetail->size_id = $detail['size_id'] ?? null;
                        $orderDetail->topping_id = $detail['topping_id'] ?? null;
                        $orderDetail->status = $detail['status'] ?? 'pending';

                        if (!$orderDetail->save()) {
                            throw new \Exception('Không thể lưu chi tiết đơn hàng');
                        }
                    }

                    if (Auth::check()) {
                        $userCart = \App\Models\Cart::where('user_id', Auth::id())->first();
                        if ($userCart) {
                            \App\Models\Cartdetail::where('cart_id', $userCart->id)->delete();
                            $userCart->delete();
                        }
                    }

                    session()->forget(['cart', 'coupons', 'vnp_order', 'vnp_transaction']);
                    DB::commit();

                    Log::info('VNPAY Payment Success', [
                        'order_id' => $order->id,
                        'transaction_id' => $vnpData['vnp_TransactionNo']
                    ]);

                    return redirect()->route('order.complete', $order->id)
                        ->with('success', 'Thanh toán thành công!');
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('VNPAY Return DB Error: ' . $e->getMessage(), [
                        'exception' => $e,
                        'trace' => $e->getTraceAsString()
                    ]);
                    return redirect()->route('checkout.index')->with('error', 'Lỗi xử lý đơn hàng: ' . $e->getMessage());
                }
            } else {
                Log::warning('VNPAY Return: Payment failed or invalid signature', [
                    'response_code' => $vnpData['vnp_ResponseCode'] ?? null,
                    'secure_hash_valid' => ($secureHash === ($vnpData['vnp_SecureHash'] ?? null))
                ]);
                return redirect()->route('checkout.index')
                    ->with('error', 'Thanh toán thất bại hoặc dữ liệu không hợp lệ!');
            }
        } catch (\Exception $e) {
            Log::error('VNPAY Return Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('checkout.index')
                ->with('error', 'Có lỗi xảy ra trong quá trình xử lý thanh toán');
        }
    }
}
