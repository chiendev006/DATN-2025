@extends('staff.layout')

@section('main-content')
<h2 class="text-2xl font-bold text-indigo-700 mb-6">🧾 Hóa đơn hôm nay</h2>

@if ($donhangs->isEmpty())
    <div class="bg-white p-6 rounded shadow text-gray-500 italic text-center">
        Chưa có hóa đơn nào hôm nay.
    </div>
@else
    @foreach ($donhangs as $item)
        <div class="bg-white p-6 rounded shadow mb-8 hover:shadow-lg transition">
            <!-- Dòng nổi bật: ID và thời gian -->
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-lg font-bold text-indigo-700">#Hóa đơn {{ $item->id }}</h3>
                <span class="text-sm text-gray-500">
                    {{ $item->created_at->format('d/m/Y H:i') }}
                </span>
            </div>

            <!-- Thông tin khách hàng + đơn hàng -->
            <table class="w-full text-sm border border-gray-300">
                <thead class="bg-indigo-100 text-indigo-800 font-semibold">
                    <tr>
                        <th class="border px-4 py-2">Khách hàng</th>
                        <th class="border px-4 py-2">Địa chỉ</th>
                        <th class="border px-4 py-2">SĐT</th>
                        <th class="border px-4 py-2">Phương thức</th>
                        <th class="border px-4 py-2">Sản phẩm</th>
                        <th class="border px-4 py-2 text-right">Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-indigo-50">
                        <td class="border px-4 py-2">{{ $item->name }}</td>
                        <td class="border px-4 py-2">{{ $item->address }}</td>
                        <td class="border px-4 py-2">{{ $item->phone }}</td>
                        <td class="border px-4 py-2">{{ $item->payment_method }}</td>
                        <td class="border px-4 py-2 whitespace-pre-wrap">
                            @foreach ($item->details as $ct)
                                - {{ $ct->product_name }} (x{{ $ct->quantity }}) = {{ number_format($ct->total, 0, ',', '.') }} đ
                                @if (!$loop->last)<br>@endif
                            @endforeach
                        </td>
                        <td class="border px-4 py-2 text-right font-semibold text-green-600">
                            {{ number_format($item->total, 0, ',', '.') }} đ
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach
@endif
@endsection
