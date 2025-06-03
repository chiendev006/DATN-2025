  @extends('layout2')
    @section('main')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <main>
     <div class="max-w-8xl mx-auto px-10 py-10">
        
  <!-- User Header -->
  <div class="bg-white rounded-lg shadow p-6 flex flex-col md:flex-row items-center md:items-start gap-6">
    <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-3xl text-gray-400">
      <i class="fas fa-user"></i>
    </div>
    <div class="flex-1 space-y-2">
      <h2 class="text-2xl font-bold">Fethawi Tesfalem</h2>
      <div class="text-gray-600 flex items-center gap-2">
        <i class="fas fa-map-marker-alt text-gray-500"></i> Ghunsh, Muksudpur Dhaka - Gopalganj
      </div>
      <div class="text-gray-600 flex items-center gap-2">
        <i class="fas fa-phone text-gray-500"></i> +880 123456879
      </div>
      <div class="text-gray-600 flex items-center gap-2">
        <i class="fas fa-envelope text-gray-500"></i> saiful@gshop.com
      </div>
    </div>
   <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full md:w-auto mt-4 md:mt-0">
  <div class="bg-green-100 text-green-800 p-4 rounded-lg text-center">
    <div class="text-2xl font-bold">4k+</div>
    <div class="text-base">Total Order</div>
  </div>
  <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg text-center">
    <div class="text-2xl font-bold">10+</div>
    <div class="text-base">Order Processing</div>
  </div>
  <div class="bg-blue-100 text-blue-800 p-4 rounded-lg text-center">
    <div class="text-2xl font-bold">3.5k+</div>
    <div class="text-base">Total Delivered</div>
  </div>
  <div class="bg-purple-100 text-purple-800 p-4 rounded-lg text-center">
    <div class="text-2xl font-bold">25+</div>
    <div class="text-base">Pending Orders</div>
  </div>
</div>

  </div>

  <!-- Main Content -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <!-- Sidebar -->
    <div class="bg-white rounded-lg shadow p-4 space-y-4">
      <h3 class="text-lg font-semibold">Manage My Account</h3>
      <ul class="space-y-2 text-gray-700">
        <li><button class="order-tab active-tab text-lg font-medium text-green-600 hover:underline" data-status="all"></button>Tất cả</button> </li>
        <li><button class="order-tab text-lg font-medium text-gray-600 hover:underline" data-status="completed">Đã mua</button></li>
        <li><button class="order-tab text-lg font-medium text-gray-600 hover:underline" data-status="processing">Đang chờ giao</button></li>
        <li><button class="order-tab text-lg font-medium text-gray-600 hover:underline" data-status="cancelled">Đã hủy</button></li>
      </ul>
    </div>

   <!-- Order Filter & Product Display -->
<div class="md:col-span-2 bg-white rounded-lg shadow p-4">
  <div class="flex justify-between items-center mb-4">
    <h3 class="text-lg font-semibold">Sản phẩm đã đặt</h3>
  </div>





<!-- Product List -->
<div id="product-list" class="grid md:grid-cols-2 gap-6 text-gray-700">
    @foreach($orders as $item)
        @php
            $status = $item->order->status;

            $badgeColor = match($status) {
                'completed' => 'bg-green-100 text-green-700',
                'processing' => 'bg-yellow-100 text-yellow-700',
                'cancelled' => 'bg-red-100 text-red-700',
                default => 'bg-gray-100 text-gray-700',
            };

            // Xử lý ảnh
            $image = $item->product->image
                ? asset('storage/' . $item->product->image)
                : 'https://via.placeholder.com/150x150.png?text=Product';

            // Xử lý topping
            $toppingNames = [];
            if (!empty($item->topping_id)) {
                $toppingIds = explode(',', $item->topping_id);
                foreach ($toppingIds as $id) {
                    if (isset($toppings[$id])) {
                        $toppingNames[] = $toppings[$id]->name;
                    }
                }
            }
        @endphp

        <div class="product-item" data-status="{{ $status }}">
            <div class="border p-4 rounded-lg hover:shadow-lg transition duration-300 bg-white">
                <div class="flex items-center gap-4">
                    <img src="{{ $image }}" alt="{{ $item->product_name }}" class="w-24 h-24 rounded object-cover">
                    <div class="flex-1 space-y-1">
                        <div class="flex justify-between items-center">
                            <p class="font-semibold text-lg">{{ $item->product_name }}</p>
                            <span class="inline-block {{ $badgeColor }} text-sm font-semibold px-3 py-1 rounded-full">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                        <p>Số lượng: {{ $item->quantity }}</p>
                        <p>Giá: {{ number_format($item->product_price, 0, ',', '.') }}đ</p>
                        <p><strong>Tổng giá:</strong> {{ number_format($item->total, 0, ',', '.') }}đ</p>
                        <p>Size: {{ $item->size_id ?? 'Không có' }}</p>
                        <p>Topping: {{ count($toppingNames) > 0 ? implode(', ', $toppingNames) : 'Không có' }}</p>
                        <button class="mt-2 px-4 py-1 bg-green-500 text-white rounded hover:bg-green-600">Mua lại</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>




</div>

<!-- JavaScript lọc sản phẩm -->
<script>
  const tabs = document.querySelectorAll('.order-tab');
  const products = document.querySelectorAll('.product-item');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('text-green-600', 'active-tab'));
      tab.classList.add('text-green-600', 'active-tab');

      const status = tab.getAttribute('data-status');

      products.forEach(product => {
        if (status === 'all' || product.getAttribute('data-status') === status) {
          product.style.display = 'block';
        } else {
          product.style.display = 'none';
        }
      });
    });
  });
</script>

  </div>
</div>
   </main>
     <script src="https://cdn.tailwindcss.com"></script>
@endsection

