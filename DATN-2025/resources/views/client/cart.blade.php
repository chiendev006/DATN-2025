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
                  <th class="w-[80px]">Số lượng</th>
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
                  $rowKey = $item->product_id . '-' . $item->size_id . '-' . implode(',', $toppingIds);
                @endphp
                <tr class="text-center" data-key="{{ $rowKey }}">
                  <td>
                  <button class="remove-item btn btn-danger btn-sm" data-key="{{ $rowKey }}" style="background-color: transparent !important; border: none; color: inherit; padding: 0;">Xóa</button>
                  </td>
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
                        <button class="btn btn-outline-secondary decrement-btn" type="button">-</button>
                        <input readonly type="number" name="quantity" value="{{ $item->quantity }}"
                            class="form-control text-center quantity-input" min="1"
                            data-key="{{ $rowKey }}" data-product_id="{{ $item->product_id }}"
                            data-size_id="{{ $item->size_id }}"
                            data-topping_ids="{{ implode(',', $toppingIds) }}">
                        <button class="btn btn-outline-secondary increment-btn" type="button">+</button>
                    </div>
                </td>

                  <td class="line-total">{{ number_format($total) }} VND</td>
                </tr>
                @endforeach
              @else
                @foreach($cart as $key => $item)
                @php
                  $unitPrice = ($item['size_price'] ?? 0) + array_sum($item['topping_prices']);
                  $total = $unitPrice * $item['quantity'];
                @endphp
                <tr class="text-center" data-key="{{ $key }}">
                  <td>
                    <button class="remove-item btn btn-danger btn-sm" data-key="{{ $key }}">Xóa</button>
                  </td>
                  <td><img src="{{ asset('storage/uploads/' . $item['image']) }}" width="80"></td>
                  <td><strong>{{ $item['name'] }}</strong></td>
                  <td>
                    {{ $item['size_name'] ?? 'Không rõ' }}
                  </td>
                  <td>
                    @if(!empty($item['topping_names']))
                      @foreach($item['topping_names'] as $index => $topping)
                        {{ $topping }}<small class="text-muted"> (+{{ number_format($item['topping_prices'][$index]) }} VND)</small><br>
                      @endforeach
                    @else
                      Không có
                    @endif
                  </td>
                  <td>
                    <div class="input-group">
                      <input type="number" name="quantity" value="{{ $item['quantity'] }}" class="form-control text-center quantity-input" min="1"
                        data-key="{{ $key }}" data-product_id="{{ $item['sanpham_id'] }}" data-size_id="{{ $item['size_id'] }}" data-topping_ids="{{ implode(',', $item['topping_ids']) }}">
                    </div>
                  </td>
                  <td class="line-total">{{ number_format($total) }} VND</td>
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
            $unitPrice = ($item['size_price'] ?? 0) + array_sum($item['topping_prices']);
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
          <p>Tạm tính: <span id="cart-subtotal">{{ number_format($subtotal, 0, ',', '.') }} đ</span></p>

          @foreach($coupons as $c)
            <p>Mã giảm giá ({{ $c['code'] }}): -{{ $c['type'] === 'percent' ? number_format($subtotal * $c['discount'] / 100, 0, ',', '.') : number_format($c['discount'], 0, ',', '.') }} đ</p>
          @endforeach

          <hr>
          <p class="d-flex justify-content-between font-weight-bold"><span>Tổng cộng</span><span id="cart-total">{{ number_format($total, 0, ',', '.') }} đ</span></p>

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

  // Xử lý xóa sản phẩm bằng AJAX POST
  document.querySelectorAll('.remove-item').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      if (!confirm('Xóa sản phẩm?')) return;
      const key = this.dataset.key;
      fetch("{{ url('/cart/remove') }}/" + key, {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": csrfToken
        }
      })
      .then(res => {
        if (res.ok) return res.json();
        throw new Error('Network response was not ok');
      })
      .then(data => {
        if (data.success) {
          // Ẩn dòng sản phẩm vừa xóa
          document.querySelectorAll(`tr[data-key='${data.key}']`).forEach(row => row.remove());
          // Cập nhật tổng tiền nếu có
          if (data.subtotal !== undefined) {
            document.getElementById('cart-subtotal').textContent = Number(data.subtotal).toLocaleString('vi-VN') + ' đ';
          }
          if (data.total !== undefined) {
            document.getElementById('cart-total').textContent = Number(data.total).toLocaleString('vi-VN') + ' đ';
          }
        } else {
          alert(data.message || "Không thể xóa sản phẩm.");
        }
      })
      .catch(() => alert("Lỗi khi xóa sản phẩm."));
    });
  });

  // Xử lý cập nhật số lượng bằng AJAX
  document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function () {
      const key = this.dataset.key;
      const quantity = this.value;
      const product_id = this.dataset.product_id;
      const size_id = this.dataset.size_id;
      const topping_ids = this.dataset.topping_ids;
      fetch("{{ url('/cart/update') }}", {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": csrfToken,
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          key: key,
          quantity: quantity,
          product_id: product_id,
          size_id: size_id,
          topping_ids: topping_ids ? topping_ids.split(',') : []
        })
      })
      .then(res => {
        if (res.ok) return res.json();
        throw new Error('Network response was not ok');
      })
      .then(data => {
        if (data.success) {
          // Cập nhật lại số lượng nếu backend trả về (phòng trường hợp backend chỉnh lại)
          this.value = data.quantity;
          // Cập nhật lại thành tiền dòng
          const row = this.closest('tr');
          if (row && row.querySelector('.line-total')) {
            row.querySelector('.line-total').textContent = Number(data.line_total).toLocaleString('vi-VN') + ' VND';
          }
          // Cập nhật tổng tiền
          if (data.subtotal !== undefined) {
            document.getElementById('cart-subtotal').textContent = Number(data.subtotal).toLocaleString('vi-VN') + ' đ';
          }
          if (data.total !== undefined) {
            document.getElementById('cart-total').textContent = Number(data.total).toLocaleString('vi-VN') + ' đ';
          }
        } else {
          alert(data.message || "Không thể cập nhật số lượng.");
        }
      })
      .catch(() => alert("Lỗi khi cập nhật số lượng."));
    });
  });
});


document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.increment-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const input = this.previousElementSibling;
        input.value = parseInt(input.value) + 1;
        input.dispatchEvent(new Event('change')); // kích hoạt sự kiện nếu bạn lắng nghe
      });
    });

    document.querySelectorAll('.decrement-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const input = this.nextElementSibling;
        if (parseInt(input.value) > parseInt(input.min)) {
          input.value = parseInt(input.value) - 1;
          input.dispatchEvent(new Event('change'));
        }
      });
    });
  });

</script>
@endsection
