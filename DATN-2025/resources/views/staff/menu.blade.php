<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>AspStudio | POS - Customer Order System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>

    <!-- ================== BEGIN core-css ================== -->
    <link href="{{ url('assetstaff/css/vendor.min.css') }}" rel="stylesheet"/>
    <link href="{{ url('assetstaff/css/app.min.css') }}" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        #coupon-select option:disabled {
            color: #bfbfbf !important;
            background: #f5f5f5;
        }
        .prroduct-staff{
  display: flex;
  justify-content: center;
  align-items: center;

}

.pos-product {
  width: 300px;
  height: 200px;
  background: #111;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  overflow: hidden;
  border-radius: 15px;
  transition: all 0.5s ease;
}

.pos-product h2 {
  color: #0ff;
  font-size: 2rem;
  position: relative;
  z-index: 2;
}

.pos-product::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: linear-gradient(
    0deg,
    transparent,
    transparent 30%,
    rgba(0,255,255,0.3)
  );
  transform: rotate(-45deg);
  transition: all 0.5s ease;
  opacity: 0;
}

.pos-product:hover {
  transform: scale(1.05);
  box-shadow: 0 0 20px rgba(0,255,255,0.5);
}

.pos-product:hover::before {
  opacity: 1;
  transform: rotate(-45deg) translateY(100%);
}
/* Tăng chiều rộng thanh tìm kiếm */
.search-bar {
    width: 90%; /* Tăng từ 70% lên 90% */
    max-width: 1200px; /* Tăng max-width từ 800px lên 1200px */
}

/* Tăng kích thước input và button */
.search-form .form-control {
    height: 60px; /* Tăng chiều cao input */
    font-size: 18px; /* Tăng font size */
    padding: 15px 20px; /* Tăng padding */
}

.search-form .btn {
    height: 60px; /* Tăng chiều cao button */
    font-size: 18px; /* Tăng font size */
    padding: 15px 40px; /* Tăng padding ngang từ 25px lên 40px */
    min-width: 120px; /* Thêm min-width để button không bị quá nhỏ */
    background: linear-gradient(45deg, #28a745, #20c997); /* Đổi màu xanh lá cây */
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.search-form .btn:hover {
    background: linear-gradient(45deg, #20c997, #17a2b8);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
}

/* Các hiệu ứng chuyên nghiệp */
.search-form input.form-control:focus {
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25);
    outline: none;
    border-color: #28a745;
}

.search-form .input-group {
    transition: all 0.3s ease-in-out;
    border-radius: 50px !important; /* Bo tròn hơn */
}

.search-form .input-group:hover {
    box-shadow: 0 0 20px rgba(40, 167, 69, 0.3);
    transform: translateY(-1px);
}



    </style>
    <!-- ================== END core-css ================== -->
</head>
<body class="pace-top">
<div
    id="app"
    class="app app-content-full-height app-without-sidebar app-without-header"
>

    <!-- BEGIN #content -->
    <div id="content" class="app-content p-0">
        <!-- BEGIN pos -->
        <div class="pos pos-with-menu pos-with-sidebar" id="pos">
            <div class="pos-container">
                <!-- BEGIN pos-menu -->
                <div class="pos-menu">
                    <!-- BEGIN logo -->
                    <div class="logo">
                        <a href="">
                            <div class="logo-img"><i class="fa fa-bowl-rice"></i></div>
                            <div class="logo-text">Pine & Dine</div>
                        </a>
                    </div>
                    <!-- END logo -->
                    <!-- BEGIN nav-container -->
                    <div class="nav-container">
                        <div
                            class="h-100"
                            data-scrollbar="true"
                            data-skip-mobile="true"
                        >
                            <ul class="nav nav-tabs mb-4">
                                <li class="nav-item">
                                    <a class="nav-link {{ !isset($selectedDanhmuc) ? 'active' : '' }}"
                                       href="{{ route('staff.products') }}">
                                        <i class="fa fa-fw fa-utensils"></i> Tất cả
                                    </a>
                                </li>
                                @foreach($danhmuc as $dm)
                                    <li class="nav-item">
                                        <a class="nav-link {{ (isset($selectedDanhmuc) && $selectedDanhmuc->id === $dm->id) ? 'active' : '' }}"
                                           href="{{ route('staff.products.category', ['id' => $dm->id]) }}">
                                            {{ $dm->name }}
                                        </a>
                                    </li>
                                @endforeach
                                <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('staff.orderdetailtoday') ? 'active' : '' }}"
                                href="{{ route('staff.orderdetailtoday') }}">
                                    <i class="fa fa-fw fa-file-invoice-dollar"></i> Hóa đơn hôm nay
                                </a>
                            </li>

                            </ul>

                        </div>
                    </div>
                    <!-- END nav-container -->
                </div>
                <!-- END pos-menu -->

                <!-- BEGIN pos-content -->

                <div class="pos-content">
    <div class="pos-content-container h-100">
        <div class="search-bar-container text-center my-4">
                <form action="{{ route('staff.products.search') }}" method="GET" class="d-flex justify-content-center search-form">
                    <div class="input-group search-bar shadow-sm rounded-pill overflow-hidden">
                        <input type="text" name="keyword" class="form-control border-0 px-4" placeholder="Tìm sản phẩm..." value="{{ request('keyword') }}">
                        <button type="submit" class="btn btn-primary px-7 rounded-end">
                            <i class="bi bi-search"></i> Tìm
                        </button>
                    </div>
                </form>
            </div>
        @if(session('message'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Thông báo!</strong> {{ session('message') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            alert("{{ session('message') }}");
        </script>
        @endif
        @if (isset($sanpham))
            @if($sanpham->isEmpty())
                <div class="text-center text-danger fs-4 mt-4">
                    Không có sản phẩm nào trong danh mục này.
                </div>
            @else
                <div class="row gx-4">
                    @foreach ($sanpham as $item)
                        <div class="prroduct-staff col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-3 pb-3" data-type="meat">
                            <a  style="background-color:rgb(226, 226, 226); "  href="#" class="pos-product" data-bs-toggle="modal"
                               data-bs-target="#modalPosItem"
                               data-id="{{ $item->id }}"
                               data-name="{{ $item->name }}"
                               data-image="{{ url("/storage/uploads/$item->image") }}" >
                                <div class="img">
                                    <img src="{{ url("/storage/uploads/$item->image") }}" width="100%" alt="{{ $item->name }}">
                                </div>
                                <div class="info">
                                    <div class="title">{{ $item->name }}</div>

                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</div>

            <!-- END pos-content -->

            <!-- BEGIN pos-sidebar -->
            <div class="pos-sidebar" id="pos-sidebar">
                <div class="h-100 d-flex flex-column p-0">
                    <!-- BEGIN pos-sidebar-header -->
                    <div class="pos-sidebar-header">
                        <div class="back-btn">
                            <button
                                type="button"
                                data-toggle-class="pos-mobile-sidebar-toggled"
                                data-toggle-target="#pos"
                                class="btn"
                            >
                                <i class="fa fa-chevron-left"></i>
                            </button>
                        </div>
                        <div class="icon"><i class="fa fa-plate-wheat"></i></div>
                        <div class="title">Table</div>
                        <div class="order small">
                            Order
                        </div>
                    </div>
                    <!-- END pos-sidebar-header -->

                    <!-- BEGIN pos-sidebar-nav -->
                    <div class="pos-sidebar-nav small">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a
                                    class="nav-link active"
                                    href="#"
                                    data-bs-toggle="tab"
                                    data-bs-target="#newOrderTab"
                                >Order</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div
                        class="pos-sidebar-body tab-content"
                        data-scrollbar="true"
                        data-height="100%"
                    >
                        <!-- BEGIN #newOrderTab -->
                        <div class="tab-pane fade h-100 show active" id="newOrderTab">
                            <div class="pos-order-list"></div>
                        </div>
                        <!-- END #orderHistoryTab -->

                        <!-- BEGIN #orderHistoryTab -->
                        <!-- END #orderHistoryTab -->
                    </div>
                    <!-- END pos-sidebar-body -->

                    <!-- BEGIN pos-sidebar-footer -->
                    <div class="pos-sidebar-footer">
                        <div class="mb-2">
                            <select class="form-select" id="coupon-select">
                                <option value="">-- Chọn mã giảm giá --</option>
                                <!-- option sẽ được JS render thêm -->
                            </select>
                            <div id="apply-coupon-btn"></div>
                            <div id="coupon-message" style="font-size:14px;margin-top:4px;"></div>
                        </div>
                        <hr class="opacity-1 my-10px"/>
                        <div class="d-flex align-items-center mb-2">
                            <div>Subtotal</div>
                            <div class="flex-1 text-end h6 mb-0" id="cart-subtotal">0đ</div>
                        </div>
                        <div class="d-flex align-items-center mb-2 text-danger">
                            <div>Discount</div>
                            <div class="flex-1 text-end h6 mb-0" id="cart-discount">0đ</div>
                        </div>
                        <hr class="opacity-1 my-10px"/>
                        <div class="d-flex align-items-center mb-2">
                            <div>Total</div>
                            <div class="flex-1 text-end h4 mb-0" id="cart-total">0đ</div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-sm-around">
                                <a style="max-width: 130px;"
                                    href="#"
                                    class="btn btn-primary flex-fill d-flex align-items-center justify-content-center btn-submit-order"
                                    id="btn-confirm-order"
                                    data-pay_status="0"
                                >
                                    <span>
                                    <i class="fa fa-check-circle fa-lg my-10px d-block"></i>
                                      <span class="small fw-semibold">Xác nhận</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- END pos-sidebar-footer -->
                </div>
            </div>
            <!-- END pos-sidebar -->
        </div>
    </div>
    <!-- END pos -->

    <!-- BEGIN pos-mobile-sidebar-toggler -->
    <a
        href="#"
        class="pos-mobile-sidebar-toggler"
        data-toggle-class="pos-mobile-sidebar-toggled"
        data-toggle-target="#pos"
    >
        <i class="fa fa-shopping-bag"></i>
        <span class="badge">5</span>
    </a>
    <!-- END pos-mobile-sidebar-toggler -->
</div>

<a href="#" data-click="scroll-top" class="btn-scroll-top fade"
><i class="fa fa-arrow-up"></i
    ></a>


<!-- BEGIN #modalPosItem -->
<div class="modal modal-pos fade" id="modalPosItem">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <a href="#" data-bs-dismiss="modal" class="btn-close position-absolute top-0 end-0 m-4"></a>
            <div class="modal-pos-product">
                <div class="modal-pos-product-img">
                    <div class="img">
                        <img class="product-image" src="" width="100%" alt="">
                    </div>
                </div>
                <div class="modal-pos-product-info">
                    <div class="fs-4 fw-semibold product-name"></div>
                    <div class="fs-3 fw-bold mb-3 product-price"></div>
                    <div class="d-flex mb-3">
                        <a href="#" class="btn btn-secondary qty-decrease"><i class="fa fa-minus"></i></a>
                        <input type="text" class="form-control w-50px fw-bold mx-2 text-center" name="qty" value="1"/>
                        <a href="#" class="btn btn-secondary qty-increase"><i class="fa fa-plus"></i></a>
                    </div>
                    <hr class="opacity-1"/>
                    <div class="mb-2">
                        <div class="fw-bold">Kích thước:</div>
                        <div class="option-list size-list"></div>
                    </div>
                    <div class="mb-2">
                        <div class="fw-bold">Topping:</div>
                        <div class="option-list topping-list"></div>
                    </div>
                    <hr class="opacity-1"/>
                    <div class="row">
                        <div class="col-4">
                            <a href="#" class="btn btn-default fw-semibold mb-0 d-block py-3" data-bs-dismiss="modal">Hủy</a>
                        </div>
                        <div class="col-8">
                            <a href="#" class="btn btn-theme fw-semibold d-flex justify-content-center align-items-center py-3 m-0 add-to-cart">
                                Thêm vào giỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END #modalPosItem -->

<!-- ================== BEGIN core-js ================== -->
<script
    src="{{ url('assetstaff') }}/js/vendor.min.js"
></script>
<script
    src="{{ url('assetstaff') }}/js/app.min.js"
></script>
<script
    src="{{ url('assetstaff') }}/js/demo/pos-customer-order.demo.js"
></script>
</body>
</html>
