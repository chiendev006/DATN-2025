@extends('staff.layout')

@section('main-content')
<h2 class="text-3xl font-extrabold text-indigo-800 mb-8 flex items-center gap-2">
    🧾 Hóa đơn hôm nay
</h2>

@if ($donhangs->isEmpty())
    <div class="bg-gray-50 p-6 rounded-xl shadow text-gray-500 italic text-center border border-dashed">
        Chưa có hóa đơn nào hôm nay.
    </div>
@else
    <div class="space-y-8">
        @foreach ($donhangs as $item)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <!-- Header hóa đơn -->
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-indigo-200">
                    <div>
                        <h3 class="text-3xl font-extrabold text-indigo-700">#{{ $item->id }}</h3>
                        <p class="text-sm text-gray-500">Mã hóa đơn</p>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-semibold text-gray-700">{{ $item->created_at->format('H:i') }}</div>
                        <div class="text-sm text-gray-500">{{ $item->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>

                <!-- Bảng thông tin -->
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed text-sm border border-gray-200 rounded-lg">
                        <thead class="bg-indigo-100 text-indigo-800 uppercase">
                            <tr>
                                <th class="px-4 py-2 border text-left w-[18%]">👤 Khách hàng</th>
                                <th class="px-4 py-2 border text-left w-[15%]">📞 SĐT</th>
                                <th class="px-4 py-2 border text-left w-[17%]">💳 Thanh toán</th>
                                <th class="px-4 py-2 border text-left w-[25%]">🛍️ Sản phẩm</th>
                                <th class="px-4 py-2 border text-center w-[10%]">🔢 SL</th>
                                <th class="px-4 py-2 border text-right w-[15%]">💰 Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white hover:bg-gray-50 transition">
                                <td class="px-4 py-2 border font-medium text-gray-700 break-words">
                                    {{ $item->name ?? 'Khách lẻ' }}
                                </td>
                                <td class="px-4 py-2 border text-gray-600 break-words">
                                    {{ $item->phone ?? '---' }}
                                </td>
                                <td class="px-4 py-2 border capitalize text-indigo-600 font-semibold break-words">
                                    {{ $item->payment_method === 'cash' ? 'Tiền mặt 💵' : 'Thẻ 💳' }}
                                </td>
                                <td class="px-4 py-2 border break-words">
                                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                                        @foreach ($item->details as $ct)
                                            <li>{{ $ct->product_name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-4 py-2 border text-center break-words">
                                    <ul class="space-y-1 text-gray-700 font-semibold">
                                        @foreach ($item->details as $ct)
                                            <li>x{{ $ct->quantity }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-4 py-2 border text-right font-bold text-green-700 text-base break-words">
                                    {{ number_format($item->total, 0, ',', '.') }} đ
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
