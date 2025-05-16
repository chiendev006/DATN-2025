{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Users" icon="la la-question" :link="backpack_url('users')" />
<x-backpack::menu-item title="Sanphams" icon="la la-question" :link="backpack_url('sanphams')" />
<x-backpack::menu-item title="Sizes" icon="la la-question" :link="backpack_url('size')" />
<x-backpack::menu-item title="Toppings" icon="la la-question" :link="backpack_url('topping')" />
<x-backpack::menu-item title="Danhmucs" icon="la la-question" :link="backpack_url('danhmucs')" />
<x-backpack::menu-item title="Product images" icon="la la-question" :link="backpack_url('product-images')" />