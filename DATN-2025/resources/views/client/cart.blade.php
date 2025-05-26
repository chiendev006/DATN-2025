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
                  <th>Topping</th>
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
                  <td>
                    {{ $size->size ?? 'Không rõ' }}<br>
                  </td>
                  <td>
                    @if($toppings->count())
                      <ul class="list-unstyled mb-0">
                        @foreach($toppings as $top)
                          <li>{{ $top->topping }}<small class="text-muted"> (+{{ number_format($top->price) }} VND)</small></li>
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
                @php
                  $unitPrice = ($item['size_price'] ?? 0) + array_sum($item['topping_price']);
                  $total = $unitPrice * $item['quantity'];
                @endphp
                <tr class="text-center">
                  <td><a href="{{ route('cart.remove', $key) }}" onclick="return confirm('Xóa sản phẩm?')">X</a></td>
                  <td><img src="{{ asset('storage/uploads/' . $item['image']) }}" width="80"></td>
                  <td><strong>{{ $item['name'] }}</strong></td>
                  <td>
                    {{ $item['size_name'] ?? 'Không rõ' }}
                  </td>
                  <td>
                    @if(!empty($item['topping_names']))
                      @foreach($item['topping_names'] as $index => $topping)
                        {{ $topping }}<small class="text-muted"> (+{{ number_format($item['topping_price'][$index]) }} VND)</small><br>
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
                  <td>{{ number_format($total) }} VND</td>
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



    {{-- TỔNG TIỀN --}}
    @php
      $subtotal = $isLoggedIn
        ? collect($items ?? [])->sum(fn($i) =>
            ($i->product->price + optional(\App\Models\Size::find($i->size_id))->price + \App\Models\Product_topping::where('product_id', $i->product_id)->whereIn('id', explode(',', $i->topping_id))->sum('price')) * $i->quantity)
        : collect($cart)->sum(function($item) {
            $unitPrice = ($item['size_price'] ?? 0) + array_sum($item['topping_price']);
            return $unitPrice * $item['quantity'];
        });

      $coupons = session('coupons', []);
      $discount = 0;
      foreach ($coupons as $c) {
        $discount += ($c['type'] === 'percent') ? ($subtotal * $c['discount'] / 100) : $c['discount'];
      }
      $total = max(0, $subtotal - $discount);
    @endphp

    @if($subtotal > 0)
    <div class="row justify-content-end mt-4">
      <div class="col-lg-4">
        <div class="cart-total p-4 border">
          <h3>Tổng giỏ hàng</h3>
          <p>Tạm tính: {{ number_format($subtotal, 0, ',', '.') }} đ</p>

          @foreach($coupons as $c)
            <p>Mã giảm giá ({{ $c['code'] }}): -{{ $c['type'] === 'percent' ? number_format($subtotal * $c['discount'] / 100, 0, ',', '.') : number_format($c['discount'], 0, ',', '.') }} đ</p>
          @endforeach

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

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Gửi mã giảm giá

});
//

document.addEventListener("DOMContentLoaded", function () {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Xử lý tăng/giảm số lượng
  document.querySelectorAll('.quantity-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const type = this.dataset.type;
      const key = this.dataset.key;

      fetch("{{ route('cart.update') }}", {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": csrfToken,
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ [type]: key })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert("Không thể cập nhật giỏ hàng.");
        }
      })
      .catch(() => alert("Có lỗi khi cập nhật giỏ hàng."));
    });
  });

  // Xử lý xóa sản phẩm
  document.querySelectorAll('.remove-item').forEach(btn => {
    btn.addEventListener('click', function () {
      const key = this.dataset.key;

      fetch("{{ url('/cart/remove') }}/" + key, {
        method: "GET",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": csrfToken
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert("Không thể xóa sản phẩm.");
        }
      })
      .catch(() => alert("Lỗi khi xóa sản phẩm."));
    });
  });
});

</script>
@endsection
