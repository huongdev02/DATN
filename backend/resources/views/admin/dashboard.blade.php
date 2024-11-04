@extends('Layout.Layout')

@section('title')
    DashBoard Admin
@endsection

@section('content_admin')
    <div class="container text-center mt-5 mb-3">
        <h2>DashBoard Admin</h2>
    </div>

    <div class="container mt-4">
        <div class="row">
            <!-- User statistics -->
            <div class="col-md-6 d-flex align-items-stretch">
                <div class="w-100">
                    <h4>Thống kê người dùng mới</h4>
                    <div class="form-group">
                        <select id="timeframe-select-account" class="form-control" onchange="submitForm(this)">
                            <option value="today" {{ request('timeframe') === 'today' ? 'selected' : '' }}>Hôm nay</option>
                            <option value="this_week" {{ request('timeframe') === 'this_week' ? 'selected' : '' }}>Tuần này
                            </option>
                            <option value="this_month" {{ request('timeframe') === 'this_month' ? 'selected' : '' }}>Tháng
                                này</option>
                            <option value="this_quarter" {{ request('timeframe') === 'this_quarter' ? 'selected' : '' }}>Quý
                                này</option>
                        </select>
                    </div>

                    <div id="statistics" class="mt-3">
                        <div class="card text-white bg-primary h-100">
                            <div class="card-body">
                                <h5 class="card-title">Số lượng người dùng mới</h5>
                                <p class="card-text" id="userCount">{{ $account['count'] }} người dùng mới</p>
                                <p class="card-text" id="changeIndicator">
                                    So với kỳ trước:
                                    <span id="changeValue" class="font-weight-bold">{{ $account['change'] }}</span>
                                    @if ($account['change'] > 0)
                                        <i class="fas fa-arrow-up" style="color: green;"></i>
                                    @elseif($account['change'] < 0)
                                        <i class="fas fa-arrow-down" style="color: red;"></i>
                                    @else
                                        <i class="fas fa-arrow-right" style="color: gray;"></i>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue and order statistics -->
            <div class="col-md-6 d-flex align-items-stretch">
                <div class="w-100">
                    <h4>Thống kê doanh thu và đơn hàng hoàn thành</h4>
                    <div class="form-group">
                        <select id="timeframe-select-revenue" class="form-control" onchange="submitForm(this)">
                            <option value="today" {{ request('timeframe') === 'today' ? 'selected' : '' }}>Hôm nay</option>
                            <option value="this_week" {{ request('timeframe') === 'this_week' ? 'selected' : '' }}>Tuần này
                            </option>
                            <option value="this_month" {{ request('timeframe') === 'this_month' ? 'selected' : '' }}>Tháng
                                này</option>
                            <option value="this_quarter" {{ request('timeframe') === 'this_quarter' ? 'selected' : '' }}>Quý
                                này</option>
                        </select>
                    </div>

                    <div id="revenueStatistics" class="mt-3">
                        <div class="card text-white bg-success h-100">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <h5 class="card-title">Tổng Doanh Thu</h5>
                                        <p class="card-text" id="totalRevenue">{{ $order['total_amount'] }} VNĐ</p>
                                        <p class="card-text" id="revenueChangeIndicator">
                                            So với kỳ trước:
                                            <span id="revenueChangeValue"
                                                class="font-weight-bold">{{ $order['change'] }}</span>
                                            @if ($order['change'] > 0)
                                                <i class="fas fa-arrow-up" style="color: green;"></i>
                                            @elseif($order['change'] < 0)
                                                <i class="fas fa-arrow-down" style="color: red;"></i>
                                            @else
                                                <i class="fas fa-arrow-right" style="color: gray;"></i>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="card-title">Số lượng đơn hoàn thành</h5>
                                        <p class="card-text" id="completedOrders">{{ $order['order_count'] }} đơn</p>
                                        <p class="card-text" id="orderChangeIndicator">
                                            So với kỳ trước:
                                            <span id="orderChangeValue"
                                                class="font-weight-bold">{{ $order['order_count'] - $order['lastOrderCount'] }}</span>
                                            @if ($order['order_count'] - $order['lastOrderCount'] > 0)
                                                <i class="fas fa-arrow-up" style="color: green;"></i>
                                            @elseif($order['order_count'] - $order['lastOrderCount'] < 0)
                                                <i class="fas fa-arrow-down" style="color: red;"></i>
                                            @else
                                                <i class="fas fa-arrow-right" style="color: gray;"></i>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 3 Best-Selling Products Section -->
        <div class="container mt-4">
            <h4 class="text-center mb-4">Top 3 Sản Phẩm Bán Chạy Nhất</h4>
            
            <div class="form-group">
                <label for="timeframe-select-products">Chọn Thời Gian:</label>
                <select id="timeframe-select-products" class="form-control" onchange="submitProductForm(this)">
                    <option value="this_week" {{ request('product_timeframe') === 'this_week' ? 'selected' : '' }}>Tuần này</option>
                    <option value="this_month" {{ request('product_timeframe') === 'this_month' ? 'selected' : '' }}>Tháng này</option>
                    <option value="this_quarter" {{ request('product_timeframe') === 'this_quarter' ? 'selected' : '' }}>Quý này</option>
                </select>
            </div>
            
            <div class="mt-3">
                <ul class="list-group">
                    @foreach ($topProducts as $product)
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['product_name'] }}" class="rounded-circle mb-2" style="width: 60px; height: 60px; margin-right: 15px;">
                                <div class="product-details">
                                    <h5 class="mb-1">{{ $product['product_name'] }}</h5>
                                    <div class="product-info">
                                        <span class="text-muted">Số đơn: {{ $product['sales_count'] }}</span>
                                        <span class="text-muted">Số lượng bán: {{ $product['total_quantity'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <span class="badge badge-primary badge-pill">{{ $product['sales_count'] }} đơn</span>
                                <span class="badge badge-success badge-pill">{{ $product['total_quantity'] }} bán</span>
                                <span class="badge badge-info badge-pill">{{ number_format($product['total_revenue'], 0, ',', '.') }} VNĐ</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        
        
        
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script>
        function submitForm(select) {
            const selectedValue = select.value;
            window.location.href = '{{ route('admin.dashboard') }}?timeframe=' + selectedValue + (select.id ===
                'timeframe-select-products' ? '&product_timeframe=' + selectedValue : '');
        }
    </script>
@endsection
