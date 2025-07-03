@include('header')
<style>
    .card-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    .text-primary { color: #007bff !important; }
    .text-success { color: #28a745 !important; }
    .text-info { color: #17a2b8 !important; }
    .text-warning { color: #ffc107 !important; }
    .table-sm th, .table-sm td {
        padding: 0.5rem;
        font-size: 0.875rem;
    }
    .text-muted {
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }
    .card-header h5 {
        margin-bottom: 0;
        font-weight: 600;
        color: #495057;
    }
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
        margin-bottom: 1rem;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        padding: 0.75rem 1.25rem;
    }
    .table th {
        background-color: #f8f9fa;
        border-top: none;
        font-weight: 600;
        color: #495057;
    }
    .text-center h3, .text-center h4, .text-center h5 {
        margin-bottom: 0.25rem;
        font-weight: 600;
    }
    .pagination-sm .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }
    .pagination-sm .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    .pagination-sm .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
</style>

    <div class="content-wrapper-scroll">

                    <div class="content-wrapper">
                    <div class="row gutters">
                    <div class="container">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                       <div style="display: flex; justify-content: space-between; " class="col-xl-11 col-lg-11 col-md-11 col-sm-11 col-11">
                    <div style="align-items: center;" class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                    <h2>Thống kê 12 tháng gần nhất</h2>
                   <div style="background-color: white;" >

                    <canvas style="margin-top:19px ;" id="lineChart"></canvas>
                   </div>
                    </div>



                    <div style="margin-top: 10px; margin-left:10px" class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" >
                  <h2>Đơn hàng trong tuần này</h2>
                    <div style="background-color: white;" >

                    <canvas  id="barChart"></canvas>
                    </div>
                  </div>
                    </div>


    </div>

    <div  style="margin-top: 10px;margin-left:3px; display: flex;" class="row gutters col-xl-11 col-lg-11 col-md-11 col-sm-11 col-11" >
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
                            <th>Số đơn hoàn thành</th>
                            <th>Số đơn đã hủy</th>
                            <th>Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody id="revenueTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

   <div style="display: flex; justify-content: space-between;; " class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
   <div class="card col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table products-table">
                    <thead>
                        <tr>
                            <th>Top</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody id="revenueTableBody1">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table products-table">
                    <thead>
                        <tr>
                            <th>Top</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody id="revenueTableBody2">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

   </div>
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
            labels: ['Thành công', 'Chưa thanh toán','Hủy-Hoàn tiền'],
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
// Biến theo dõi trang hiện tại
let currentRecentPage = 1;
let currentDailyPage = 1;

function filterRevenue(page = 1) {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    if (!startDate || !endDate) {
        alert('Vui lòng chọn cả ngày bắt đầu và ngày kết thúc');
        return;
    }
    
    // Reset về trang 1 khi filter mới
    if (page === 1) {
        currentRecentPage = 1;
        currentDailyPage = 1;
    }
    document.getElementById('revenueTableBody').innerHTML = '<tr><td colspan="5" class="text-center">Đang tải...</td></tr>';
    document.getElementById('revenueTableBody1').innerHTML = '<tr><td colspan="4" class="text-center">Đang tải...</td></tr>';
    document.getElementById('revenueTableBody2').innerHTML = '<tr><td colspan="4" class="text-center">Đang tải...</td></tr>';
    document.getElementById('topProductsTableBody').innerHTML = '<tr><td colspan="3" class="text-center">Đang tải...</td></tr>';
    document.getElementById('couponStatsTableBody').innerHTML = '<tr><td colspan="4" class="text-center">Đang tải...</td></tr>';
    
    // Loading cho thống kê điểm thưởng
    document.getElementById('totalEarnedPoints').innerHTML = 'Đang tải...';
    document.getElementById('totalSpentPoints').innerHTML = 'Đang tải...';
    document.getElementById('usageRate').innerHTML = 'Đang tải...';
    document.getElementById('usersUsedPoints').innerHTML = 'Đang tải...';
    document.getElementById('topEarnersTableBody').innerHTML = '<tr><td colspan="4" class="text-center">Đang tải...</td></tr>';
    document.getElementById('topSpendersTableBody').innerHTML = '<tr><td colspan="4" class="text-center">Đang tải...</td></tr>';
    document.getElementById('transactionTypeTableBody').innerHTML = '<tr><td colspan="4" class="text-center">Đang tải...</td></tr>';
    document.getElementById('recentTransactionsTableBody').innerHTML = '<tr><td colspan="6" class="text-center">Đang tải...</td></tr>';
    document.getElementById('totalUsers').innerHTML = 'Đang tải...';
    document.getElementById('totalCurrentPoints').innerHTML = 'Đang tải...';
    document.getElementById('avgPointsPerUser').innerHTML = 'Đang tải...';
    document.getElementById('usersWithPoints').innerHTML = 'Đang tải...';
    document.getElementById('totalTransactions').innerHTML = 'Đang tải...';
    document.getElementById('avgPointsPerTransaction').innerHTML = 'Đang tải...';
    document.getElementById('pointsPerUserAvg').innerHTML = 'Đang tải...';
    document.getElementById('spendPerUserAvg').innerHTML = 'Đang tải...';
    document.getElementById('dailyStatsTableBody').innerHTML = '<tr><td colspan="4" class="text-center">Đang tải...</td></tr>';
    fetch('/admin/revenue/filter', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            start_date: startDate,
            end_date: endDate,
            recent_page: currentRecentPage,
            daily_page: currentDailyPage
        })
    })
    .then(response => response.json())
    .then(data => {
        // Handle Revenue Table
        let revenueHtml = '';
        if (data.revenueData && data.revenueData.length > 0) {
            data.revenueData.forEach(item => {
                revenueHtml += `
                    <tr>
                        <td>${item.date}</td>
                        <td>${item.total_orders}</td>
                        <td>${item.completed_count}</td>
                        <td>${item.cancelled_count}</td>
                        <td>${new Intl.NumberFormat('vi-VN').format(item.revenue)} đ</td>
                    </tr>
                `;
            });
        } else {
            revenueHtml = '<tr><td colspan="5" class="text-center">Không có dữ liệu</td></tr>';
        }
        document.getElementById('revenueTableBody').innerHTML = revenueHtml;

        // Handle Top Staff Table
        let staffHtml = '';
        if (data.topStaff && data.topStaff.user) {
            staffHtml = `<tr>
                <td>1</td>
                <td>${data.topStaff.user.name || 'Không rõ'}</td>
                <td>${data.topStaff.user.email || ''}</td>
                <td>${new Intl.NumberFormat('vi-VN').format(data.topStaff.total_revenue || 0)} đ</td>
            </tr>`;
        } else {
            staffHtml = '<tr><td colspan="4" class="text-center">Không có dữ liệu nhân viên</td></tr>';
        }
        document.getElementById('revenueTableBody1').innerHTML = staffHtml;

        // Handle Top Customer Table
        let customerHtml = '';
        if (data.topCustomer && data.topCustomer.user) {
            customerHtml = `<tr>
                <td>1</td>
                <td>${data.topCustomer.user.name || 'Không rõ'}</td>
                <td>${data.topCustomer.user.email || ''}</td>
                <td>${new Intl.NumberFormat('vi-VN').format(data.topCustomer.total_spent || 0)} đ</td>
            </tr>`;
        } else {
            customerHtml = '<tr><td colspan="4" class="text-center">Không có dữ liệu khách hàng</td></tr>';
        }
        document.getElementById('revenueTableBody2').innerHTML = customerHtml;

        // Top sản phẩm bán chạy
        let topProductsHtml = '';
        if (data.topProducts && data.topProducts.length > 0) {
            data.topProducts.forEach((item, idx) => {
                topProductsHtml += `<tr>
                    <td>${idx + 1}</td>
                    <td>${item.product ? item.product.name : 'Không rõ'}</td>
                    <td>${item.total_sold}</td>
                </tr>`;
            });
        } else {
            topProductsHtml = '<tr><td colspan="3" class="text-center">Không có dữ liệu</td></tr>';
        }
        document.getElementById('topProductsTableBody').innerHTML = topProductsHtml;

        // Thống kê mã giảm giá
        let couponStatsHtml = '';
        if (data.couponStats && data.couponStats.length > 0) {
            data.couponStats.forEach(item => {
                couponStatsHtml += `<tr>
                    <td>${item.code}</td>
                    <td>${item.used_count}</td>
                    <td>${new Intl.NumberFormat('vi-VN').format(item.total_discount)} đ</td>
                    <td>${new Intl.NumberFormat('vi-VN').format(item.total_revenue)} đ</td>
                </tr>`;
            });
        } else {
            couponStatsHtml = '<tr><td colspan="4" class="text-center">Không có dữ liệu</td></tr>';
        }
        document.getElementById('couponStatsTableBody').innerHTML = couponStatsHtml;

        // Thống kê điểm thưởng
        let pointStatsHtml = '';
        if (data.pointStats) {
            pointStatsHtml = `<tr>
                <td>${new Intl.NumberFormat('vi-VN').format(data.pointStats.total_earned)}</td>
                <td>${new Intl.NumberFormat('vi-VN').format(data.pointStats.total_spent)}</td>
                <td>${data.pointStats.users_used_points}</td>
            </tr>`;
        } else {
            pointStatsHtml = '<tr><td colspan="3" class="text-center">Không có dữ liệu</td></tr>';
        }
        // Thống kê điểm thưởng chi tiết
        if (data.pointsEfficiency) {
            const pointsData = data.pointsEfficiency;
            
            // Cập nhật tổng quan
            document.getElementById('totalEarnedPoints').innerHTML = new Intl.NumberFormat('vi-VN').format(pointsData.total_earned || 0);
            document.getElementById('totalSpentPoints').innerHTML = new Intl.NumberFormat('vi-VN').format(pointsData.total_spent || 0);
            document.getElementById('usageRate').innerHTML = (pointsData.usage_rate || 0) + '%';
            document.getElementById('usersUsedPoints').innerHTML = pointsData.users_used || 0;
            
            // Top người tích điểm
            let topEarnersHtml = '';
            if (pointsData.top_earners && pointsData.top_earners.length > 0) {
                pointsData.top_earners.forEach((item, idx) => {
                    topEarnersHtml += `<tr>
                        <td>${idx + 1}</td>
                        <td>${item.user ? item.user.name : 'Không rõ'}</td>
                        <td>${item.user ? item.user.email : ''}</td>
                        <td>${new Intl.NumberFormat('vi-VN').format(item.total_earned)}</td>
                    </tr>`;
                });
            } else {
                topEarnersHtml = '<tr><td colspan="4" class="text-center">Không có dữ liệu</td></tr>';
            }
            document.getElementById('topEarnersTableBody').innerHTML = topEarnersHtml;
            
            // Top người sử dụng điểm
            let topSpendersHtml = '';
            if (pointsData.top_spenders && pointsData.top_spenders.length > 0) {
                pointsData.top_spenders.forEach((item, idx) => {
                    topSpendersHtml += `<tr>
                        <td>${idx + 1}</td>
                        <td>${item.user ? item.user.name : 'Không rõ'}</td>
                        <td>${item.user ? item.user.email : ''}</td>
                        <td>${new Intl.NumberFormat('vi-VN').format(item.total_spent)}</td>
                    </tr>`;
                });
            } else {
                topSpendersHtml = '<tr><td colspan="4" class="text-center">Không có dữ liệu</td></tr>';
            }
            document.getElementById('topSpendersTableBody').innerHTML = topSpendersHtml;
            
            // Thống kê theo loại giao dịch
            let transactionTypeHtml = '';
            if (pointsData.type_stats && pointsData.type_stats.length > 0) {
                const typeLabels = {
                    'earn': 'Tích điểm',
                    'spend': 'Sử dụng điểm',
                    'adjust': 'Điều chỉnh',
                    'expire': 'Hết hạn'
                };
                pointsData.type_stats.forEach(item => {
                    transactionTypeHtml += `<tr>
                        <td>${typeLabels[item.type] || item.type}</td>
                        <td>${item.transaction_count}</td>
                        <td>${new Intl.NumberFormat('vi-VN').format(item.total_points)}</td>
                        <td>${new Intl.NumberFormat('vi-VN').format(Math.round(item.avg_points))}</td>
                    </tr>`;
                });
            } else {
                transactionTypeHtml = '<tr><td colspan="4" class="text-center">Không có dữ liệu</td></tr>';
            }
            document.getElementById('transactionTypeTableBody').innerHTML = transactionTypeHtml;
            
            // Giao dịch gần đây (có phân trang)
            let recentTransactionsHtml = '';
            if (pointsData.recent_transactions && pointsData.recent_transactions.data && pointsData.recent_transactions.data.length > 0) {
                const typeLabels = {
                    'earn': 'Tích điểm',
                    'spend': 'Sử dụng điểm',
                    'adjust': 'Điều chỉnh',
                    'expire': 'Hết hạn'
                };
                pointsData.recent_transactions.data.forEach(item => {
                    const date = new Date(item.created_at).toLocaleDateString('vi-VN');
                    recentTransactionsHtml += `<tr>
                        <td>${date}</td>
                        <td>${item.user ? item.user.name : 'Không rõ'}</td>
                        <td>${typeLabels[item.type] || item.type}</td>
                        <td>${item.points > 0 ? '+' : ''}${new Intl.NumberFormat('vi-VN').format(item.points)}</td>
                        <td>${item.description || '-'}</td>
                        <td>${item.order_id ? '#' + item.order_id : '-'}</td>
                    </tr>`;
                });
            } else {
                recentTransactionsHtml = '<tr><td colspan="6" class="text-center">Không có dữ liệu</td></tr>';
            }
            document.getElementById('recentTransactionsTableBody').innerHTML = recentTransactionsHtml;
            
            // Phân trang cho giao dịch gần đây
            if (pointsData.recent_transactions && pointsData.recent_transactions.last_page > 1) {
                document.getElementById('recentTransactionsPagination').innerHTML = generatePagination(
                    pointsData.recent_transactions.current_page,
                    pointsData.recent_transactions.last_page,
                    'recent'
                );
            } else {
                document.getElementById('recentTransactionsPagination').innerHTML = '';
            }
            
            // Thống kê điểm hiện tại
            if (pointsData.current_stats) {
                document.getElementById('totalUsers').innerHTML = new Intl.NumberFormat('vi-VN').format(pointsData.current_stats.total_users || 0);
                document.getElementById('totalCurrentPoints').innerHTML = new Intl.NumberFormat('vi-VN').format(pointsData.current_stats.total_current_points || 0);
                document.getElementById('avgPointsPerUser').innerHTML = new Intl.NumberFormat('vi-VN').format(Math.round(pointsData.current_stats.avg_points_per_user || 0));
                document.getElementById('usersWithPoints').innerHTML = new Intl.NumberFormat('vi-VN').format(pointsData.current_stats.users_with_points || 0);
            }
            
            // Thống kê bổ sung
            document.getElementById('totalTransactions').innerHTML = new Intl.NumberFormat('vi-VN').format(pointsData.total_transactions || 0);
            document.getElementById('avgPointsPerTransaction').innerHTML = new Intl.NumberFormat('vi-VN').format(Math.round(pointsData.avg_points_per_transaction || 0));
            document.getElementById('pointsPerUserAvg').innerHTML = new Intl.NumberFormat('vi-VN').format(pointsData.points_per_user_avg || 0);
            document.getElementById('spendPerUserAvg').innerHTML = new Intl.NumberFormat('vi-VN').format(pointsData.spend_per_user_avg || 0);
            
            // Thống kê theo ngày (có phân trang)
            let dailyStatsHtml = '';
            if (pointsData.daily_stats && pointsData.daily_stats.data && pointsData.daily_stats.data.length > 0) {
                pointsData.daily_stats.data.forEach(item => {
                    const date = new Date(item.date).toLocaleDateString('vi-VN');
                    const totalTransactions = (item.earn_transactions || 0) + (item.spend_transactions || 0);
                    dailyStatsHtml += `<tr>
                        <td>${date}</td>
                        <td>${new Intl.NumberFormat('vi-VN').format(item.earned || 0)}</td>
                        <td>${new Intl.NumberFormat('vi-VN').format(item.spent || 0)}</td>
                        <td>${totalTransactions}</td>
                    </tr>`;
                });
            } else {
                dailyStatsHtml = '<tr><td colspan="4" class="text-center">Không có dữ liệu</td></tr>';
            }
            document.getElementById('dailyStatsTableBody').innerHTML = dailyStatsHtml;
            
            // Phân trang cho thống kê theo ngày
            if (pointsData.daily_stats && pointsData.daily_stats.last_page > 1) {
                document.getElementById('dailyStatsPagination').innerHTML = generatePagination(
                    pointsData.daily_stats.current_page,
                    pointsData.daily_stats.last_page,
                    'daily'
                );
            } else {
                document.getElementById('dailyStatsPagination').innerHTML = '';
            }
        } else {
            // Fallback cho dữ liệu cũ
            let pointStatsHtml = '';
            if (data.pointStats) {
                pointStatsHtml = `<tr>
                    <td>${new Intl.NumberFormat('vi-VN').format(data.pointStats.total_earned)}</td>
                    <td>${new Intl.NumberFormat('vi-VN').format(data.pointStats.total_spent)}</td>
                    <td>${data.pointStats.users_used_points}</td>
                </tr>`;
            } else {
                pointStatsHtml = '<tr><td colspan="3" class="text-center">Không có dữ liệu</td></tr>';
            }
            document.getElementById('pointStatsTableBody').innerHTML = pointStatsHtml;
        }
    })
    .catch(error => {
        document.getElementById('revenueTableBody').innerHTML = '<tr><td colspan="5" class="text-center">Có lỗi xảy ra khi tải dữ liệu</td></tr>';
        document.getElementById('revenueTableBody1').innerHTML = '<tr><td colspan="4" class="text-center">Có lỗi xảy ra khi tải dữ liệu</td></tr>';
        document.getElementById('revenueTableBody2').innerHTML = '<tr><td colspan="4" class="text-center">Có lỗi xảy ra khi tải dữ liệu</td></tr>';
        document.getElementById('topProductsTableBody').innerHTML = '<tr><td colspan="3" class="text-center">Có lỗi xảy ra khi tải dữ liệu</td></tr>';
        document.getElementById('couponStatsTableBody').innerHTML = '<tr><td colspan="4" class="text-center">Có lỗi xảy ra khi tải dữ liệu</td></tr>';
        
        // Error cho thống kê điểm thưởng
        document.getElementById('totalEarnedPoints').innerHTML = 'Lỗi';
        document.getElementById('totalSpentPoints').innerHTML = 'Lỗi';
        document.getElementById('usageRate').innerHTML = 'Lỗi';
        document.getElementById('usersUsedPoints').innerHTML = 'Lỗi';
        document.getElementById('topEarnersTableBody').innerHTML = '<tr><td colspan="4" class="text-center">Có lỗi xảy ra khi tải dữ liệu</td></tr>';
        document.getElementById('topSpendersTableBody').innerHTML = '<tr><td colspan="4" class="text-center">Có lỗi xảy ra khi tải dữ liệu</td></tr>';
        document.getElementById('transactionTypeTableBody').innerHTML = '<tr><td colspan="4" class="text-center">Có lỗi xảy ra khi tải dữ liệu</td></tr>';
        document.getElementById('recentTransactionsTableBody').innerHTML = '<tr><td colspan="6" class="text-center">Có lỗi xảy ra khi tải dữ liệu</td></tr>';
        document.getElementById('totalUsers').innerHTML = 'Lỗi';
        document.getElementById('totalCurrentPoints').innerHTML = 'Lỗi';
        document.getElementById('avgPointsPerUser').innerHTML = 'Lỗi';
        document.getElementById('usersWithPoints').innerHTML = 'Lỗi';
        document.getElementById('totalTransactions').innerHTML = 'Lỗi';
        document.getElementById('avgPointsPerTransaction').innerHTML = 'Lỗi';
        document.getElementById('pointsPerUserAvg').innerHTML = 'Lỗi';
        document.getElementById('spendPerUserAvg').innerHTML = 'Lỗi';
        document.getElementById('dailyStatsTableBody').innerHTML = '<tr><td colspan="4" class="text-center">Có lỗi xảy ra khi tải dữ liệu</td></tr>';
    });
}

// Hàm tạo phân trang
function generatePagination(currentPage, lastPage, type) {
    let paginationHtml = '<nav><ul class="pagination pagination-sm">';
    
    // Nút Previous
    if (currentPage > 1) {
        paginationHtml += `<li class="page-item">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1}, '${type}')">Trước</a>
        </li>`;
    } else {
        paginationHtml += '<li class="page-item disabled"><span class="page-link">Trước</span></li>';
    }
    
    // Các trang
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(lastPage, currentPage + 2);
    
    for (let i = startPage; i <= endPage; i++) {
        if (i === currentPage) {
            paginationHtml += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
        } else {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" onclick="changePage(${i}, '${type}')">${i}</a>
            </li>`;
        }
    }
    
    // Nút Next
    if (currentPage < lastPage) {
        paginationHtml += `<li class="page-item">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1}, '${type}')">Sau</a>
        </li>`;
    } else {
        paginationHtml += '<li class="page-item disabled"><span class="page-link">Sau</span></li>';
    }
    
    paginationHtml += '</ul></nav>';
    return paginationHtml;
}

// Hàm thay đổi trang
function changePage(page, type) {
    if (type === 'recent') {
        currentRecentPage = page;
    } else if (type === 'daily') {
        currentDailyPage = page;
    }
    filterRevenue();
}
</script>

<!-- Top sản phẩm bán chạy -->
<h2 style="margin-top: 30px;">Top sản phẩm bán chạy</h2>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table products-table">
                <thead>
                    <tr>
                        <th>Top</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng bán</th>
                    </tr>
                </thead>
                <tbody id="topProductsTableBody"></tbody>
            </table>
        </div>
    </div>
</div>
<!-- Thống kê mã giảm giá -->
<h2 style="margin-top: 30px;">Thống kê mã giảm giá</h2>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table products-table">
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Số lần dùng</th>
                        <th>Tổng giảm giá</th>
                        <th>Doanh thu từ mã</th>
                    </tr>
                </thead>
                <tbody id="couponStatsTableBody"></tbody>
            </table>
        </div>
    </div>
</div>
<!-- Thống kê điểm thưởng chi tiết -->
<h2 style="margin-top: 30px;">Thống kê điểm thưởng chi tiết</h2>

<!-- Tổng quan điểm thưởng -->
<div class="row gutters">
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Tổng điểm phát</h5>
                <h3 id="totalEarnedPoints" class="text-primary">0</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Tổng điểm dùng</h5>
                <h3 id="totalSpentPoints" class="text-success">0</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Tỷ lệ sử dụng</h5>
                <h3 id="usageRate" class="text-info">0%</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Khách dùng điểm</h5>
                <h3 id="usersUsedPoints" class="text-warning">0</h3>
            </div>
        </div>
    </div>
</div>

<!-- Thống kê chi tiết -->
<div class="row gutters">
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-header">
                <h5>Top người tích điểm nhiều nhất</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Top</th>
                                <th>Tên khách hàng</th>
                                <th>Email</th>
                                <th>Điểm tích</th>
                            </tr>
                        </thead>
                        <tbody id="topEarnersTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-header">
                <h5>Top người sử dụng điểm nhiều nhất</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Top</th>
                                <th>Tên khách hàng</th>
                                <th>Email</th>
                                <th>Điểm dùng</th>
                            </tr>
                        </thead>
                        <tbody id="topSpendersTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thống kê theo loại giao dịch -->
<div class="card">
    <div class="card-header">
        <h5>Thống kê theo loại giao dịch</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Loại giao dịch</th>
                        <th>Số lượng giao dịch</th>
                        <th>Tổng điểm</th>
                        <th>Điểm trung bình</th>
                    </tr>
                </thead>
                <tbody id="transactionTypeTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Giao dịch điểm gần đây -->
<div class="card">
    <div class="card-header">
        <h5>Giao dịch điểm gần đây</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Thời gian</th>
                        <th>Khách hàng</th>
                        <th>Loại</th>
                        <th>Điểm</th>
                        <th>Mô tả</th>
                        <th>Đơn hàng</th>
                    </tr>
                </thead>
                <tbody id="recentTransactionsTableBody"></tbody>
            </table>
        </div>
        <!-- Phân trang cho giao dịch gần đây -->
        <div id="recentTransactionsPagination" class="d-flex justify-content-center mt-3"></div>
    </div>
</div>

<!-- Thống kê điểm hiện tại -->
<div class="card">
    <div class="card-header">
        <h5>Thống kê điểm hiện tại</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <div class="text-center">
                    <h4 id="totalUsers">0</h4>
                    <p class="text-muted">Tổng khách hàng</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="text-center">
                    <h4 id="totalCurrentPoints">0</h4>
                    <p class="text-muted">Tổng điểm hiện tại</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="text-center">
                    <h4 id="avgPointsPerUser">0</h4>
                    <p class="text-muted">Điểm trung bình/khách</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="text-center">
                    <h4 id="usersWithPoints">0</h4>
                    <p class="text-muted">Khách có điểm</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="text-center">
                    <h4 id="totalTransactions">0</h4>
                    <p class="text-muted">Tổng giao dịch</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="text-center">
                    <h4 id="avgPointsPerTransaction">0</h4>
                    <p class="text-muted">Điểm trung bình/giao dịch</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thống kê bổ sung -->
<div class="row gutters">
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-header">
                <h5>Thống kê theo ngày</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Điểm tích</th>
                                <th>Điểm dùng</th>
                                <th>Giao dịch</th>
                            </tr>
                        </thead>
                        <tbody id="dailyStatsTableBody"></tbody>
                    </table>
                </div>
                <!-- Phân trang cho thống kê theo ngày -->
                <div id="dailyStatsPagination" class="d-flex justify-content-center mt-3"></div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-header">
                <h5>Thống kê trung bình</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="text-center">
                            <h5 id="pointsPerUserAvg">0</h5>
                            <p class="text-muted">Điểm tích/khách</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center">
                            <h5 id="spendPerUserAvg">0</h5>
                            <p class="text-muted">Điểm dùng/khách</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

                    </div>
                </div>

            @include('footer')

            </div>



