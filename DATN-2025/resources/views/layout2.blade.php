<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Despina : Coffee, Cake & Bakery HTML Template</title>

    <link rel="shortcut icon" href="{{ url('asset') }}/images/favicon.png" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link
      href="https://fonts.googleapis.com/css?family=Pacifico|Quicksand:300,400,500,700|Source+Sans+Pro:400,600,700"
      rel="stylesheet"
    />
    <link href="{{ url('asset') }}/css/icon-plugin/font-awesome.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/icon-plugin/fontello.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/revolution-plugin/extralayers.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/revolution-plugin/settings.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/bootstrap-plugin/bootstrap.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/bootstrap-plugin/bootstrap-slider.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/animation/animate.min.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/owl-carousel/owl.carousel.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/owl-carousel/owl.theme.default.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/light-box/simplelightbox.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/light-box/magnific-popup.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/form-field/jquery.formstyler.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/Slick-slider/slick-theme.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/theme.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/responsive.css" rel="stylesheet" />
  <!-- Bootstrap Slider CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.2/css/bootstrap-slider.min.css">


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
@vite(['resources/css/app.css', 'resources/js/app.js'])

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
       @php
      use Illuminate\Support\Facades\Auth;

      $cartCount = 0;

      if (Auth::check()) {
          $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
          if ($cart) {
              $cartCount = \App\Models\Cartdetail::where('cart_id', $cart->id)->sum('quantity');
          }
      } else {
          $sessionCart = session('cart', []);
          $cartCount = collect($sessionCart)->sum('quantity');
      }
    @endphp
    <style>
      .search-input-wrapper {
    position: relative;
    width: 100%;
}

.search-input-wrapper input[type="text"] {
    width: 100%;
    padding: 10px 40px 10px 15px;
    border: 1px solid #ccc;
    border-radius: 100px;
    font-size: 14px;
}

.search-input-wrapper i.fa-search {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    cursor: pointer;
}

.ui-slider-horizontal {
    height: 4px;
    background: #e9ecef;
}

.ui-slider .ui-slider-handle {
    width: 16px;
    height: 16px;
    background: #c7a17a;
    border-radius: 50%;
    border: 2px solid #fff;
    cursor: pointer;
    margin-top: -6px;
}

.ui-slider .ui-slider-range {
    background: #c7a17a;
}

#price-range-label {
    font-size: 14px;
    color: #666;
    margin-bottom: 10px;
    display: inline-block;
}

.filter-btn {
    background: #c7a17a;
    color: #fff !important;
    padding: 8px 20px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    margin-top: 15px;
}

.filter-btn:hover {
    background: #b08b63;
    color: #fff !important;
    text-decoration: none;
}






.scrollable-cart {
    max-height: 300px; /* Adjust as needed based on your design */
    overflow-y: auto; /* Adds a vertical scrollbar when content exceeds max-height */
    padding-right: 15px; /* Add some padding to prevent scrollbar from overlapping content */
}

/* Optional: Style for the scrollbar itself (Webkit browsers) */
.scrollable-cart::-webkit-scrollbar {
    width: 8px;
}

.scrollable-cart::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 4px;
}

.scrollable-cart::-webkit-scrollbar-track {
    background-color: #f1f1f1;
}

.quantity-controls {
    display: flex;
    align-items: center;
    margin-top: 5px; /* Adjust spacing as needed */
}

.quantity-controls button {
    background-color: #f0f0f0;
    border: 1px solid #ddd;
    padding: 2px 8px;
    cursor: pointer;
    font-size: 14px;
    line-height: 1;
    border-radius: 3px;
    margin: 0 5px;
}

.quantity-controls input {
    width: 40px; /* Adjust width as needed */
    text-align: center;
    border: 1px solid #ddd;
    padding: 2px 0;
    font-size: 14px;
    border-radius: 3px;
}

.delete-icon {
    cursor: pointer;
    color: #f00; /* Red color for delete icon */
    font-size: 18px; /* Adjust size as needed */
    margin-left: auto; /* Pushes the icon to the right */
    position: absolute; /* Position absolutely if needed for alignment */
    right: 10px; /* Adjust based on your layout */
    top: 50%;
    transform: translateY(-50%);
}

.cart-item {
    position: relative; /* Needed for absolute positioning of delete icon */
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px dashed #eee;
}

.cart-item-right {
    flex-grow: 1; /* Allows the right section to take up available space */
    padding-right: 30px; /* Space for the delete icon */
}

    </style>
  </head>




  <body>
    <div id="app">
    <!-- Page pre loader -->
    <div id="pre-loader">
      <div class="loader-holder">
        <div class="frame">
          <img src="{{ url('asset') }}/images/Preloader.gif" alt="Despına" />
        </div>
      </div>
    </div>

    <!-- Start Wrapper Part -->

    <div class="wrapper">
      <!-- Start Header Part -->

      <header>
        <!-- Flash Message Display -->
        @if(session('message'))
        <div class="alert-container" style="position: fixed; top: 80px; right: 20px; z-index: 9999; max-width: 350px;">
          <div class="alert alert-warning alert-dismissible fade show" role="alert" style="background-color: #fff3cd; color: #856404; border-color: #ffeeba; padding: 12px 20px; border-radius: 4px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <strong>Thông báo!</strong> {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; top: 0; right: 0; padding: 12px 20px; background: none; border: none; cursor: pointer;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
        <script>
          // Auto close alert after 5 seconds
          setTimeout(function() {
            document.querySelector('.alert').classList.remove('show');
            setTimeout(function() {
              document.querySelector('.alert-container').remove();
            }, 500);
          }, 5000);

          // Close on click
          document.querySelector('.close').addEventListener('click', function() {
            document.querySelector('.alert').classList.remove('show');
            setTimeout(function() {
              document.querySelector('.alert-container').remove();
            }, 500);
          });
        </script>
        @endif

        <div class="header-part header-reduce sticky">
          <div class="header-nav">
            <div class="container">
              <div class="header-nav-inside">
                <div class="logo">
                  <a href="/"
                    ><span>mira</span><small>café</small></a
                  >
                </div>
                <div class="menu-top-part">
                  <div class="menu-nav-main">
                    <ul>
                      <li class="">
                        <a href="/">Home</a>
                      </li>
                      <li class="mega-menu">
                        <a href="/menu">Menu</a>
                        <ul class="drop-nav">
                          <li>
                            <div class="drop-mega-part">
                              <div class="row">
                                <div class="col-md-5 col-sm-12 col-xs-12">
                                  <span class="mega-title">MAIN MENU</span>
                                  <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                      <ul>
                                        <li>
                                          <a href="menu.html"
                                            >Ready Player One</a
                                          >
                                        </li>
                                        <li>
                                          <a href="menu_change.html"
                                            >Ernest Cline</a
                                          >
                                        </li>
                                        <li>
                                          <a href="menu_fixed.html"
                                            >Ender's Game</a
                                          >
                                        </li>
                                        <li>
                                          <a href="menu.html"
                                            >Orson Scott Card</a
                                          >
                                        </li>
                                        <li>
                                          <a href="menu_change.html"
                                            >Americam Gods</a
                                          >
                                        </li>
                                        <li>
                                          <a href="menu_fixed.html"
                                            >Neil Gaiman</a
                                          >
                                        </li>
                                        <li>
                                          <a href="menu.html"
                                            >The Great Gatsby</a
                                          >
                                        </li>
                                      </ul>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                      <ul>
                                        <li>
                                          <a href="menu.html"
                                            >Ready Player One</a
                                          >
                                        </li>
                                        <li>
                                          <a href="menu_change.html"
                                            >Ernest Cline</a
                                          >
                                        </li>
                                        <li>
                                          <a href="menu_fixed.html"
                                            >Ender's Game</a
                                          >
                                        </li>
                                        <li>
                                          <a href="menu.html"
                                            >Orson Scott Card</a
                                          >
                                        </li>
                                        <li>
                                          <a href="menu_change.html"
                                            >Americam Gods</a
                                          >
                                        </li>
                                        <li>
                                          <a href="menu_fixed.html"
                                            >Neil Gaiman</a
                                          >
                                        </li>
                                        <li>
                                          <a href="menu.html"
                                            >The Great Gatsby</a
                                          >
                                        </li>
                                      </ul>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-2 col-sm-12 col-xs-12">
                                  <span class="mega-title">IMAGE</span>
                                  <img src="{{ url('asset') }}/images/img6.png" alt="" />
                                </div>
                                <div class="col-md-5 col-sm-12 col-xs-12">
                                  <span class="mega-title">DESCRIPTION</span>
                                  <p>
                                    This column can contain whatever you like!
                                    Add text, shortcodes, or HTML.Various
                                    versions have evolved over the years,
                                    sometimes by accident, sometimes on
                                  </p>
                                  <div class="mega-position">
                                    <img src="{{ url('asset') }}/images/img7.png" alt="" />
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                        </ul>
                      </li>
                      <li class="has-child">
                        <a href="/shop">Shop</a>
                        <ul class="drop-nav">
                          <li><a href="/shop">Shop Page</a></li>
                          <li><a href="shop_single.html">Shop Single</a></li>
                          <li><a href="shop_cart.html">Shop Cart</a></li>
                          <li>
                            <a href="shop_checkout.html">Shop Checkout</a>
                          </li>
                          <li>
                            <a href="order_complete.html">Order Complete</a>
                          </li>
                          <li>
                            <a href="track_order.html">Track Your Order</a>
                          </li>
                          <li>
                            <a href="login_register.html">Login & Register</a>
                          </li>
                        </ul>
                      </li>
                      <li class="has-child">
                        <a href="/about">Pages</a>
                        <ul class="drop-nav">
                          <li><a href="/about">About Us</a></li>
                          <li class="drop-has-child">
                            <a href="service1.html">Services</a>
                            <ul class="drop-nav">
                              <li><a href="service1.html">Service 1</a></li>
                              <li><a href="service2.html">Service 2</a></li>
                            </ul>
                          </li>
                          <li class="drop-has-child">
                            <a href="gallery2.html">Gallery</a>
                            <ul class="drop-nav">
                              <li>
                                <a href="gallery2.html">Gallery 2 Columns</a>
                              </li>
                              <li>
                                <a href="gallery3.html">Gallery 3 Columns</a>
                              </li>
                              <li>
                                <a href="gallery4.html">Gallery 4 Columns</a>
                              </li>
                              <li>
                                <a href="gallery_masonry.html"
                                  >Gallery Masonry</a
                                >
                              </li>
                            </ul>
                          </li>
                          <li class="drop-has-child">
                            <a href="menu_fixed.html">Menu</a>
                            <ul class="drop-nav">
                              <li><a href="menu_fixed.html">Menu 1</a></li>
                              <li><a href="menu.html">Menu 2</a></li>
                              <li><a href="menu_change.html">Menu 3</a></li>
                            </ul>
                          </li>
                          <li class="drop-has-child">
                            <a href="contact_1.html">Contact</a>
                            <ul class="drop-nav">
                              <li><a href="contact_1.html">Contact 1</a></li>
                              <li><a href="contact_2.html">Contact 2</a></li>
                            </ul>
                          </li>
                          <li>
                            <a href="terms_condition.html">Terms & Condition</a>
                          </li>
                          <li><a href="faq.html">FAQ</a></li>
                          <li><a href="element.html">Elements</a></li>
                          <li><a href="404.html">404 Error</a></li>
                        </ul>
                      </li>
                      <li >
                        <a href="/blog">Blog</a>
                      </li>
                      <li class="has-child">
                        <a href="/contact">Contact</a>
                        <ul class="drop-nav">
                          <li><a href="contact_1.html">Contact 1</a></li>
                          <li><a href="contact_2.html">Contact 2</a></li>
                        </ul>
                      </li>
                     @auth
                          <li class="has-child">
                              <a href="#" class="nav-link dropdown-toggle" id="userDropdown">
                                  Xin chào, {{ Auth::user()->name }}
                              </a>
                              <ul class="drop-nav">
                                  <li>
                                      <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                          Đăng xuất
                                      </a>
                                      <a href="{{ route('client.myaccount') }}">Tài khoản của tôi</a>

                                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                          @csrf
                                      </form>
                                  </li>
                              </ul>
                          </li>
                      @else
                        <li class="has-child">
                            <a href="#" class="nav-link">Tài khoản</a>
                            <ul class="drop-nav">
                                <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                                <li><a href="{{ route('register') }}">Đăng ký</a></li>
                                <li><a href="{{ route('order.search') }}">Tra cứu đơn hàng</a></li>
                            </ul>
                        </li>
                    @endauth
                  </div>
                  <div class="cart animated">
                       <span class="icon-basket fontello"></span
                    ><span>{{ $cartCount }} sản phẩm - {{ number_format($subtotal, 0, ',', '.') }}₫</span>
                        <div class="cart-wrap">
                            <div class="cart-blog scrollable-cart">
                                @forelse ($items as $item)
                                    @php
                                        $productName = $item->product->name ?? $item->name;
                                        $itemId = isset($item->product)
                                            ? $item->id
                                            : $loop->index;
                                        $productImage = isset($item->product->image)
                                            ? asset('storage/uploads/' . $item->product->image)
                                            : (isset($item->image) ? asset('storage/uploads/' . $item->image) : asset('asset/images/img21.png'));
                                        $quantity = $item->quantity ?? 1;
                                        $price = 0;

                                        if (isset($item->product)) {
                                            $basePrice = $item->size->price ?? $item->product->price;
                                            $toppingIds = array_filter(explode(',', $item->topping_id ?? ''));
                                            $toppingPrice = $toppingIds ? \App\Models\Product_topping::whereIn('id', $toppingIds)->sum('price') : 0;
                                            $price = ($basePrice + $toppingPrice);
                                        } else {
                                            $basePrice = $item->size_price ?? 0;
                                            $toppingPrice = array_sum($item->topping_prices ?? []);
                                            $price = ($basePrice + $toppingPrice);
                                        }
                                        $totalItemPrice = $price * $quantity;
                                    @endphp

                                    <div class="cart-item" data-item-id="{{ $itemId }}" data-item-price-per-unit="{{ $price }}">
                                        <div class="cart-item-left">
                                            <img src="{{ $productImage }}" alt="" />
                                        </div>
                                        <div class="cart-item-right">
                                            <h6>{{ $productName }}</h6>
                                            <div class="quantity-controls">
                                                <button class="decrease-quantity" data-item-id="{{ $itemId }}">-</button>
                                                <input type="number" class="item-quantity" value="{{ $quantity }}" min="1" data-item-id="{{ $itemId }}" readonly>
                                                <button class="increase-quantity" data-item-id="{{ $itemId }}">+</button>
                                            </div>
                                            <span class="item-price">{{ number_format($totalItemPrice, 0, ',', '.') }}₫</span>
                                        </div>
                                        <i class="delete-icon fa fa-trash" data-item-id="{{ $itemId }}"></i>
                                    </div>
                                @empty
                                    <div class="cart-item">
                                        <p>Không có sản phẩm nào trong giỏ hàng.</p>
                                    </div>
                                @endforelse

                                <div class="subtotal">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h6>Tạm tính :</h6>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <span id="subtotal-amount">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="cart-btn">
                                    <a href="{{ route('cart.index') }}" class="button-default view">XEM GIỎ HÀNG</a>
                                    <a href="{{ route('checkout.index') }}" class="button-default checkout">THANH TOÁN</a>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="menu-icon">
                    <a href="#" class="hambarger">
                      <span class="bar-1"></span>
                      <span class="bar-2"></span>
                      <span class="bar-3"></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </header>
    @yield('main')
      <!-- End Main Part -->

      <!-- Start Footer Part -->

      <footer>
        <div class="footer-part">
          <div
            class="footer-inner-info Banner-Bg"
            data-background="{{ url('asset') }}/images/footer-bg.jpg">
            <div class="container">
              <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="logo">
                    <a href="/"
                      ><span>mira</span><small>café</small></a
                    >
                  </div>
                  <p>
                    We have a hankering for some really in good melt in a mouth
                    variety. Floury is the best choice to taste food and
                    dessert.
                  </p>
                  <ul class="footer-social">
                    <li>
                      <a href="#"
                        ><i class="fa fa-facebook" aria-hidden="true"></i
                      ></a>
                    </li>
                    <li>
                      <a href="#"
                        ><i class="fa fa-twitter" aria-hidden="true"></i
                      ></a>
                    </li>
                    <li>
                      <a href="#"
                        ><i class="fa fa-instagram" aria-hidden="true"></i
                      ></a>
                    </li>
                    <li>
                      <a href="#"
                        ><i class="fa fa-pinterest" aria-hidden="true"></i
                      ></a>
                    </li>
                    <li>
                      <a href="#"
                        ><i class="fa fa-dribbble" aria-hidden="true"></i
                      ></a>
                    </li>
                    <li>
                      <a href="#"
                        ><i class="fa fa-google" aria-hidden="true"></i
                      ></a>
                    </li>
                  </ul>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <h5>Contact</h5>
                  <p>
                    329 Queensberry Street, North Melbourne <br />
                    VIC 3051, Australia. <br />
                    <a href="tel:1234567890">123 456 7890</a> <br />
                    <a href="mailto:support@despina.com">support@despina.com</a>
                  </p>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <h5>Opening Hour</h5>
                  <p>
                    Monday - Friday: ........6am - 9pm <br />
                    Saturday - Sunday: ........6am - 10pm <br />
                    Close on special days
                  </p>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <h5>Despina Video</h5>
                  <a
                    href="https://www.youtube.com/watch?v=bnQ5BwLCH3U"
                    class="magnific-youtube"
                    ><img src="{{ url('asset') }}/images/video-bg.png" alt=""
                  /></a>
                </div>
              </div>
            </div>
          </div>
          <div class="copyright">
            <div class="container">
              <p>
                Copyright © 2017 Hire WordPress Developer. All rights reserved.
              </p>
            </div>
          </div>
        </div>
      </footer>

      <!-- End Footer Part -->
    </div>

    <!-- End Wrapper Part -->

    <!-- Back To Top Arrow -->



<!-- Elfsight AI Chatbot | Untitled AI Chatbot  -->
<script src="https://static.elfsight.com/platform/platform.js" async></script>

                <!-- luonghvpp03220@gmal.com -->
<!-- <div class="elfsight-app-36d5930f-53fe-4eb3-9cfb-7853aecba54c" data-elfsight-app-lazy></div> -->

                <!-- chubenai3@gmail.com -->
<div class="elfsight-app-784728d3-89d8-42e0-8e07-0b4b0235f735" data-elfsight-app-lazy></div>


    <script src="{{ url('asset') }}/js/jquery.min.js"></script>
    <script src="{{ url('asset') }}/js/bootstrap/bootstrap.min.js"></script>
    <script src="{{ url('asset') }}/js/bootstrap/bootstrap-slider.js"></script>
    <script src="{{ url('asset') }}/js/revolution-plugin/jquery.themepunch.plugins.min.js"></script>
    <script src="{{ url('asset') }}/js/revolution-plugin/jquery.themepunch.revolution.min.js"></script>
    <script src="{{ url('asset') }}/js/parallax-stellar/jquery.stellar.js"></script>
    <script src="{{ url('asset') }}/js/animation/wow.min.js"></script>
    <script src="{{ url('asset') }}/js/owl-carousel/owl.carousel.min.js"></script>
    <script src="{{ url('asset') }}/js/light-box/simple-lightbox.min.js"></script>
    <script src="{{ url('asset') }}/js/light-box/jquery.magnific-popup.min.js"></script>
    <script src="{{ url('asset') }}/js/scroll-bar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="{{ url('asset') }}/js/masonry/isotop.js"></script>
    <script src="{{ url('asset') }}/js/masonry/packery-mode.pkgd.min.js"></script>
    <script src="{{ url('asset') }}/js/form-field/jquery.formstyler.min.js"></script>
    <script src="{{ url('asset') }}/js/Slick-slider/slick.min.js"></script>
    <script src="{{ url('asset') }}/js/progress-circle/waterbubble.min.js"></script>
    <script src="{{ url('asset') }}/js/app.js"></script>
    <script src="{{ url('asset') }}/js/script.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <!-- Bootstrap Slider JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.2/bootstrap-slider.min.js"></script>
    @yield('scripts')
    </div>
  </body>
  <!-- jQuery (bắt buộc) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Summernote CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">

<!-- Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartBlog = document.querySelector('.cart-blog');
    const subtotalAmountSpan = document.getElementById('subtotal-amount');

    // Function to format currency in VND
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
    }

    // Function to update the subtotal displayed in the cart
    function updateCartSubtotal(newSubtotal) {
        subtotalAmountSpan.textContent = formatCurrency(newSubtotal);
        // Update header cart count and total
        const headerCartText = document.querySelector('.cart.animated > span:nth-child(2)');
        const cartItemCount = document.querySelectorAll('.cart-item').length - (document.querySelector('.cart-item p') ? 1 : 0); // Subtract 1 if empty cart message exists
        headerCartText.textContent = `${cartItemCount} sản phẩm - ${formatCurrency(newSubtotal)}`;
    }

    // Handle quantity changes and delete
    cartBlog.addEventListener('click', async function(event) {
        const target = event.target;

        if (target.classList.contains('increase-quantity') || target.classList.contains('decrease-quantity')) {
            const itemId = target.dataset.itemId;
            const cartItem = target.closest('.cart-item');
            const quantityInput = cartItem.querySelector('.item-quantity');
            const currentQuantity = parseInt(quantityInput.value);

            let newQuantity = currentQuantity;
            if (target.classList.contains('increase-quantity')) {
                newQuantity = currentQuantity + 1;
            } else {
                newQuantity = Math.max(1, currentQuantity - 1);
            }

            if (newQuantity !== currentQuantity) {
                try {
                    const response = await fetch('/cart/update-quantity', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            item_id: itemId,
                            quantity: newQuantity
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Update quantity input
                        quantityInput.value = newQuantity;

                        // Update item price
                        const pricePerUnit = parseFloat(cartItem.dataset.itemPricePerUnit);
                        const newItemPrice = pricePerUnit * newQuantity;
                        cartItem.querySelector('.item-price').textContent = formatCurrency(newItemPrice);

                        // Update subtotal
                        updateCartSubtotal(data.subtotal);
                    } else {
                        alert(data.message || 'Cập nhật số lượng thất bại');
                    }
                } catch (error) {
                    console.error('Error updating quantity:', error);
                    alert('Có lỗi xảy ra khi cập nhật số lượng.');
                }
            }
        } else if (target.classList.contains('delete-icon')) {
            const itemId = target.dataset.itemId;
            const cartItem = target.closest('.cart-item');

            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                try {
                    const response = await fetch('/cart/remove-item', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            item_id: itemId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        cartItem.remove();
                        updateCartSubtotal(data.subtotal);

                        // If cart is empty, show empty cart message
                        if (data.subtotal === 0) {
                            cartBlog.innerHTML = `
                                <div class="cart-item">
                                    <p>Không có sản phẩm nào trong giỏ hàng.</p>
                                </div>
                                <div class="subtotal">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h6>Tạm tính :</h6>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <span id="subtotal-amount">0₫</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="cart-btn">
                                    <a href="{{ route('cart.index') }}" class="button-default view">XEM GIỎ HÀNG</a>
                                    <a href="{{ route('checkout.index') }}" class="button-default checkout">THANH TOÁN</a>
                                </div>
                            `;
                        }
                    } else {
                        alert(data.message || 'Xóa sản phẩm thất bại');
                    }
                } catch (error) {
                    console.error('Error removing item:', error);
                    alert('Có lỗi xảy ra khi xóa sản phẩm.');
                }
            }
        }
    });
});
</script>
</html>


