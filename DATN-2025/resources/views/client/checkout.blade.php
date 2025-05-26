@extends('layout')
@section('main')
@php
    use Illuminate\Support\Facades\Auth;
    $isLoggedIn = Auth::check();
@endphp
<section class="home-slider owl-carousel">

<div class="slider-item" style="background-image: url({{ asset('asset/images/bg_3.jpg') }});" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
  <div class="container">
    <div class="row slider-text justify-content-center align-items-center">

      <div class="col-md-7 col-sm-12 text-center ftco-animate">
          <h1 class="mb-3 mt-5 bread">Checkout</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home</a></span> <span>Checout</span></p>
      </div>

    </div>
  </div>
</div>
</section>

<section class="ftco-section">
<div class="container">
  <div class="row">
    <div class="col-xl-12 ftco-animate">
<div class="row mt-5 pt-3">
    <div class="col-md-12" style="display: block !important;">
        <div class="cart-detail cart-total ftco-bg-dark p-3 p-md-4">
            <h3 class="billing-heading mb-4">Cart Summary</h3>
            <div class="table-responsive">
                <table class="table" style="white-space: nowrap;">
                    <thead class="thead-primary">
                        <tr class="text-center">
                            <th>Sản phẩm</th>
                            <th>Size</th>
                            <th>Topping</th>
                            <th>Số lượng</th>
                            <th>Tổng giá sản phâm</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($isLoggedIn)
                            @foreach($items as $item)
                                @php
                                    $product = $item->product;
                                    $size = \App\Models\Size::find($item->size_id);
                                    $toppingIds = array_filter(array_map('trim', explode(',', $item->topping_id)));
                                    $toppings = \App\Models\Product_topping::where('product_id', $item->product_id)
                                        ->whereIn('id', $toppingIds)
                                        ->get();
                                    $unitPrice = $product->price + ($size->price ?? 0) + $toppings->sum('price');
                                    $total = $unitPrice * $item->quantity;
                                    $toppingNamesArr = $toppings->pluck('topping')->toArray();
                                @endphp
                                <tr class="text-center">
                                    <td class="align-middle"><strong>{{ $product->name }}  </strong></td>
                                    <td>{{ $size->size ?? 'Không rõ' }}</td>
                                    <td class="align-middle">{!! $toppingNamesArr ? implode('<br>', $toppingNamesArr) : '' !!}</td>
                                    <td class="align-middle">{{ $item->quantity }}</td>
                                    <td class="align-middle">{{ number_format($total) }} VND</td>
                                </tr>
                            @endforeach
                        @else
                            @foreach($cart as $item)
                                @php
                                    $toppingNamesArr = !empty($item['topping_names']) ? $item['topping_names'] : [];
                                @endphp
                                <tr class="text-center">
                                    <td class="align-middle"><strong>{{ $item['name'] }}</strong></td>
                                    <td class="align-middle">{{ $item['size_name'] ?? 'Không rõ' }}</td>
                                    <td class="align-middle">{!! $toppingNamesArr ? implode('<br>', $toppingNamesArr) : '' !!}</td>
                                    <td class="align-middle">{{ $item['quantity'] }}</td>
                                    @php
                                    $basePrice = $item['price'] ?? 0; // Giá đã bao gồm size
                                    $toppingPrices = $item['topping_price'] ?? []; // Đổi từ 'topping_prices' → 'topping_price'
                                    $toppingTotal = is_array($toppingPrices) ? array_sum($toppingPrices) : 0;
                                    $quantity = $item['quantity'] ?? 1;
                                    $total = ($basePrice + $toppingTotal) * $quantity;
                                @endphp
                                <td class="align-middle">{{ number_format($total) }} VND</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><br><br>
                  <form action="#" class="billing-form ftco-bg-dark p-3 p-md-5">
                      <h3 class="mb-4 billing-heading">Billing Details</h3>
            <div class="row align-items-end">
                <div class="col-md-6">
              <div class="form-group">
                  <label for="firstname">Firt Name</label>
                <input type="text" class="form-control" placeholder="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label for="lastname">Last Name</label>
                <input type="text" class="form-control" placeholder="">
              </div>
          </div>
          <div class="w-100"></div>
              <div class="col-md-12">
                  <div class="form-group">
                      <label for="country">State / Country</label>
                      <div class="select-wrap">
                    <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                    <select name="" id="" class="form-control">
                        <option value="">France</option>
                      <option value="">Italy</option>
                      <option value="">Philippines</option>
                      <option value="">South Korea</option>
                      <option value="">Hongkong</option>
                      <option value="">Japan</option>
                    </select>
                  </div>
                  </div>
              </div>
              <div class="w-100"></div>
              <div class="col-md-6">
                  <div class="form-group">
                  <label for="streetaddress">Street Address</label>
                <input type="text" class="form-control" placeholder="House number and street name">
              </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                <input type="text" class="form-control" placeholder="Appartment, suite, unit etc: (optional)">
              </div>
              </div>
              <div class="w-100"></div>
              <div class="col-md-6">
                  <div class="form-group">
                  <label for="towncity">Town / City</label>
                <input type="text" class="form-control" placeholder="">
              </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label for="postcodezip">Postcode / ZIP *</label>
                <input type="text" class="form-control" placeholder="">
              </div>
              </div>
              <div class="w-100"></div>
              <div class="col-md-6">
              <div class="form-group">
                  <label for="phone">Phone</label>
                <input type="text" class="form-control" placeholder="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label for="emailaddress">Email Address</label>
                <input type="text" class="form-control" placeholder="">
              </div>
          </div>
          <div class="w-100"></div>
          <div class="col-md-12">
              <div class="form-group mt-4">
          <div class="radio">
              <label class="mr-3"><input type="radio" name="optradio"> Create an Account? </label>
              <label><input type="radio" name="optradio"> Ship to different address</label>
          </div>
          </div>
          </div>
          </div>
        </form>
        <br><br>

    <div class="col-md-6">
                <div class="cart-detail ftco-bg-dark p-3 p-md-4">
                    <h3 class="billing-heading mb-4">Payment Method</h3>
                              <div class="form-group">
                                  <div class="col-md-12">
                                      <div class="radio">
                                         <label><input type="radio" name="optradio" class="mr-2"> Direct Bank Tranfer</label>
                                      </div>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <div class="col-md-12">
                                      <div class="radio">
                                         <label><input type="radio" name="optradio" class="mr-2"> Check Payment</label>
                                      </div>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <div class="col-md-12">
                                      <div class="radio">
                                         <label><input type="radio" name="optradio" class="mr-2"> Paypal</label>
                                      </div>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <div class="col-md-12">
                                      <div class="checkbox">
                                         <label><input type="checkbox" value="" class="mr-2"> I have read and accept the terms and conditions</label>
                                      </div>
                                  </div>
                              </div>
                              <p><a href="#"class="btn btn-primary py-3 px-4">Place an order</a></p>
                        </div>
                </div>
          </div>
  </div>
</div>
</section> 
@endsection
