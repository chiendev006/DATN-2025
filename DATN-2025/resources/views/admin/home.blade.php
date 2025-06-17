@include('header')

    <div class="content-wrapper-scroll">

                    <div class="content-wrapper">
                    <div class="row gutters">
                    <div class="container">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div style="align-items: center;" class="col-xl-11 col-lg-11 col-md-11 col-sm-11 col-11">
                    <h2>Thống kê 12 tháng gần nhất</h2>
                   <div style="background-color: white;" >

                    <canvas style="width: 100%; height: 100%;" id="lineChart"></canvas>
                   </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; " class="col-xl-11 col-lg-11 col-md-11 col-sm-11 col-11">
                  <div style="margin-top: 10px;" class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" >
                  <h2>Đơn hàng trong tuần này</h2>
                    <div style="background-color: white;" >

                    <canvas  id="barChart"></canvas>
                    </div>
                  </div>

                  <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-5" >
                    <h2 style="margin-top: 10px;">Top sản phẩm</h2>
                                <div class="card">


                                    <div  class="card-body">
										<div  style="margin-top: 10px;" class="table-responsive">
											<table class="table products-table">
												<thead>
													<tr>
                                                        <th>Top </th>
														<th>Sản phẩm</th>
														<th>Sô lượt bán</th>
													</tr>
												</thead>
												<tbody>
                                                    @foreach ($topproduct as $key => $item)
													<tr>
														<td>{{ $key+1}}</td>
														<td>{{ $item->product->name }}</td>
														<td style="text-align: center;">{{ $item->total_quantity }}</td>
													</tr>
                                                    @endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
                            </div>
                    </div>


    </div>

    <div  style="margin-top: 10px;margin-left:3px" class="row gutters col-xl-11 col-lg-11 col-md-11 col-sm-11 col-11" >

                            <h2>Đơn hàng gần đây</h2>
								<div class="card">
									<div class="card-header">


									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table class="table products-table">
												<thead>
													<tr>
														<th>Mã đơn hàng</th>
														<th>Ngày đặt hàng</th>
														<th>Tên khách hàng</th>
														<th>Số điện thoại</th>
														<th>Tổng tiền</th>
														<th>Trạng thái</th>
													</tr>
												</thead>
												<tbody>
                                                    @foreach ($recentOrders as $order)
													<tr>
														<td>{{ $order->id }}</td>
														<td>{{ $order->created_at }}</td>
														<td>{{ $order->name }}</td>
														@if( $order->phone=="N/A")
                                                        <td>Nhân viên order</td>
                                                        @else
                                                        <td>{{ $order->phone }}</td>
                                                        @endif
														<td>{{ number_format($order->total) }} đ</td>
														<td>{{ $order->status }}</td>
													</tr>
                                                    @endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>

						</div><div id="modal-revenue" style="margin-top: 10px;margin-left:3px; " class="row gutters col-xl-11 col-lg-11 col-md-11 col-sm-11 col-11" >

<h2>Doanh thu
    <form id="revenueFilterForm" method="post">
        @csrf
        từ
        <input type="date" name="start" id="start_date">
        đến
        <input type="date" name="end" id="end_date">
        <button style="border-radius: 5px;" type="button" onclick="filterRevenue()">Lọc</button>
    </form>
</h2>
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table products-table">
                    <thead>
                        <tr>
                            <th>Ngày </th>
                            <th>Số lượng đơn</th>
                            <th>Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody id="revenueTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>


 <div class="col-xl-11 col-lg-11 col-md-11 col-sm-11 col-11"><h2>Xu hướng khách hàng</h2>
<div id="chartContainer" class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3" style="width:100%;height:400px;"></div></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ đường: Đơn và doanh thu 12 tháng
    var months = JSON.parse('@json($months)');
    var ordersPerMonth = JSON.parse('@json($ordersPerMonth)');
    var revenuePerMonth = JSON.parse('@json($revenuePerMonth)');

    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Số đơn',
                    data: ordersPerMonth,
                    borderColor: 'blue',
                    fill: false,
                    spanGaps: true
                },
                {
                    label: 'Doanh thu',
                    data: revenuePerMonth,
                    borderColor: 'green',
                    fill: false,
                    yAxisID: 'y1',
                    spanGaps: true
                }
            ]
        },
        options: {
            animations: {
                x: {
                    type: 'number',
                    easing: 'easeInOutSine',
                    duration: 1800,
                    from: NaN,
                    delay(ctx) {
                        return ctx.index * 30;
                    }
                },
                y: {
                    type: 'number',
                    easing: 'easeInOutSine',
                    duration: 1800,
                    from: NaN,
                    delay(ctx) {
                        return ctx.index * 30;
                    }
                }
            },
            scales: {
                y: { beginAtZero: true, position: 'left', title: { display: true, text: 'Số đơn' } },
                y1: { beginAtZero: true, position: 'right', title: { display: true, text: 'Doanh thu' }, grid: { drawOnChartArea: false } }
            }
        }
    });

    // Biểu đồ cột: Đơn theo trạng thái trong tuần
    var orderStatusWeek = JSON.parse('@json($orderStatusWeek)');
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: ['Thành công', 'Chưa thanh toán'],
            datasets: [{
                label: 'Số đơn',
                data: [orderStatusWeek.success, orderStatusWeek.pending, orderStatusWeek.cancel],
                backgroundColor: ['#4caf50', '#ff9800', '#f44336']
            }]
        },
        options: {
            animations: {
                y: {
                    duration: 1500,
                    easing: 'easeOutBounce'
                }
            },
            indexAxis: 'x',
            scales: {
                y: {
                    beginAtZero: true
                }
            },

        }
    });

</script>
<script>
window.onload = function() {

var muaThang = {{ isset($muaThang) ? (int)$muaThang : 0 }};
var muaTaiKhoan = {{ isset($muaTaiKhoan) ? (int)$muaTaiKhoan : 0 }};

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title: {

	},
	data: [{
		type: "pie",
		startAngle: 180,
		yValueFormatString: "#,##0 đơn",
		indexLabel: "{label} {y}",
		dataPoints: [
			{y: muaThang, label: "Mua thẳng"},
			{y: muaTaiKhoan, label: "Mua với tài khoản shop"},
		]
	}]
});
chart.render();

}
</script>

<script>
function filterRevenue() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;

    if (!startDate || !endDate) {
        alert('Vui lòng chọn cả ngày bắt đầu và ngày kết thúc');
        return;
    }

    // Show loading state
    document.getElementById('revenueTableBody').innerHTML = '<tr><td colspan="3" class="text-center">Đang tải...</td></tr>';

    // Make AJAX request
    fetch('/admin/revenue/filter', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            start_date: startDate,
            end_date: endDate
        })
    })
    .then(response => response.json())
    .then(data => {
        let html = '';
        if (data.length === 0) {
            html = '<tr><td colspan="3" class="text-center">Không có dữ liệu</td></tr>';
        } else {
            data.forEach(item => {
                html += `
                    <tr>
                        <td>${item.date}</td>
                        <td>${item.total_orders}</td>
                        <td>${new Intl.NumberFormat('vi-VN').format(item.revenue)} đ</td>
                    </tr>
                `;
            });
        }
        document.getElementById('revenueTableBody').innerHTML = html;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('revenueTableBody').innerHTML =
            '<tr><td colspan="3" class="text-center">Có lỗi xảy ra khi tải dữ liệu</td></tr>';
    });
}
</script>


                            </div>
                        </div>

                    @include('footer')

                    </div>



