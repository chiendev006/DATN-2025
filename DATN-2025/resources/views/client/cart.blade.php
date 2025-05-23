@extends('layout')
@section('main')
<section class="ftco-section ftco-cart">
  <div class="container">
    <div class="row">
      <div class="col-md-12 ftco-animate">
        <div class="cart-list">
          @php
            use Illuminate\Support\Facades\Auth;
            $cart = session('cart', []);
            $isLoggedIn = Auth::check();
          @endphp

          @if(($isLoggedIn && isset($items) && count($items)) || (!$isLoggedIn && count($cart)))
          <form action="{{ route('cart.update') }}" method="POST">
            @csrf
            <table class="table">
              <thead class="thead-primary">
                <tr class="text-center">
                  <th>Xóa</th>
                  <th>Ảnh</th>
                  <th>Sản phẩm</th>
                  <th>Size</th>
                  <th>Đơn giá</th>
                  <th>Topping</th>
                  <th>Đơn giá</th>
                  <th>Số lượng</th>
                  <th>Thành tiền</th>
                </tr>
              </thead>
              <tbody>
              @if($isLoggedIn)
                @foreach($items as $item)
                @php
                  $product = $item->product;
                  $toppingIds = array_filter(array_map('trim', explode(',', $item->topping_id)));
                  $toppings = \App\Models\Product_topping::where('product_id', $item->product_id)
                      ->whereIn('id', $toppingIds)
                      ->get();
                  $size = \App\Models\Size::find($item->size_id);
                  $unitPrice = $product->price + ($size->price ?? 0) + $toppings->sum('price');
                  $total = $unitPrice * $item->quantity;
                @endphp
                <tr class="text-center">
                  <td><a href="{{ route('cart.remove', $item->id) }}" onclick="return confirm('Xóa sản phẩm?')">X</a></td>
                  <td><img src="{{ asset('storage/uploads/' . $product->image) }}" width="80"></td>
                  <td><strong>{{ $product->name }}</strong></td>
                  <td>{{ $size->size ?? 'Không rõ' }}</td>
                  <td>{{ number_format($size->price ?? 0) }} VND</td>
                  <td>
                    @if($toppings->count())
                      <ul class="list-unstyled mb-0">
                        @foreach($toppings as $top)
                          <li>{{ $top->topping }}</li>
                        @endforeach
                      </ul>
                    @else
                      Không có
                    @endif
                  </td>
                  <td>
                    @if($toppings->count())
                      <ul class="list-unstyled mb-0">
                        @foreach($toppings as $top)
                          <li>+{{ number_format($top->price) }} VND</li>
                        @endforeach
                      </ul>
                    @else
                      Không có
                    @endif
                  </td>
                  <td>
                    <div class="input-group">
                      <input type="number" name="quantities[{{ $item->id }}]" value="{{ $item->quantity }}" class="form-control text-center" min="1" onchange="this.form.submit()">
                    </div>
                  </td>
                  <td>{{ number_format($total) }} VND</td>
                </tr>
                @endforeach
              @else
                @foreach($cart as $key => $item)
                <tr class="text-center">
                  <td><a href="{{ route('cart.remove', $key) }}" onclick="return confirm('Xóa sản phẩm?')">X</a></td>
                  <td><img src="{{ asset('storage/uploads/' . $item['image']) }}" width="80"></td>
                  <td><strong>{{ $item['name'] }}</strong></td>
                  <td>{{ $item['size_name'] ?? 'Không rõ' }}</td>
                  <td>{{ number_format($item['size_price'] ?? 0) }} VND</td>
                  <td>
                    @if(!empty($item['topping_names']))
                      @foreach($item['topping_names'] as $topping)
                        {{ $topping }} <br>
                      @endforeach
                    @else
                      Không có
                    @endif
                  </td>
                  <td>
                    @if(!empty($item['topping_price']))
                      @foreach($item['topping_price'] as $price)
                        (+{{ number_format($price) }} VND)<br>
                      @endforeach
                    @else
                      Không có
                    @endif
                  </td>
                  <td>
                    <div class="input-group">
                      <input type="number" name="quantities[{{ $key }}]" value="{{ $item['quantity'] }}" class="form-control text-center" min="1" onchange="this.form.submit()">
                    </div>
                  </td>
                  <td>{{ number_format($item['price']) }} VND</td>
                </tr>
                @endforeach
              @endif
              </tbody>
            </table>
          </form>
          @else
            <p class="text-center">Giỏ hàng trống.</p>
          @endif
        </div>
      </div>
    </div>

    {{-- FORM NHẬP MÃ GIẢM GIÁ --}}
   <div class="row justify-content-end">
  <div class="col-lg-4 mb-3">
    {{-- Form nhập mã --}}
    <form action="{{ route('cart.applyCoupon') }}" method="POST" class="d-flex mb-2">
      @csrf
      <input type="text" name="coupon_code" class="form-control mr-2" placeholder="Nhập mã giảm giá" required>
      <button type="submit" class="btn btn-secondary">Áp dụng</button>
    </form>

    {{-- Hiển thị tất cả mã đã áp dụng --}}
    @if(session('coupons'))
      @foreach(session('coupons') as $c)
        <form action="{{ route('cart.removeCoupon') }}" method="POST" class="d-flex mb-1">
          @csrf
          <input type="hidden" name="code" value="{{ $c['code'] }}">
          <input type="text" value="{{ $c['code'] }}" class="form-control mr-2" readonly>
          <button type="submit" class="btn btn-danger">Xóa</button>
        </form>
      @endforeach
    @endif

    {{-- Hiển thị thông báo --}}
    @if(session('coupon_error'))
      <small class="text-danger">{{ session('coupon_error') }}</small>
    @endif
    @if(session('coupon_success'))
      <small class="text-success">{{ session('coupon_success') }}</small>
    @endif
  </div>
</div>


    {{-- TỔNG TIỀN --}}
    @php
      $subtotal = $isLoggedIn
        ? collect($items ?? [])->sum(fn($i) =>
            ($i->product->price + optional(\App\Models\Size::find($i->size_id))->price + \App\Models\Product_topping::where('product_id', $i->product_id)->whereIn('id', explode(',', $i->topping_id))->sum('price')) * $i->quantity)
        : collect($cart)->sum('price');

      $coupon = session('coupon');
      $discount = 0;
      $total = $subtotal;

      if ($coupon) {
          $discount = $coupon['type'] === 'percent'
              ? $subtotal * ($coupon['discount'] / 100)
              : $coupon['discount'];
          $total = $subtotal - $discount;
      }
    @endphp

    @if($subtotal > 0)
    <div class="row justify-content-end mt-4">
      <div class="col-lg-4">
        <div class="cart-total p-4 border">
          <h3>Tổng giỏ hàng</h3>
          <p>Tạm tính: {{ number_format($subtotal, 0, ',', '.') }} đ</p>

          @if($coupon)
            <p>Mã giảm giá ({{ $coupon['code'] }}): -{{ number_format($discount, 0, ',', '.') }} đ</p>
          @endif

          <hr>
          <p class="d-flex justify-content-between font-weight-bold"><span>Tổng cộng</span><span>{{ number_format($total, 0, ',', '.') }} đ</span></p>

          <div class="text-center mt-3">
            <a href="{{ route('checkout.index') }}" class="btn btn-primary">Thanh toán</a>
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>
</section>
@endsection
