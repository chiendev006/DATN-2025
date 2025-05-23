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
                  $toppings = \App\Models\Topping::whereIn('id', explode(',', $item->topping_id))->get();
                  $size = \App\Models\Size::find($item->size_id);
                  $unitPrice = $product->price + ($size->price ?? 0) + $toppings->sum('price');
                  $total = $unitPrice * $item->quantity;
                @endphp
                <tr class="text-center">
                 <td><a href="{{ route('cart.remove', $item->id) }}" onclick="return confirm('Xóa sản phẩm?')">X</a></td>
                  <td><img src="{{ asset('storage/uploads/' . $product->image) }}" width="80"></td>
                  <td>
                    <strong>{{ $product->name }}</strong><br>
                  </td>
                  <td>{{ $size->size ?? 'Không rõ' }}</td>
                  <td>{{ $size->price ?? 'Không rõ' }} VND</td>
                 <td>
                        @if($toppings->count())
                            <ul class="list-unstyled mb-0">
                                @foreach($toppings as $top)
                                    <li>{{ $top->name }}</li>
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
                  <td>
                    <strong>{{ $item['name'] }}</strong><br>
                    Size: {{ $item['size_name'] ?? 'Không rõ' }}<br>
                  </td>
                  <td>{{ number_format($item['size_price'] ?? 0) }} VND</td>
                   <td>
                    @if(!empty($item['topping_names']))
                        @foreach($item['topping_names'] as $i => $name)
                            {{ $name }} <br>
                        @endforeach
                    @else
                        Không có
                    @endif
                  </td> <td>
                    @if(!empty($item['topping_names']))
                        @foreach($item['topping_names'] as $i => $name)
                           (+{{ number_format($item['topping_price'][$i] ?? 0) }} VND)<br>
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

    @php
      $subtotal = $isLoggedIn
        ? collect($items ?? [])->sum(fn($i) => ($i->product->price + optional(\App\Models\Size::find($i->size_id))->price + \App\Models\Topping::whereIn('id', explode(',', $i->topping_id))->sum('price')) * $i->quantity)
        : collect($cart)->sum('price');
    @endphp

    @if($subtotal > 0)
    <div class="row justify-content-end mt-5">
      <div class="col-lg-4">
        <div class="cart-total p-4 border">
          <h3>Tổng giỏ hàng</h3>
          <p class="d-flex justify-content-between"><span>Tạm tính</span><span>{{ number_format($subtotal) }} VND</span></p>
          <p class="d-flex justify-content-between"><span>Giao hàng</span><span>Miễn phí</span></p>
          <p class="d-flex justify-content-between"><span>Giảm giá</span><span>0 VND</span></p>
          <hr>
          <p class="d-flex justify-content-between font-weight-bold"><span>Tổng cộng</span><span>{{ number_format($subtotal) }} VND</span></p>
          <div class="text-center mt-3">
            <a href="#" class="btn btn-primary">Thanh toán</a>
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>
</section>
@endsection
