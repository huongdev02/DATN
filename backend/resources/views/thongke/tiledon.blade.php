<div class="container">
    <h1 class="mt-4 mb-4">Thống kê tỉ lệ đơn hàng</h1>

    <!-- Tổng đơn hàng và tỉ lệ hoàn thành -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h4>Tổng đơn hàng</h4>
            <p><strong>{{ $data['total_orders'] }}</strong></p>
        </div>
        <div class="col-md-6">
            <h4>Tỉ lệ hoàn thành đơn</h4>
            <p><strong>{{ $data['completion_rate'] }}%</strong></p>
            <p>Tổng số đơn hoàn thành (status = 3): <strong>{{ $data['completed_orders'] }}</strong></p>
        </div>
    </div>

    <!-- Tổng số đơn hàng đã hủy và lý do hủy phổ biến nhất -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h4>Tổng số đơn hàng đã hủy</h4>
            <p><strong>{{ $data['canceled_orders'] }}</strong></p>
            <p>Tỉ lệ hủy đơn: <strong>{{ $data['cancel_rate'] }}%</strong></p>
        </div>
        <div class="col-md-6">
            <h4>Lý do hủy đơn phổ biến nhất</h4>
            <p><strong>{{ $data['most_common_reason'] }}</strong></p>
        </div>
    </div>

    <!-- Thống kê lý do hủy đơn -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h4>Thống kê lý do hủy đơn</h4>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Lý do</th>
                        <th>Số lượng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['reason_counts'] as $reason => $count)
                        <tr>
                            <td>{{ $reason }}</td>
                            <td>{{ $count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Thống kê phương thức thanh toán -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h4>Thống kê phương thức thanh toán</h4>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Phương thức</th>
                        <th>Số lượng</th>
                        <th>Tỉ lệ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['payment_rates'] as $method => $rate)
                        <tr>
                            <td>
                                @if ($method === 'COD')
                                    Thanh toán khi nhận hàng (COD)
                                @elseif ($method === 'Online Payment')
                                    Thanh toán trực tuyến
                                @endif
                            </td>
                            <td>{{ $data['payment_rates'][$method] }}</td>
                            <td>{{ $rate }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
