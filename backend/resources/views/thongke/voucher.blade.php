<div class="mt-4">
    <h4>Thống kê Voucher</h4>
    <p><strong>Tổng số voucher:</strong> {{ $data['total_vouchers'] }}</p>
    <p><strong>Voucher đã sử dụng:</strong> {{ $data['used_vouchers'] }}</p>
    <p><strong>Voucher còn lại:</strong> {{ $data['remaining_vouchers'] }}</p>
    <p><strong>Tổng tiền đã giảm giá:</strong> {{ number_format($data['total_discounted'], 2) }} VND</p>

    @if ($data['most_used_voucher'])
        <p><strong>Voucher được dùng nhiều nhất:</strong> 
            {{ $data['most_used_voucher']->code }} ({{ $data['most_used_voucher']->used_times }} lần)
        </p>
    @endif
</div>

@if ($data['vouchers_with_days_left']->isNotEmpty())
    <h5 class="mt-4">Danh sách voucher còn hạn sử dụng:</h5>
    <table class="table table-bordered mb-5">
        <thead>
            <tr>
                <th>#</th>
                <th>Mã</th>
                <th>Hạn sử dụng</th>
                <th>Số ngày còn lại</th>
                <th>Đã sử dụng</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['vouchers_with_days_left'] as $index => $voucher)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $voucher->code }}</td>
                    <td>{{ $voucher->end_day }}</td>
                    <td>{{ $voucher->days_left }} ngày</td>
                    <td>{{ $voucher->used_times }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@if ($startDate && $endDate)
    <div class="mt-4">
        <h5>Thống kê theo bộ lọc ({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})</h5>
        <p><strong>Tổng số voucher đã sử dụng:</strong> {{ $data['filtered_used_count'] }}</p>
        <p><strong>Tổng tiền đã giảm giá:</strong> {{ number_format($data['filtered_discounted_total'], 2) }} VND</p>

        @if ($data['most_used_voucher_filtered'])
            <p><strong>Voucher được dùng nhiều nhất trong khoảng thời gian:</strong> 
                {{ $data['most_used_voucher_filtered']->code }}
            </p>
        @endif

        @if ($data['filtered_vouchers']->isNotEmpty())
            <h6 class="mt-4">Danh sách voucher đã sử dụng:</h6>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mã Voucher</th>
                        <th>Người dùng</th>
                        <th>Đơn hàng</th>
                        <th>Giá trị giảm</th>
                        <th>Ngày sử dụng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['filtered_vouchers'] as $index => $usage)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $usage->voucher->code }}</td>
                            <td>{{ $usage->user->name ?? 'N/A' }}</td>
                            <td>{{ $usage->order->id ?? 'N/A' }}</td>
                            <td>{{ number_format($usage->discount_value, 2) }}</td>
                            <td>{{ $usage->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endif
