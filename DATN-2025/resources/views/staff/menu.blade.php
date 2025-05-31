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
                        @if($sanpham->isEmpty())
                            <div class="text-center text-danger fs-4 mt-4">
                                Không có sản phẩm nào trong danh mục này.
                            </div>
                        @else
                            <div class="row gx-4">
                                @foreach ($sanpham as $item)
                                    <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="meat">
                                        <a href="#" class="pos-product" data-bs-toggle="modal"
                                           data-bs-target="#modalPosItem"
                                           data-id="{{ $item->id }}"
                                           data-name="{{ $item->name }}"
                                           data-image="{{ url("/storage/uploads/$item->image") }}"
                                           data-description="{{ $item->mota }}">
                                            <div class="img">
                                                <img src="{{ url("/storage/uploads/$item->image") }}" width="100%"
                                                     alt="{{ $item->name }}">
                                            </div>
                                            <div class="info">
                                                <div class="title">{{ $item->name }}</div>
                                                <div class="desc">{{ $item->mota }}</div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
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
                        <ul class="nav nav-tabs nav-fill">
                            <li class="nav-item">
                                <a
                                    class="nav-link active"
                                    href="#"
                                    data-bs-toggle="tab"
                                    data-bs-target="#newOrderTab"
                                >New Order (5)</a
                                >
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link"
                                    href="#"
                                    data-bs-toggle="tab"
                                    data-bs-target="#orderHistoryTab"
                                >Order History (0)</a
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
                            <div class="pos-order">
                                <div class="pos-order-product">
                                    <div
                                        class="img"
                                        style="
                            background-image: url(assets/img/pos/product-2.jpg);
                          "
                                    ></div>
                                    <div class="flex-1">
                                        <div class="h6 mb-1">Grill Pork Chop</div>
                                        <div class="small">$12.99</div>
                                        <div class="small mb-2">- size: large</div>
                                        <div class="d-flex">
                                            <a href="#" class="btn btn-secondary btn-sm"
                                            ><i class="fa fa-minus"></i
                                                ></a>
                                            <input
                                                type="text"
                                                class="form-control w-50px form-control-sm mx-2 bg-white bg-opacity-25 bg-white bg-opacity-25 text-center"
                                                value="01"
                                            />
                                            <a href="#" class="btn btn-secondary btn-sm"
                                            ><i class="fa fa-plus"></i
                                                ></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="pos-order-price d-flex flex-column">
                                    <div class="flex-1">$12.99</div>
                                    <div class="text-end">
                                        <a href="#" class="btn btn-default btn-sm"
                                        ><i class="fa fa-trash"></i
                                            ></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END #orderHistoryTab -->

                        <!-- BEGIN #orderHistoryTab -->
                        <div class="tab-pane fade h-100" id="orderHistoryTab">
                            <div
                                class="h-100 d-flex align-items-center justify-content-center text-center p-20"
                            >
                                <div>
                                    <div class="mb-3 mt-n5">
                                        <svg
                                            width="6em"
                                            height="6em"
                                            viewBox="0 0 16 16"
                                            class="text-gray-300"
                                            fill="currentColor"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M14 5H2v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5zM1 4v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4H1z"
                                            />
                                            <path
                                                d="M8 1.5A2.5 2.5 0 0 0 5.5 4h-1a3.5 3.5 0 1 1 7 0h-1A2.5 2.5 0 0 0 8 1.5z"
                                            />
                                        </svg>
                                    </div>
                                    <h5>No order history found</h5>
                                </div>
                            </div>
                        </div>
                        <!-- END #orderHistoryTab -->
                    </div>
                    <!-- END pos-sidebar-body -->

                    <!-- BEGIN pos-sidebar-footer -->
                    <div class="pos-sidebar-footer">
                        <div class="d-flex align-items-center mb-2">
                            <div>Subtotal</div>
                            <div class="flex-1 text-end h6 mb-0">$30.98</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div>Taxes (6%)</div>
                            <div class="flex-1 text-end h6 mb-0">$2.12</div>
                        </div>
                        <hr class="opacity-1 my-10px"/>
                        <div class="d-flex align-items-center mb-2">
                            <div>Total</div>
                            <div class="flex-1 text-end h4 mb-0">$33.10</div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex">
                                <a
                                    href="#"
                                    class="btn btn-default w-70px me-10px d-flex align-items-center justify-content-center"
                                >
                        <span>
                          <i class="fa fa-bell fa-lg my-10px d-block"></i>
                          <span class="small fw-semibold">Service</span>
                        </span>
                                </a>
                                <a
                                    href="#"
                                    class="btn btn-default w-70px me-10px d-flex align-items-center justify-content-center"
                                >
                        <span>
                          <i
                              class="fa fa-receipt fa-fw fa-lg my-10px d-block"
                          ></i>
                          <span class="small fw-semibold">Bill</span>
                        </span>
                                </a>
                                <a
                                    href="#"
                                    class="btn btn-theme flex-fill d-flex align-items-center justify-content-center"
                                >
                        <span>
                          <i
                              class="fa fa-cash-register fa-lg my-10px d-block"
                          ></i>
                          <span class="small fw-semibold">Submit Order</span>
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
