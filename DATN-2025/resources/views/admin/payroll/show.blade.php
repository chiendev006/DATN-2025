@include('header')
<div class="content-wrapper-scroll">
    <div class="content-wrapper">
        <div class="row gutters">
   <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">

    <h2>Bảng lương tháng {{ $payroll->month }}</h2>
    <p><strong>Tổng tiền công phải trả:</strong> <span id="payroll-total">{{ number_format($payroll->total) }} VND</span></p>

    <style>
        .workday-list-scroll {
            max-width: 570px;   /* Tăng chiều rộng cột nếu muốn */
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 2px;
        }
        .workday-cell {
            display: inline-block;
            width: 22px;           /* Tăng từ 22px lên 32px */
            height: 22px;          /* Tăng từ 22px lên 32px */
            line-height: 22px;     /* Tăng từ 22px lên 32px */
            text-align: center;
            margin-right: 4px;     /* Tăng khoảng cách giữa các ô */
            margin-bottom: 4px;
            font-size: 16px;       /* Tăng font chữ */
            border-radius: 6px;    /* Tăng bo góc */
            cursor: pointer;
            user-select: none;
        }
        th{
            text-align: center;
        }
        td{
            text-align: center;
        }
    </style>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nhân viên</th>
                <th>Lương/ngày</th>
                <th>Số ngày công</th>
                <th>Tổng lương</th>
                <th>Lịch công (ngày làm việc được đánh dấu)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payroll->details as $detail)
                <tr>
                    <td>{{ $detail->user->name }}</td>
                    <td>{{ number_format($detail->user->salary_per_day) }} VND</td>
                    <td id="total-days-{{ $detail->id }}">{{ $detail->total_days }}</td>
                    <td id="total-salary-{{ $detail->id }}">{{ number_format($detail->total_salary) }} VND</td>
                    <td style="justify-items: center;">
                        @php
                            $workDays = $detail->work_days ? array_map('intval', explode(',', $detail->work_days)) : [];
                            $month = $payroll->month;
                            $daysInMonth = \Carbon\Carbon::parse($month . '-01')->daysInMonth;
                        @endphp
                        <div class="workday-list-scroll">
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                @php $isChecked = in_array($d, $workDays); @endphp
                                <span
                                    class="workday-cell"
                                    data-detail-id="{{ $detail->id }}"
                                    data-day="{{ $d }}"
                                    style="{{ $isChecked ? 'background:#4caf50;color:#fff;' : 'background:#eee;color:#aaa;' }}"
                                    title="Ngày {{ $d }}"
                                >{{ $d }}</span>
                            @endfor
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button style="margin-left:10px; margin-bottom:10px ; border-radius: 15px; margin-top: 20px;"  class="btn btn-primary col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2"><a style="color:white;" href="{{ route('payroll.index') }}">Trở lại danh sách</a></button>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $('.workday-cell').on('click', function() {
        var $cell = $(this);
        var detailId = $cell.data('detail-id');
        var day = $cell.data('day');
        var isChecked = $cell.css('background-color') === 'rgb(76, 175, 80)'; // #4caf50

        $.ajax({
            url: '{{ route("payroll.toggleWorkDay") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                detail_id: detailId,
                day: day
            },
            success: function(res) {
                if(res.status === 'added') {
                    $cell.css({'background':'#4caf50', 'color':'#fff'});
                } else if(res.status === 'removed') {
                    $cell.css({'background':'#eee', 'color':'#aaa'});
                }
                // Cập nhật số ngày công và tổng lương từng nhân viên
                $('#total-days-' + detailId).text(res.total_days);
                $('#total-salary-' + detailId).text(Number(res.total_salary).toLocaleString('vi-VN') + ' VND');
                // Cập nhật tổng tiền công phải trả
                $('#payroll-total').text(Number(res.payroll_total).toLocaleString('vi-VN') + ' VND');
            }
        });
    });
});
</script>
@include('footer')
</div>
</div>
</div>
</div>
