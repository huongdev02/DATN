<div class="mt-5 mb-5">
    <form id="filter-stats-form" action="{{ route('admin.orders') }}" method="GET" class="row g-3">
        <div class="col-md-3">
            <label for="start-date" class="form-label">Từ ngày:</label>
            <input type="date" id="start-date" name="start_date" class="form-control"
                value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <label for="end-date" class="form-label">Đến ngày:</label>
            <input type="date" id="end-date" name="end_date" class="form-control"
                value="{{ request('end_date') }}">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
    </form>
</div>

<!-- Thống kê đơn hàng -->
<div class="card text-white bg-success h-100">
    <div class="card-body">
        <h5 class="card-title">Tổng Doanh Thu</h5>
        <p class="card-text">{{ number_format($data['total_amount'], 0, ',', '.') }} VNĐ</p>
        <p class="card-text">
            So với kỳ trước:
            <span class="font-weight-bold">{{ number_format($data['change'], 0, ',', '.') }}</span>
            @if ($data['change'] > 0)
                <i class="fas fa-arrow-up text-success"></i>
            @elseif($data['change'] < 0)
                <i class="fas fa-arrow-down text-danger"></i>
            @else
                <i class="fas fa-arrow-right text-muted"></i>
            @endif
        </p>
    </div>
</div>

<div class="card text-white bg-info h-100 mt-4">
    <div class="card-body">
        <h5 class="card-title">Số lượng đơn hoàn thành</h5>
        <p class="card-text">{{ $data['order_count'] }} đơn</p>
        <p class="card-text">
            So với kỳ trước:
            <span class="font-weight-bold">{{ number_format($data['order_count_change'], 2) }}%</span>
            @if ($data['order_count_change'] > 0)
                <i class="fas fa-arrow-up text-success"></i>
            @elseif($data['order_count_change'] < 0)
                <i class="fas fa-arrow-down text-danger"></i>
            @else
                <i class="fas fa-arrow-right text-muted"></i>
            @endif
        </p>
    </div>
</div>
