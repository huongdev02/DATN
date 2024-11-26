<div class="mt-5 mb-5">
    <form id="filter-stats-form" action="{{ route('thongke.account') }}" method="GET" class="row g-3">
        <div class="col-md-3">
            <label for="start-date" class="form-label">Từ ngày:</label>
            <input type="date" id="start-date" name="start_date" class="form-control"
                value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <label for="end-date" class="form-label">Đến ngày:</label>
            <input type="date" id="end-date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
    </form>
</div>

<div class="card text-white bg-primary h-100">
    <div class="card-body">
        <h5 class="card-title">Số lượng người dùng mới</h5>
        <p class="card-text">{{ $count }} người dùng mới</p>
        <p class="card-text">
            So với kỳ trước:
            <span class="font-weight-bold">{{ $change }}</span>
            @if ($change > 0)
                <i class="fas fa-arrow-up text-success"></i>
            @elseif($change < 0)
                <i class="fas fa-arrow-down text-danger"></i>
            @else
                <i class="fas fa-arrow-right text-muted"></i>
            @endif
        </p>
    </div>
</div>
