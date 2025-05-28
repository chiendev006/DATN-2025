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

    </style>
  </head>




  <body>
    <!-- Page pre loader -->
    <div id="pre-loader">
      <div class="loader-holder">
        <div class="frame">
          <img src="{{ url('asset') }}/{{ url('asset') }}/images/Preloader.gif" alt="Despına" />
        </div>
      </div>
    </div>

    <!-- Start Wrapper Part -->

    <div class="wrapper">
      <!-- Start Header Part -->

      <header>
        <div class="header-part header-reduce sticky">
          <div class="header-nav">
            <div class="container">
              <div class="header-nav-inside">
                <div class="logo">
                  <a href="index.html"
                    ><span>Despına</span><small>1991</small></a
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
                      <li class="has-child">
                        <a href="/blog">Blog</a>
                        <ul class="drop-nav">
                          <li><a href="/blog">Blog List</a></li>
                          <li><a href="blog_2col.html">Blog 2 Columns</a></li>
                          <li><a href="blog_full.html">Blog Full Width</a></li>
                          <li>
                            <a href="blog_left_sidebar.html"
                              >Blog Left Sidebar</a
                            >
                          </li>
                          <li>
                            <a href="blog_right_sidebar.html"
                              >Blog Right Sidebar</a
                            >
                          </li>
                          <li><a href="blog_single.html">Blog Single</a></li>
                        </ul>
                      </li>
                      <li class="has-child">
                        <a href="/contact">Contact</a>
                        <ul class="drop-nav">
                          <li><a href="contact_1.html">Contact 1</a></li>
                          <li><a href="contact_2.html">Contact 2</a></li>
                        </ul>
                      </li>
                       <li class="has-child">
                        <a href="/login">Login</a>
                      </li>
                    </ul>
                  </div>
                  <div class="cell-part">
                    <a href="tel:123-456-7890"
                      ><span class="icon-phone fontello"></span>123-456-7890</a
                    >
                  </div>
                  <div class="cart animated">
                    <span class="icon-basket fontello"></span
                    ><span>2 items - $ 10.89</span>
                    <div class="cart-wrap">
                      <div class="cart-blog">
                        <div class="cart-item">
                          <div class="cart-item-left">
                            <img src="{{ url('asset') }}/images/img21.png" alt="" />
                          </div>
                          <div class="cart-item-right">
                            <h6>Caramel Chesse Cake</h6>
                            <span>$ 14.00</span>
                          </div>
                          <span class="delete-icon"></span>
                        </div>
                        <div class="cart-item">
                          <div class="cart-item-left">
                            <img src="{{ url('asset') }}/images/img21.png" alt="" />
                          </div>
                          <div class="cart-item-right">
                            <h6>Caramel Chesse Cake</h6>
                            <span>$ 14.00</span>
                          </div>
                          <span class="delete-icon"></span>
                        </div>
                        <div class="subtotal">
                          <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                              <h6>Subtotal :</h6>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                              <span>$ 140.00</span>
                            </div>
                          </div>
                        </div>
                        <div class="cart-btn">
                          <a href="{{ route('cart.index') }}" class="button-default view"
                            >VIEW ALL</a
                          >
                          <a
                            href="shop_checkout.html"
                            class="button-default checkout"
                            >CHECK OUT</a
                          >
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
            data-background="{{ url('asset') }}/images/footer-bg.jpg"
          >
            <div class="container">
              <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="logo">
                    <a href="index.html"
                      ><span>Despına</span><small>1991</small></a
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
                    href="https://www.youtube.com/watch?v=uZ0aQMXkiV4"
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

    <a href="#" class="top-arrow"></a>

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
  </body>
  <!-- jQuery (bắt buộc) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Summernote CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">

<!-- Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

  
</html>
