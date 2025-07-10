<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật trạng thái đơn hàng</title>
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
        .status-update {
            background-color: #e3f2fd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #2196f3;
        }
        .status-change {
            font-size: 18px;
            font-weight: bold;
            color: #1976d2;
            margin-bottom: 10px;
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
        .status-message {
            margin-top: 15px;
            padding: 15px;
            border-radius: 8px;
            font-weight: 500;
        }
        .status-message.pending {
            background-color: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        .status-message.processing {
            background-color: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }
        .status-message.shipping {
            background-color: #cce5ff;
            color: #004085;
            border-left: 4px solid #007bff;
        }
        .status-message.completed {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .status-message.cancelled {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📦 Cập nhật trạng thái đơn hàng</h1>
        </div>
        
        <div class="content">
            <div class="status-update">
                <div class="status-change">
                    Đơn hàng #{{ $order->id }} đã được cập nhật trạng thái
                </div>
                <div style="margin-bottom: 10px;">
                    <strong>Từ:</strong> 
                    @if($oldStatus == 'pending')
                        <span class="status-badge status-pending">Chờ xử lý</span>
                    @elseif($oldStatus == 'processing')
                        <span class="status-badge status-processing">Đã xác nhận</span>
                    @elseif($oldStatus == 'shipping')
                        <span class="status-badge status-shipping">Đang giao</span>
                    @elseif($oldStatus == 'completed')
                        <span class="status-badge status-completed">Hoàn thành</span>
                    @elseif($oldStatus == 'cancelled')
                        <span class="status-badge status-cancelled">Đã hủy</span>
                    @endif
                </div>
                <div>
                    <strong>Thành:</strong> 
                    @if($newStatus == 'pending')
                        <span class="status-badge status-pending">Chờ xử lý</span>
                    @elseif($newStatus == 'processing')
                        <span class="status-badge status-processing">Đã xác nhận</span>
                    @elseif($newStatus == 'shipping')
                        <span class="status-badge status-shipping">Đang giao</span>
                    @elseif($newStatus == 'completed')
                        <span class="status-badge status-completed">Hoàn thành</span>
                    @elseif($newStatus == 'cancelled')
                        <span class="status-badge status-cancelled">Đã hủy</span>
                    @endif
                </div>
            </div>
            
            <div class="status-message {{ $newStatus }}">
                @if($newStatus == 'pending')
                    🕐 Đơn hàng của bạn đang chờ xử lý. Chúng tôi sẽ xác nhận sớm nhất có thể.
                @elseif($newStatus == 'processing')
                    ✅ Đơn hàng của bạn đã được xác nhận và đang được chuẩn bị.
                @elseif($newStatus == 'shipping')
                    🚚 Đơn hàng của bạn đang được giao. Vui lòng chuẩn bị nhận hàng.
                @elseif($newStatus == 'completed')
                    🎉 Đơn hàng của bạn đã được giao thành công! Cảm ơn bạn đã mua sắm.
                @elseif($newStatus == 'cancelled')
                    ❌ Đơn hàng của bạn đã bị hủy. Nếu có thắc mắc, vui lòng liên hệ với chúng tôi.
                @endif
            </div>
            
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
                    <span class="info-label">Ngày đặt hàng:</span>
                    <span class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
            
              <div class="products-section">
                <div class="section-title">📦 Chi tiết sản phẩm</div>
                
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
            <div class="total-section">
                <div class="section-title">💰 Tổng thanh toán</div>
                
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