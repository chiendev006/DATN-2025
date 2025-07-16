<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .order-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .order-number {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        .info-value {
            color: #6c757d;
        }
        .products-section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
        }
        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f3f4;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-name {
            font-weight: 500;
            color: #2c3e50;
        }
        .product-details {
            color: #6c757d;
            font-size: 14px;
        }
        .product-price {
            font-weight: 600;
            color: #28a745;
        }
        .total-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .total-row.final {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            border-top: 2px solid #dee2e6;
            padding-top: 12px;
            margin-top: 12px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-processing {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .status-shipping {
            background-color: #cce5ff;
            color: #004085;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Xác nhận đơn hàng thành công!</h1>
        </div>
        
        <div class="content">
            <div class="order-info">
                <div class="order-number">Đơn hàng #{{ $order->id }}</div>
                
                <div class="info-row">
                    <span class="info-label">Tên khách hàng:</span>
                    <span class="info-value">{{ $order->name }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Số điện thoại:</span>
                    <span class="info-value">{{ $order->phone }}</span>
                </div>
                
                @if($order->email)
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $order->email }}</span>
                </div>
                @endif
                
                <div class="info-row">
                    <span class="info-label">Địa chỉ:</span>
                    <span class="info-value">{{ $order->address_detail }}{{ $order->district_name ? ', ' . $order->district_name : '' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Phương thức thanh toán:</span>
                    <span class="info-value">{{ $order->payment_method === 'cash' ? 'Tiền mặt' : 'Chuyển khoản' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Trạng thái thanh toán:</span>
                    <span class="info-value">
                        @if($order->pay_status == '0')
                            <span class="status-badge status-pending">Chờ thanh toán</span>
                        @elseif($order->pay_status == '1')
                            <span class="status-badge status-completed">Đã thanh toán</span>
                        @elseif($order->pay_status == '2')
                            <span class="status-badge status-cancelled">Đã hủy</span>
                        @elseif($order->pay_status == '3')
                            <span class="status-badge status-cancelled">Hoàn tiền</span>
                        @endif
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Trạng thái đơn hàng:</span>
                    <span class="info-value">
                        @if($order->status == 'pending')
                            <span class="status-badge status-pending">Chờ xử lý</span>
                        @elseif($order->status == 'processing')
                            <span class="status-badge status-processing">Đã xác nhận</span>
                        @elseif($order->status == 'shipping')
                            <span class="status-badge status-shipping">Đang giao</span>
                        @elseif($order->status == 'completed')
                            <span class="status-badge status-completed">Hoàn thành</span>
                        @elseif($order->status == 'cancelled')
                            <span class="status-badge status-cancelled">Đã hủy</span>
                        @endif
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Ngày đặt hàng:</span>
                    <span class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
            
            <div class="products-section">
                <div class="section-title"> Chi tiết sản phẩm</div>
                
                @foreach($orderDetails as $detail)
                <div class="product-item">
                    <div style="flex: 1;">
                        <div class="product-name">{{ $detail->product->name ?? 'Sản phẩm đã xóa' }}</div>
                        <div class="product-details">
                            @if($detail->size)
                                Size: {{ $detail->size->size }} ({{ number_format($detail->size->price, 0, ',', '.') }} đ) |
                            @endif
                            Số lượng: {{ $detail->quantity }}
                            @php
                                $toppingNames = [];
                                $toppingTotal = 0;
                                if (!empty($detail->topping_id)) {
                                    $toppingIds = array_filter(array_map('trim', explode(',', $detail->topping_id)));
                                    if (!empty($toppingIds)) {
                                        $toppings = \App\Models\Product_topping::whereIn('id', $toppingIds)->get();
                                        foreach ($toppings as $tp) {
                                            $toppingNames[] = $tp->topping . ' (' . number_format($tp->price, 0, ',', '.') . ' đ)';
                                            $toppingTotal += $tp->price;
                                        }
                                    }
                                }
                            @endphp
                            @if(count($toppingNames))
                                <br>Topping: {!! implode(', ', $toppingNames) !!}
                            @else
                                <br>Topping: <span style="color: red;">Không chọn</span>
                            @endif
                        </div>
                    </div>
                    <div class="product-price" style="text-align: right;">
                        @php
                            $sizePrice = $detail->size ? $detail->size->price : 0;
                            $lineTotal = ($sizePrice + $toppingTotal) * $detail->quantity;
                            // Nếu đã có trường total trong detail thì dùng luôn cho chuẩn admin
                            if (isset($detail->total)) {
                                $lineTotal = $detail->total;
                            }
                        @endphp
                        <div>{{ number_format($lineTotal, 0, ',', '.') }} đ</div>
                        <div style="font-size: 12px; color: #6c757d;">
                            ({{ number_format($sizePrice + $toppingTotal, 0, ',', '.') }} đ × {{ $detail->quantity }})
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="total-section">
                <div class="section-title"> Tổng thanh toán</div>
                @php
                    $productTotal = 0;
                    foreach($orderDetails as $detail) {
                        $lineTotal = 0;
                        $sizePrice = $detail->size ? $detail->size->price : 0;
                        $toppingTotal = 0;
                        if (!empty($detail->topping_id)) {
                            $toppingIds = array_filter(array_map('trim', explode(',', $detail->topping_id)));
                            if (!empty($toppingIds)) {
                                $toppings = \App\Models\Product_topping::whereIn('id', $toppingIds)->get();
                                foreach ($toppings as $tp) {
                                    $toppingTotal += $tp->price;
                                }
                            }
                        }
                        $lineTotal = ($sizePrice + $toppingTotal) * $detail->quantity;
                        if (isset($detail->total)) {
                            $lineTotal = $detail->total;
                        }
                        $productTotal += $lineTotal;
                    }
                @endphp
                
                <div class="total-row">
                    <span>Tiền sản phẩm:</span>
                    <span>{{ number_format($productTotal, 0, ',', '.') }} đ</span>
                </div>
                
                @if($order->coupon_total_discount > 0)
                
                <div class="total-row">
                    <span>Giảm giá coupon:</span>
                    <span>-{{ number_format($order->coupon_total_discount, 0, ',', '.') }} đ</span>
                </div>
                @endif
                
                @if($order->points_discount > 0)
                <div class="total-row">
                    <span>Giảm giá điểm:</span>
                    <span>-{{ number_format($order->points_discount, 0, ',', '.') }} đ</span>
                </div>
                @endif
                
                <div class="total-row">
                    <span>Phí vận chuyển:</span>
                    <span>{{ number_format($order->shipping_fee, 0, ',', '.') }} đ</span>
                </div>
                
                <div class="total-row final">
                    <span>Tổng cộng:</span>
                    <span>{{ number_format($order->total, 0, ',', '.') }} đ</span>
                </div>
            </div>
            
            @if($order->note)
            <div style="margin-top: 20px; padding: 15px; background-color: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
                <strong>📝 Ghi chú:</strong><br>
                {{ $order->note }}
            </div>
            @endif
        </div>
        
        <div class="footer">
            <p>Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi!</p>
            <p>Nếu có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.</p>
        </div>
    </div>
</body>
</html> 