<div class="card text-white bg-primary h-100">
    <div class="card-body">
        <h5 class="card-title">Số lượng người dùng mới</h5>
        <p class="card-text">{{ $account['count'] }} người dùng mới</p>
        <p class="card-text">
            So với kỳ trước:
            <span class="font-weight-bold">{{ $account['change'] }}</span>
            @if ($account['change'] > 0)
                <i class="fas fa-arrow-up text-success"></i>
            @elseif($account['change'] < 0)
                <i class="fas fa-arrow-down text-danger"></i>
            @else
                <i class="fas fa-arrow-right text-muted"></i>
            @endif
        </p>
    </div>
</div>
