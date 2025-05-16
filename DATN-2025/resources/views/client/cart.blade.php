@extends('layout')
@section('main')
<div class="container mt-5">
  <h2>🛒 Giỏ hàng của bạn</h2>
  @php $cart = session('cart', []); @endphp
  @if(count($cart))
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Sản phẩm</th>
          <th>Size</th>
          <th>Topping</th>
          <th>Số lượng</th>
          <th>Thành tiền</th>
        </tr>
      </thead>
      <tbody>
        @foreach($cart as $item)
        <tr>
          <td>{{ $item['name'] }}</td>
          <td>{{ $item['size_id'] }}</td>
          <td>
            <ul>
              @foreach($item['toppings'] as $topping)
                <li>{{ $topping['name'] }} (+{{ number_format($topping['price']) }})</li>
              @endforeach
            </ul>
          </td>
          <td>{{ $item['quantity'] }}</td>
          <td>{{ number_format($item['price']) }} VND</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p>Giỏ hàng của bạn đang trống.</p>
  @endif
</div>
@endsection
