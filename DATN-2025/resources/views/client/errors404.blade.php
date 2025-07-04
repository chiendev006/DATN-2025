
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mira Café</title>

    @vite(['resources/css/client.css', 'resources/js/client-app.js','resources/js/admin-app.js'])


    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @php
        use Illuminate\Support\Facades\Auth;
        use App\Models\Cart;
        use App\Models\Cartdetail;
        use App\Models\User;

        $currentUserId = Auth::check() ? Auth::id() : null;
        $cartCount = 0;

        if ($currentUserId) {
            $cart = Cart::where('user_id', $currentUserId)->first();
            if ($cart) {
                $cartCount = Cartdetail::where('cart_id', $cart->id)->sum('quantity');
            }
        } else {
            $sessionCart = session('cart', []);
            $cartCount = collect($sessionCart)->sum('quantity');
        }

        // Tìm ID của admin mặc định hoặc admin đầu tiên
        $adminForChat = User::where('role', 1)->first(); // Giả sử cột is_admin
        $adminIdForChat = $adminForChat ? $adminForChat->id : null;
        $currentAdminId = (Auth::check() && Auth::user()->role == 1) ? Auth::id() : null;

    @endphp
    <link rel="shortcut icon" href="{{ url('asset') }}/admin/img/cards/M-removebg-preview.png" />
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
    <link href="{{ url('asset') }}/css/owl-carousel/owl.carousel.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/owl-carousel/owl.theme.default.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/light-box/simplelightbox.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/light-box/magnific-popup.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/form-field/jquery.formstyler.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/Slick-slider/slick-theme.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/theme.css" rel="stylesheet" />
    <link href="{{ url('asset') }}/css/responsive.css" rel="stylesheet" />


<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

        <main>
            <div class="main-part">
                <div class="error-404 Banner-Bg" data-background="{{ url('asset') }}/images/404-bg.jpg">
                    <div class="error-404-inner wow fadeInDown" data-wow-duration="2000ms" data-wow-delay="300ms">
                        <div class="error-404-title">4 <img src="{{ url('asset') }}/images/404.png" alt=""> 4</div>
                        <h2 class="text-primary">YÊU CẦU KHÔNG TỒN TẠI</h2>
                        <p>Trang bạn yêu cầu đã bị xóa hoặc không tồn tại <br> Bấm để trở về <a href="/"> Trang chủ</a></p>
                    </div>
                </div>
            </div>
        </main>


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
    @yield('scripts')
    </div>
  </body>
  <!-- jQuery (bắt buộc) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Summernote CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">

<!-- Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

</html>
