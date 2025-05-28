@extends('layout2')
@section('main')
<!-- ... phần HTML khác ... -->

<div class="form-group">
    <label><strong>Chọn size:</strong></label><br>
    @foreach($sizes as $size)
        <label class="mr-3">
            <input type="radio" 
                   name="size_id" 
                   value="{{ $size->id }}" 
                   class="size-option" 
                   data-price="{{ $size->price }}" 
                   required>
            {{ $size->size }} ({{ number_format($size->price) }} VND)
        </label><br>
    @endforeach
</div>

<div class="form-group">
    <label><strong>Chọn topping:</strong></label><br>
    @foreach($toppings as $top)
        <label class="mr-3">
            <input type="checkbox" 
                   name="topping_ids[]" 
                   value="{{ $top->id }}" 
                   class="topping-option" 
                   data-price="{{ $top->price }}">
            {{ $top->topping }} ({{ number_format($top->price) }} VND)
        </label><br>
    @endforeach
</div>

<div class="price-textbox">
    <span class="minus-text"><i class="icon-minus" onclick="changeQty(-1)"></i></span>
    <input type="text" name="qty" id="quantity" placeholder="1" value="1" readonly>
    <span class="plus-text"><i class="icon-plus" onclick="changeQty(1)"></i></span>
</div>

<h3 class="text-coffee">
    <span id="display-price">{{ number_format($sanpham->price) }} VND</span>
</h3>

<!-- ... phần HTML khác ... -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hàm thay đổi số lượng
    window.changeQty = function(delta) {
        const input = document.getElementById('quantity');
        let qty = parseInt(input.value) || 1;
        qty = Math.max(1, qty + delta);
        input.value = qty;
        updatePrice();
    }

    // Hàm cập nhật giá
    function updatePrice() {
        try {
            // Lấy giá size
            const sizeChecked = document.querySelector('input[name="size_id"]:checked');
            let basePrice = 0;
            if (sizeChecked) {
                basePrice = parseInt(sizeChecked.dataset.price) || 0;
            }

            // Lấy số lượng
            const qty = parseInt(document.getElementById('quantity').value) || 1;

            // Tính tổng giá topping
            let toppingTotal = 0;
            document.querySelectorAll('input[name="topping_ids[]"]:checked').forEach(topping => {
                const toppingPrice = parseInt(topping.dataset.price) || 0;
                toppingTotal += toppingPrice;
            });

            // Tính tổng giá cuối cùng
            const finalPrice = (basePrice + toppingTotal) * qty;

            // Hiển thị giá đã format
            if (!isNaN(finalPrice) && finalPrice >= 0) {
                document.getElementById('display-price').textContent = finalPrice.toLocaleString('vi-VN') + ' VND';
            }
        } catch (error) {
            console.error('Lỗi khi tính giá:', error);
        }
    }

    // Thêm sự kiện cho size
    document.querySelectorAll('input[name="size_id"]').forEach(input => {
        input.addEventListener('change', updatePrice);
    });

    // Thêm sự kiện cho topping
    document.querySelectorAll('input[name="topping_ids[]"]').forEach(input => {
        input.addEventListener('change', updatePrice);
    });

    // Tự động chọn size đầu tiên nếu chưa có size nào được chọn
    const firstSize = document.querySelector('input[name="size_id"]');
    if (firstSize && !document.querySelector('input[name="size_id"]:checked')) {
        firstSize.checked = true;
    }

    // Khởi tạo giá ban đầu
    updatePrice();
});
</script>

@endsection 