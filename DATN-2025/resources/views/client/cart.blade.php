@extends('layout')
@section('main')
<section class="ftco-section ftco-cart">
  <div class="container">
    <div class="row">
      <div class="col-md-12 ftco-animate">
        <div class="cart-list">
          @php $cart = session('cart', []); @endphp
          @if(count($cart))
          <form action="{{ route('cart.update') }}" method="POST">
            @csrf
            <table class="table">
              <thead class="thead-primary">
                <tr class="text-center">
                  <th>Xóa sản phẩm</th>
                  <th>Ảnh</th>
                  <th>Tên (Topping, Size)</th>
                  <th>Giá</th>
                  <th>Số lượng</th>
                  <th>Tổng</th>
                </tr>
              </thead>
              <tbody>
                @foreach($cart as $key => $item)
                <tr class="text-center">
                  <td class="product-remove">
                    <a onclick="return confirm('Bạn có chắc muốn xóa sản phẩm không ???')" href="{{ route('cart.remove', ['key' => $key]) }}"><span class="icon-close"></span></a>
                  </td>

                  <td class="image-prod">
                    <div class="img" style="background-image:url('{{ asset('storage/uploads/' . $item['image']) }}');"></div>
                  </td>

                  <td class="product-name">
                    <h3>{{ $item['name'] }}</h3>
                    <p>Size: {{ $item['size_id'] }}</p>
                    @if(count($item['toppings']))
                    <p>
                      Topping:
                      <ul class="list-unstyled">
                        @foreach($item['toppings'] as $topping)
                        <li>{{ $topping['name'] }} (+{{ number_format($topping['price']) }} VND)</li>
                        @endforeach
                      </ul>
                    </p>
                    @endif
                  </td>

                  <td class="price">{{ number_format($item['price'] / $item['quantity']) }} VND</td>

                  <td class="quantity">
                    <div class="input-group mb-3">
                      <button type="submit" name="decrease" value="{{ $key }}" class="btn btn-outline-secondary">-</button>
                      <input type="text" name="quantities[{{ $key }}]" class="form-control text-center" value="{{ $item['quantity'] }}" readonly>
                      <button type="submit" name="increase" value="{{ $key }}" class="btn btn-outline-secondary">+</button>
                    </div>
                  </td>

                  <td class="total">{{ number_format($item['price']) }} VND</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </form>
          @else
          <p class="text-center">Giỏ hàng của bạn đang trống.</p>
          @endif
        </div>
      </div>
    </div>

    @if(count($cart))
    <div class="row justify-content-end">
      <div class="col col-lg-3 col-md-6 mt-5 cart-wrap ftco-animate">
        <div class="cart-total mb-3">
          <h3>Tổng giỏ hàng</h3>
          @php
            $subtotal = collect($cart)->sum('price');
          @endphp
          <p class="d-flex">
            <span>Tạm tính</span>
            <span>{{ number_format($subtotal) }} VND</span>
          </p>
          <p class="d-flex">
            <span>Giao hàng</span>
            <span>Miễn phí</span>
          </p>
          <p class="d-flex">
            <span>Giảm giá</span>
            <span>0 VND</span>
          </p>
          <hr>
          <p class="d-flex total-price">
            <span>Tổng cộng</span>
            <span>{{ number_format($subtotal) }} VND</span>
          </p>
        </div>
        <p class="text-center">
          <a href="" class="btn btn-primary py-3 px-4">Thanh toán</a>
        </p>
      </div>
    </div>
    @endif
  </div>
</section>
@endsection
