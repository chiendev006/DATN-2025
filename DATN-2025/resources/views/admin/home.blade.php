@include('header')

    <div class="content-wrapper-scroll">

                    <div class="content-wrapper">
                    <div class="row gutters">
                    <div class="container">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
    <div style='display: flex; justify-items: stretch;' class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-5">
                    <h2>Thống kê 12 tháng gần nhất</h2>
                   <div style="background-color: white;" >

                    <canvas id="lineChart"></canvas>
                   </div>
                    </div>

                    <div style="margin-left: 10px;" class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-5">
                    <h2>Đơn hàng trong tuần này</h2>
                    <div style="background-color: white;" >

                    <canvas id="barChart"></canvas>
                    </div>
                    </div>

                    </div>
    </div>

    <div style="margin-top: 10px;" class="row gutters">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Đơn gần đây</div>
										<div class="graph-day-selection" role="group">
											<button type="button" class="btn active">Export to Excel</button>
										</div>
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
														<td>{{ $order->transaction_id }}</td>
														<td>{{ $order->created_at }}</td>
														<td>{{ $order->name }}</td>
														<td>{{ $order->phone }}</td>
														<td>{{ $order->total }}</td>
														<td>{{ $order->status }}</td>
													</tr>
                                                    @endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>


 <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"><h2>Xu hướng khách hàng</h2>
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
            labels: ['Thành công', 'Chưa thanh toán', 'Đã hủy'],
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
            plugins: {
                legend: {
                    display: false
                }
            }
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


                            </div>
                        </div>

                    @include('footer')

                    </div>



