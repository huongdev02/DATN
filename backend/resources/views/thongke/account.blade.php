<div class="card text-white bg-primary h-100">
    <div class="card-body">
        <h5 class="card-title">Số lượng người dùng mới</h5>
        @if (isset($data['count']) && $data['count'] > 0)
            <p class="card-text">{{ $data['count'] }} người dùng mới</p>
            <p class="card-text">
                So với tháng trước:
                <span class="font-weight-bold">{{ number_format($data['change'], 2) }}%</span>
                @if ($data['change'] > 0)
                    <i class="fas fa-arrow-up text-success"></i>
                @elseif($data['change'] < 0)
                    <i class="fas fa-arrow-down text-danger"></i>
                @else
                    <i class="fas fa-arrow-right text-muted"></i>
                @endif
            </p>
            <p class="card-text">Người dùng mới tháng trước: {{ $data['last_count'] }} người</p>
        @else
            <p class="card-text">Không có dữ liệu người dùng mới.</p>
        @endif
    </div>
</div>
