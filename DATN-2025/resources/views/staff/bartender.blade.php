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

        /* Status text styling */
        td:nth-child(5) {
            font-weight: bold;
        }

        td:nth-child(5):has(span.pending) {
            color: #6c757d;
        }

        td:nth-child(5):has(span.processing) {
            color: #007bff;
        }

        td:nth-child(5):has(span.completed) {
            color: #28a745;
        }

        td:nth-child(5):has(span.canceled) {
            color: #dc3545;
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

        /* Order styling */
        .menu-item button {
            width: 100%;
            text-align: left;
            padding: 10px 15px;
            border: 1px solid transparent;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .menu-item button:hover {
            background-color: rgba(0, 123, 255, 0.1);
            border-color: #007bff;
        }

        .menu-item button.active {
            background-color: rgba(0, 123, 255, 0.15);
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Order detail container styling */
        .order-detail-container {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .order-detail-header {
            padding-bottom: 10px;
            margin-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
        }

        /* Default view styling */
        .default-view {
            text-align: center;
            padding: 30px;
        }

        .default-view h3 {
            margin-bottom: 20px;
            color: #6c757d;
        }

        .order-count {
            font-size: 3rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 20px;
        }

        .order-count-details {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .count-item {
            padding: 15px;
            border-radius: 5px;
            min-width: 150px;
        }

        .count-item.pending {
            background-color: rgba(108, 117, 125, 0.1);
            border: 1px solid #6c757d;
        }

        .count-item.processing {
            background-color: rgba(0, 123, 255, 0.1);
            border: 1px solid #007bff;
        }

        .count-item.completed {
            background-color: rgba(40, 167, 69, 0.1);
            border: 1px solid #28a745;
        }
    </style>
</head>
<body>
<!-- BEGIN #app -->
<div id="app" class="app">
    <!-- BEGIN #header -->
    <div id="header" class="app-header">
        <!-- BEGIN navbar-header -->

        <!-- END navbar-header -->
        <!-- BEGIN header-nav -->
        <div style="margin-left: 100px;" class="navbar-nav">
            <div class="navbar-item navbar-user dropdown">
                <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <span>
                        <span class="d-none d-md-inline">{{ Auth::guard('staff')->user()->name ?? 'Bartender' }}</span>

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
                    <button class="btn-edit-order" data-id="{{ $donhang->id }}" style="background:none;border: 3px solid #dee2e6;cursor:pointer; margin-top:10px">
                        <div class="menu-icon">
                            <i class="fa fa-receipt"></i>
                        </div>
                        <div class="menu-text">
                            <div>{{ $donhang->name }}</div>
                            <small>{{ number_format($donhang->total, 0) }} đ</small>
                            <small class="{{ $donhang->status ?? 'pending' }}">@if($donhang->status == 'pending') Chờ xử lý @elseif($donhang->status == 'processing') Đang xử lý @elseif($donhang->status == 'completed') Hoàn thành @else Đã hủy @endif</small>
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
                <div id="order-customer-info" class="mt-2 text-muted"></div>
            </div>

            <!-- Default view when no order is selected -->
            <div id="default-view" class="default-view">
                <h3>Vui lòng chọn đơn hàng từ danh sách bên trái</h3>
                <div class="order-count">
                    <span id="incomplete-order-count">{{ $donhangs->where('status', '!=', 'completed')->where('status', '!=', 'canceled')->count() }}</span>
                </div>
                <h4>Số đơn hàng chưa hoàn thành hôm nay</h4>

                <div class="order-count-details">
                    <div class="count-item pending">
                        <h5>Chờ xử lý</h5>
                        <div class="count">{{ $donhangs->where('status', 'pending')->count() }}</div>
                    </div>
                    <div class="count-item processing">
                        <h5>Đang xử lý</h5>
                        <div class="count">{{ $donhangs->where('status', 'processing')->count() }}</div>
                    </div>
                    <div class="count-item completed">
                        <h5>Hoàn thành</h5>
                        <div class="count">{{ $donhangs->where('status', 'completed')->count() }}</div>
                    </div>
                </div>
            </div>

            <table id="order-details-table" class="table table-bordered" style="display: none;">
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

    <!-- END scroll-top-btn -->
</div>
<!-- END #app -->

<!-- ================== BEGIN core-js ================== -->
<script src="{{ url('assetstaff') }}/js/vendor.min.js"></script>
<script src="{{ url('assetstaff') }}/js/app.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let activeOrderButton = null;
    const defaultView = document.getElementById('default-view');
    const orderDetailsTable = document.getElementById('order-details-table');

    // Initially show default view
    defaultView.style.display = 'block';
    orderDetailsTable.style.display = 'none';

    document.querySelectorAll('.btn-edit-order').forEach(function(btnEdit) {
        btnEdit.addEventListener('click', function() {
            const orderId = this.dataset.id;

            // Remove active class from previously active button
            if (activeOrderButton) {
                activeOrderButton.classList.remove('active');
            }

            // Add active class to current button
            this.classList.add('active');
            activeOrderButton = this;

            // Hide default view and show order details table
            defaultView.style.display = 'none';
            orderDetailsTable.style.display = 'table';

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

                    // Update customer info if available
                    const customerInfoDiv = document.getElementById('order-customer-info');
                    if (data.length > 0 && data[0].order) {
                        const order = data[0].order;
                        customerInfoDiv.innerHTML = `
                            <strong>Khách hàng:</strong> ${order.name} |
                            <strong>SĐT:</strong> ${order.phone} |
                            <strong>Mã đơn:</strong> #${order.transaction_id}
                        `;
                    } else {
                        customerInfoDiv.innerHTML = '';
                    }

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

                        // Create status cell with span for styling
                        const statusCell = document.createElement('td');
                        const statusSpan = document.createElement('span');
                        statusSpan.classList.add(detail.status || 'pending');

                        if(detail.status == 'pending'){
                            statusSpan.textContent = 'Chờ xử lý';
                        }else if(detail.status == 'processing'){
                            statusSpan.textContent = 'Đang xử lý';
                        }else if(detail.status == 'completed'){
                            statusSpan.textContent = 'Hoàn thành';
                        }else if(detail.status == 'canceled'){
                            statusSpan.textContent = 'Đã hủy';
                        }

                        statusCell.appendChild(statusSpan);
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

                            // Enforce sequential status progression:
                            // - If current status is pending, only allow pending or processing
                            // - If current status is processing, only allow processing or completed
                            // - If current status is completed or canceled, only allow that status
                            if (
                                (detail.status === 'pending' && status !== 'pending' && status !== 'processing') ||
                                (detail.status === 'processing' && status !== 'processing' && status !== 'completed') ||
                                ((detail.status === 'completed' || detail.status === 'canceled') && status !== detail.status)
                            ) {
                                option.disabled = true;
                            }

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
                            const currentStatus = detail.status || 'pending';

                            // Validate sequential status progression
                            const validProgression = (
                                // Can stay at current status
                                newStatus === currentStatus ||
                                // Can go from pending to processing
                                (currentStatus === 'pending' && newStatus === 'processing') ||
                                // Can go from processing to completed
                                (currentStatus === 'processing' && newStatus === 'completed') ||
                                // Special case for canceled status
                                newStatus === 'canceled'
                            );

                            if (!validProgression) {
                                alert('Không thể cập nhật trạng thái. Phải tuân theo thứ tự: Chờ xử lý → Đang xử lý → Hoàn thành');
                                return;
                            }

                            // Create FormData object
                            const formData = new FormData(this);

                            // Disable the button during submission to prevent double-clicks
                            button.disabled = true;
                            button.textContent = 'Đang xử lý...';

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
                                    // Update the status cell with new text
                                    const statusSpan = statusCell.querySelector('span') || document.createElement('span');

                                    // Remove all status classes and add the new one
                                    statusSpan.classList.remove('pending', 'processing', 'completed', 'canceled');
                                    statusSpan.classList.add(newStatus);

                                    if(newStatus === 'pending') {
                                        statusSpan.textContent = 'Chờ xử lý';
                                    } else if(newStatus === 'processing') {
                                        statusSpan.textContent = 'Đang xử lý';
                                    } else if(newStatus === 'completed') {
                                        statusSpan.textContent = 'Hoàn thành';
                                    } else if(newStatus === 'canceled') {
                                        statusSpan.textContent = 'Đã hủy';
                                    }

                                    // Make sure the span is in the cell
                                    if (!statusCell.contains(statusSpan)) {
                                        statusCell.innerHTML = '';
                                        statusCell.appendChild(statusSpan);
                                    }

                                    // Update the detail object's status for future reference
                                    detail.status = newStatus;

                                    // Update select options based on new status
                                    Array.from(select.options).forEach(option => {
                                        const optionStatus = option.value;

                                        // Reset disabled state
                                        option.disabled = false;

                                        // Apply sequential progression rules
                                        if (
                                            (newStatus === 'pending' && optionStatus !== 'pending' && optionStatus !== 'processing') ||
                                            (newStatus === 'processing' && optionStatus !== 'processing' && optionStatus !== 'completed') ||
                                            ((newStatus === 'completed' || newStatus === 'canceled') && optionStatus !== newStatus)
                                        ) {
                                            option.disabled = true;
                                        }

                                        // Disable options that represent earlier stages
                                        if (statusOrder[optionStatus] < statusOrder[newStatus]) {
                                            option.disabled = true;
                                        }

                                        // Set selected option
                                        if (optionStatus === newStatus) {
                                            option.selected = true;
                                        }
                                    });

                                    // Update order status based on all order details
                                    updateParentOrderStatus(orderId);

                                    // Add visual indication of status change
                                    statusCell.classList.add('status-changed');
                                    setTimeout(() => {
                                        statusCell.classList.remove('status-changed');
                                    }, 2000);
                                } else {
                                    alert('Không thể cập nhật trạng thái: ' + (data.message || 'Lỗi không xác định'));
                                }
                            })
                            .catch(error => {
                                console.error('Error updating status:', error);
                                alert('Lỗi khi cập nhật trạng thái');
                            })
                            .finally(() => {
                                // Re-enable the button
                                button.disabled = false;
                                button.textContent = 'Cập nhật';
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
                                    // Update the status class
                                    statusElement.className = '';
                                    statusElement.classList.add(newOrderStatus);

                                    // Update text with Vietnamese translation
                                    if(newOrderStatus === 'pending') {
                                        statusElement.textContent = 'Chờ xử lý';
                                    } else if(newOrderStatus === 'processing') {
                                        statusElement.textContent = 'Đang xử lý';
                                    } else if(newOrderStatus === 'completed') {
                                        statusElement.textContent = 'Hoàn thành';
                                    } else if(newOrderStatus === 'canceled') {
                                        statusElement.textContent = 'Đã hủy';
                                    }

                                    // Add visual indication of status change
                                    statusElement.classList.add('status-changed');
                                    setTimeout(() => {
                                        statusElement.classList.remove('status-changed');
                                    }, 2000);
                                }
                            }

                            // Update the incomplete order count in the default view
                            updateIncompleteOrderCount();
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

    // Function to update the order counts
    function updateIncompleteOrderCount() {
        fetch(`{{ url('bartender/get-incomplete-order-count') }}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('incomplete-order-count').textContent = data.count;
                document.querySelector('.count-item.pending .count').textContent = data.pending;
                document.querySelector('.count-item.processing .count').textContent = data.processing;
                document.querySelector('.count-item.completed .count').textContent = data.completed;
            })
            .catch(error => {
                console.error('Error updating order counts:', error);
            });
    }
});
</script>
</body>
</html>
