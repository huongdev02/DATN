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
                            <option value="this_week" {{ request('timeframe') === 'this_week' ? 'selected' : '' }}>Tuần này</option>
                            <option value="this_month" {{ request('timeframe') === 'this_month' ? 'selected' : '' }}>Tháng này</option>
                            <option value="this_quarter" {{ request('timeframe') === 'this_quarter' ? 'selected' : '' }}>Quý này</option>
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
                                    <i id="changeIcon" class="fas"></i>
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
                            <option value="this_week" {{ request('timeframe') === 'this_week' ? 'selected' : '' }}>Tuần này</option>
                            <option value="this_month" {{ request('timeframe') === 'this_month' ? 'selected' : '' }}>Tháng này</option>
                            <option value="this_quarter" {{ request('timeframe') === 'this_quarter' ? 'selected' : '' }}>Quý này</option>
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
                                            <span id="revenueChangeValue" class="font-weight-bold">{{ $order['change'] }}</span>
                                            <i id="revenueChangeIcon" class="fas"></i>
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="card-title">Số lượng đơn hoàn thành</h5>
                                        <p class="card-text" id="completedOrders">{{ $order['order_count'] }} đơn</p>
                                        <p class="card-text" id="orderChangeIndicator">
                                            So với kỳ trước:
                                            <span id="orderChangeValue" class="font-weight-bold">{{ $order['order_count'] - $order['lastOrderCount'] }}</span>
                                            <i id="orderChangeIcon" class="fas"></i>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function submitForm(select) {
            const selectedValue = select.value;
            window.location.href = '{{ route("admin.dashboard") }}?timeframe=' + selectedValue;
        }
    </script>
@endsection
