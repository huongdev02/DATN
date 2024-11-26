<div class="mt-5 mb-5">
    <form id="filter-stats-form" action="{{ route('admin.topproducts') }}" method="GET" class="row g-3">
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

<h4 class="text-center mb-4">Top 3 Sản Phẩm Bán Chạy Nhất</h4>
<div class="list-group">
    @foreach ($topProducts as $product)
        <div class="list-group-item d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['product_name'] }}"
                    class="rounded-circle" style="width: 60px; height: 60px; margin-right: 15px;">
                <div>
                    <h5 class="mb-1">{{ $product['product_name'] }}</h5>
                    <div class="text-muted">
                        <span>Số đơn: {{ $product['sales_count'] }}</span> |
                        <span>Số lượng bán: {{ $product['total_quantity'] }}</span>
                    </div>
                </div>
            </div>
            <div>
                <span class="badge bg-primary">{{ $product['sales_count'] }} đơn</span>
                <span class="badge bg-success">{{ $product['total_quantity'] }} bán</span>
                <span class="badge bg-info">{{ number_format($product['total_revenue'], 0, ',', '.') }} VNĐ</span>
            </div>
        </div>
    @endforeach
</div>
