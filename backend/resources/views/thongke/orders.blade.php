<div class="card text-white bg-success h-100">
    <div class="card-body">
        <h5 class="card-title">Tổng Doanh Thu</h5>
        @if (isset($data['total_amount']) && $data['total_amount'] > 0)
            <p class="card-text">{{ number_format($data['total_amount'], 0, ',', '.') }} VNĐ</p>
            <p class="card-text">
                So với tháng trước: 
                <span class="font-weight-bold">{{ number_format($data['change'], 0, ',', '.') }} VNĐ</span>
                @if ($data['change'] > 0)
                    <i class="fas fa-arrow-up text-success"></i>
                @elseif($data['change'] < 0)
                    <i class="fas fa-arrow-down text-danger"></i>
                @else
                    <i class="fas fa-arrow-right text-muted"></i>
                @endif
            </p>
            <p class="card-text">Doanh thu tháng trước: {{ number_format($data['last_total_amount'], 0, ',', '.') }} VNĐ</p>
        @else
            <p class="card-text">Không có dữ liệu doanh thu.</p>
        @endif
    </div>
</div>

<div class="card text-white bg-info h-100 mt-4">
    <div class="card-body">
        <h5 class="card-title">Số lượng đơn hoàn thành</h5>
        @if (isset($data['order_count']) && $data['order_count'] > 0)
            <p class="card-text">{{ $data['order_count'] }} đơn</p>
            <p class="card-text">
                So với tháng trước:
                <span class="font-weight-bold">{{ number_format($data['order_count_change'], 2) }}%</span>
                @if ($data['order_count_change'] > 0)
                    <i class="fas fa-arrow-up text-success"></i>
                @elseif($data['order_count_change'] < 0)
                    <i class="fas fa-arrow-down text-danger"></i>
                @else
                    <i class="fas fa-arrow-right text-muted"></i>
                @endif
            </p>
            <p class="card-text">Số lượng đơn tháng trước: {{ $data['last_order_count'] }} đơn</p>
        @else
            <p class="card-text">Không có dữ liệu đơn hoàn thành.</p>
        @endif
    </div>
</div>
