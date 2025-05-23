@extends('layout')
@section('main')
@php
    use Illuminate\Support\Facades\Auth;
    $isLoggedIn = Auth::check();
@endphp
<section class="home-slider owl-carousel">

<div class="slider-item" style="background-image: url(images/bg_3.jpg);" data-stellar-background-ratio="0.5">
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
    <div class="col-xl-9 ftco-animate">
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
{{-- Thay đổi ở dòng này: Bỏ class d-flex và thêm style display: block !important; --}}
<div class="row mt-5 pt-3">
    <div class="col-md-12" style="display: block !important;">
        <div class="cart-detail cart-total ftco-bg-dark p-3 p-md-4">
            <h3 class="billing-heading mb-4">Cart Summary</h3>
            <div class="table-responsive">
                <table class="table" style="white-space: nowrap;">
                    <thead class="thead-primary">
                        <tr class="text-center">
                            <th>Sản phẩm</th>
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
                                    <td class="align-middle"><strong>{{ $product->name }}  Size: {{ $size->size ?? 'Không rõ' }}</strong></td>
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
                                    <td class="align-middle">{{ number_format($item['price']) }} VND</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
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
    <div class="col-xl-3 sidebar ftco-animate">
      <div class="sidebar-box">
        <form action="#" class="search-form">
          <div class="form-group">
              <div class="icon">
                <span class="icon-search"></span>
            </div>
            <input type="text" class="form-control" placeholder="Search...">
          </div>
        </form>
      </div>
      <div class="sidebar-box ftco-animate">
        <div class="categories">
          <h3>Categories</h3>
          <li><a href="#">Tour <span>(12)</span></a></li>
          <li><a href="#">Hotel <span>(22)</span></a></li>
          <li><a href="#">Coffee <span>(37)</span></a></li>
          <li><a href="#">Drinks <span>(42)</span></a></li>
          <li><a href="#">Foods <span>(14)</span></a></li>
          <li><a href="#">Travel <span>(140)</span></a></li>
        </div>
      </div>

      <div class="sidebar-box ftco-animate">
        <h3>Recent Blog</h3>
        <div class="block-21 mb-4 d-flex">
          <a class="blog-img mr-4" style="background-image: url(images/image_1.jpg);"></a>
          <div class="text">
            <h3 class="heading"><a href="#">Even the all-powerful Pointing has no control about the blind texts</a></h3>
            <div class="meta">
              <div><a href="#"><span class="icon-calendar"></span> July 12, 2018</a></div>
              <div><a href="#"><span class="icon-person"></span> Admin</a></div>
              <div><a href="#"><span class="icon-chat"></span> 19</a></div>
            </div>
          </div>
        </div>
        <div class="block-21 mb-4 d-flex">
          <a class="blog-img mr-4" style="background-image: url(images/image_2.jpg);"></a>
          <div class="text">
            <h3 class="heading"><a href="#">Even the all-powerful Pointing has no control about the blind texts</a></h3>
            <div class="meta">
              <div><a href="#"><span class="icon-calendar"></span> July 12, 2018</a></div>
              <div><a href="#"><span class="icon-person"></span> Admin</a></div>
              <div><a href="#"><span class="icon-chat"></span> 19</a></div>
            </div>
          </div>
        </div>
        <div class="block-21 mb-4 d-flex">
          <a class="blog-img mr-4" style="background-image: url(images/image_3.jpg);"></a>
          <div class="text">
            <h3 class="heading"><a href="#">Even the all-powerful Pointing has no control about the blind texts</a></h3>
            <div class="meta">
              <div><a href="#"><span class="icon-calendar"></span> July 12, 2018</a></div>
              <div><a href="#"><span class="icon-person"></span> Admin</a></div>
              <div><a href="#"><span class="icon-chat"></span> 19</a></div>
            </div>
          </div>
        </div>
      </div>

      <div class="sidebar-box ftco-animate">
        <h3>Tag Cloud</h3>
        <div class="tagcloud">
          <a href="#" class="tag-cloud-link">dish</a>
          <a href="#" class="tag-cloud-link">menu</a>
          <a href="#" class="tag-cloud-link">food</a>
          <a href="#" class="tag-cloud-link">sweet</a>
          <a href="#" class="tag-cloud-link">tasty</a>
          <a href="#" class="tag-cloud-link">delicious</a>
          <a href="#" class="tag-cloud-link">desserts</a>
          <a href="#" class="tag-cloud-link">drinks</a>
        </div>
      </div>

      <div class="sidebar-box ftco-animate">
        <h3>Paragraph</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus itaque, autem necessitatibus voluptate quod mollitia delectus aut, sunt placeat nam vero culpa sapiente consectetur similique, inventore eos fugit cupiditate numquam!</p>
      </div>
    </div>

  </div>
</div>
</section> <!-- .section -->

@endsection
