<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Bartender Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="{{ url('assetstaff') }}/css/vendor.min.css" rel="stylesheet" />
    <link href="{{ url('assetstaff') }}/css/app.min.css" rel="stylesheet" />
    <!-- ================== END core-css ================== -->

    <style>
        .status-changed {
            animation: highlight 2s;
        }

        @keyframes highlight {
            0% { background-color: transparent; }
            30% { background-color: #ffc107; }
            100% { background-color: transparent; }
        }

        .menu-text small:last-child {
            display: block;
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
        }

        .menu-text small:last-child:empty::after {
            content: 'pending';
            color: #6c757d;
        }

        .menu-text small.pending {
            color: #6c757d;
        }

        .menu-text small.processing {
            color: #007bff;
        }

        .menu-text small.completed {
            color: #28a745;
        }

        .menu-text small.canceled {
            color: #dc3545;
        }
    </style>
</head>
<body>
<!-- BEGIN #app -->
<div id="app" class="app">
    <!-- BEGIN #header -->
    <div id="header" class="app-header">
        <!-- BEGIN navbar-header -->
        <div class="navbar-header">
            <a href="{{ route('bartender.index') }}" class="navbar-brand">
                <span class="navbar-logo"></span>
                <b>Bartender</b> Dashboard
            </a>
            <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- END navbar-header -->
        <!-- BEGIN header-nav -->
        <div class="navbar-nav">
            <div class="navbar-item navbar-user dropdown">
                <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <span>
                        <span class="d-none d-md-inline">{{ Auth::guard('staff')->user()->name ?? 'Bartender' }}</span>
                        <b class="caret"></b>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end me-1">
                    <a href="{{ route('staff.logout') }}" class="dropdown-item">Log Out</a>
                </div>
            </div>
        </div>
        <!-- END header-nav -->
    </div>
    <!-- END #header -->

    <!-- BEGIN #sidebar -->
    <div id="sidebar" class="app-sidebar">
        <!-- BEGIN scrollbar -->
        <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
            <!-- BEGIN menu -->
            <div class="menu">
                <div class="menu-header">Navigation</div>
                <div class="menu-item active">
                    <a href="{{ route('bartender.index') }}" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-coffee"></i>
                        </div>
                        <div class="menu-text">Dashboard</div>
                    </a>
                </div>
                <!-- Add more menu items as needed -->
                <div class="menu-header">Đơn hàng</div>
                @foreach($donhangs as $donhang)
                <div class="menu-item">
                    <button class="btn-edit-order" data-id="{{ $donhang->id }}" style="background:none;border:none;cursor:pointer;">
                        <div class="menu-icon">
                            <i class="fa fa-receipt"></i>
                        </div>
                        <div class="menu-text">
                            <div>{{ $donhang->name }}</div>
                            <small>{{ number_format($donhang->total, 0) }} đ</small>
                            <small class="{{ $donhang->status ?? 'pending' }}">{{ $donhang->status }}</small>
                        </div>
                    </button>
                </div>
                @endforeach
            </div>
            <!-- END menu -->
        </div>
        <!-- END scrollbar -->
    </div>
    <!-- END #sidebar -->

    <!-- BEGIN #content -->
    <div id="content" class="app-content">
        <!-- Flash Message Display -->
        @if(session('message'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Thông báo!</strong> {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            alert("{{ session('message') }}");
        </script>
        @endif

        <h1 class="page-header">Bartender Dashboard</h1>

      <div id="order-detail">
        <div class="order-detail-container">
            <div class="order-detail-header">
                <h2>Chi tiết đơn hàng</h2>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Size</th>
                        <th>Topping</th>
                        <th>Số lượng</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="order-details-body">
                    <!-- Order details will be loaded here dynamically -->
                </tbody>
            </table>
        </div>
      </div>
    </div>
    <!-- END #content -->

    <!-- BEGIN scroll-top-btn -->
    <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top">
        <i class="fa fa-angle-up"></i>
    </a>
    <!-- END scroll-top-btn -->
</div>
<!-- END #app -->

<!-- ================== BEGIN core-js ================== -->
<script src="{{ url('assetstaff') }}/js/vendor.min.js"></script>
<script src="{{ url('assetstaff') }}/js/app.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-edit-order').forEach(function(btnEdit) {
        btnEdit.addEventListener('click', function() {
            const orderId = this.dataset.id;

            // Fetch order details via AJAX
            fetch(`{{ url('bartender/get-order-details') }}/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    // Debug the response
                    console.log('API Response:', data);

                    // Clear previous order details
                    const tableBody = document.getElementById('order-details-body');
                    tableBody.innerHTML = '';

                    // Store the current order ID in a data attribute for later use
                    tableBody.dataset.currentOrderId = orderId;

                    // Add each order detail to the table
                    data.forEach(detail => {
                        // Debug each detail
                        console.log('Detail:', detail);
                        const row = document.createElement('tr');

                        // Create product name cell
                        const productNameCell = document.createElement('td');
                        productNameCell.textContent = detail.product_name;
                        row.appendChild(productNameCell);

                        // Create size ID cell
                        const sizeCell = document.createElement('td');
                        sizeCell.textContent = detail.size_name || 'N/A';
                        row.appendChild(sizeCell);

                        // Create topping ID cell
                        const toppingCell = document.createElement('td');
                        toppingCell.textContent = detail.topping_name || 'N/A';
                        row.appendChild(toppingCell);

                        // Create quantity cell
                        const quantityCell = document.createElement('td');
                        quantityCell.textContent = detail.quantity;
                        row.appendChild(quantityCell);

                        // Create status cell
                        const statusCell = document.createElement('td');
                        statusCell.textContent = detail.status || 'pending';
                        row.appendChild(statusCell);

                        // Create action cell with status update form
                        const actionCell = document.createElement('td');

                        // Create form for updating status
                        const form = document.createElement('form');
                        form.action = `{{ url('bartender/order-detail') }}/${detail.id}/update-status`;
                        form.method = 'POST';

                        // Add CSRF token
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        // Create input group div
                        const inputGroup = document.createElement('div');
                        inputGroup.className = 'input-group';

                        // Create select element for status
                        const select = document.createElement('select');
                        select.className = 'form-select form-select-sm';
                        select.name = 'status';

                        // Add options for status
                        const statusOptions = ['pending', 'processing', 'completed', 'canceled'];
                        const statusOrder = {
                            'pending': 0,
                            'processing': 1,
                            'completed': 2,
                            'canceled': 3
                        };

                        const currentStatusOrder = statusOrder[detail.status || 'pending'];

                        statusOptions.forEach(status => {
                            const option = document.createElement('option');
                            option.value = status;
                            option.textContent = status === 'pending' ? 'Chờ xử lý' :
                                                status === 'processing' ? 'Đang xử lý' :
                                                status === 'completed' ? 'Hoàn thành' : 'Đã hủy';

                            // Disable options that represent earlier stages than the current status
                            if (statusOrder[status] < currentStatusOrder) {
                                option.disabled = true;
                            }

                            if (status === detail.status) {
                                option.selected = true;
                            }
                            select.appendChild(option);
                        });

                        // Create submit button
                        const button = document.createElement('button');
                        button.type = 'submit';
                        button.className = 'btn btn-sm btn-primary';
                        button.textContent = 'Cập nhật';

                        // Add event listener to form submission
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();

                            // Get the selected status
                            const newStatus = select.value;

                            // Create FormData object
                            const formData = new FormData(this);

                            // Send AJAX request to update order detail status
                            fetch(this.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update the status cell
                                    statusCell.textContent = newStatus;

                                    // Update order status based on all order details
                                    updateParentOrderStatus(orderId);
                                } else {
                                    alert('Failed to update status');
                                }
                            })
                            .catch(error => {
                                console.error('Error updating status:', error);
                                alert('Error updating status');
                            });
                        });

                        // Append select and button to input group
                        inputGroup.appendChild(select);
                        inputGroup.appendChild(button);

                        // Append input group to form
                        form.appendChild(inputGroup);

                        // Append form to action cell
                        actionCell.appendChild(form);

                        // Append action cell to row
                        row.appendChild(actionCell);

                        // Append row to table body
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error fetching order details:', error);
                });
        });
    });

    // Function to update parent order status based on its order details
    function updateParentOrderStatus(orderId) {
        // If orderId is not provided, try to get it from the table body data attribute
        if (!orderId) {
            const tableBody = document.getElementById('order-details-body');
            orderId = tableBody.dataset.currentOrderId;

            // If still no orderId, return
            if (!orderId) {
                console.error('No order ID found for status update');
                return;
            }
        }

        // Fetch all order details for this order
        fetch(`{{ url('bartender/get-order-details') }}/${orderId}`)
            .then(response => response.json())
            .then(details => {
                let hasProcessing = false;
                let hasPendingOrProcessing = false;

                // Check the status of all order details
                details.forEach(detail => {
                    if (detail.status === 'processing') {
                        hasProcessing = true;
                        hasPendingOrProcessing = true;
                    }
                    if (detail.status === 'pending') {
                        hasPendingOrProcessing = true;
                    }
                });

                let newOrderStatus = null;

                // If any detail is processing and order is pending, update order to processing
                if (hasProcessing) {
                    newOrderStatus = 'processing';
                }

                // If no detail is pending or processing, update order to completed
                if (!hasPendingOrProcessing) {
                    newOrderStatus = 'completed';
                }

                // If status needs to be updated
                if (newOrderStatus) {
                    // Send request to update order status
                    fetch(`{{ url('bartender/update-order-status') }}/${orderId}`, {
                        method: 'POST',
                        body: JSON.stringify({ status: newOrderStatus }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log(`Order #${orderId} status updated to ${newOrderStatus}`);

                            // Update the status in the sidebar for this order
                            const sidebarOrderButton = document.querySelector(`.btn-edit-order[data-id="${orderId}"]`);
                            if (sidebarOrderButton) {
                                const statusElement = sidebarOrderButton.querySelector('.menu-text small:last-child');
                                if (statusElement) {
                                    statusElement.textContent = newOrderStatus;

                                    // Update the status class
                                    statusElement.className = '';
                                    statusElement.classList.add(newOrderStatus);

                                    // Add visual indication of status change
                                    statusElement.classList.add('status-changed');
                                    setTimeout(() => {
                                        statusElement.classList.remove('status-changed');
                                    }, 2000);
                                }
                            }
                        } else {
                            console.error('Failed to update order status');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating order status:', error);
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching order details for status update:', error);
            });
    }
});
</script>
</body>
</html>
