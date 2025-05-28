@foreach($products as $product)
<div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="700ms">
    <div class="menu-fix-list">
        <div class="menu-fix-item">
            <img src="{{ asset('asset/images/' . $product->image) }}" alt="{{ $product->name }}">
        </div>
        <h5>{{ $product->name }} <span>${{ number_format($product->price, 2) }}</span></h5>
        <p>{{ $product->description }}</p>
    </div>
</div>
@endforeach