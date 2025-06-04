var options = {
	chart: {
		height: 250,
		type: 'radialBar',
		toolbar: {
			show: false,
		},
	},
	plotOptions: {
		radialBar: {
			dataLabels: {
				name: {
					fontSize: '12px',
					fontColor: 'black',
				},
				value: {
					fontSize: '21px',
				},
				total: {
					show: true,
					label: 'Tổng đơn',
					formatter: function (w) {
						return unpaid + paid + cancelled;
					}
				}
			},
			track: {
        show: true,
        margin: 7,
    	},
		}
	},
	series: [unpaid, paid, cancelled],
	labels: ['Chưa thanh toán', 'Đã thanh toán', 'Đã hủy'],
	colors: ['#f39c12', '#27ae60', '#e74c3c'],
}

var chart = new ApexCharts(
	document.querySelector("#ordersGraph"),
	options
);
chart.render();
