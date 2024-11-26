@extends('Layout.Layout')

@section('title')
    DashBoard Admin
@endsection

@section('content_admin')
    <div class="container text-center mt-5 mb-3">
        <h2>DashBoard Admin</h2>
    </div>

    <!-- Form lọc thời gian -->
    <div class="container">
        <form id="filter-stats-form" action="{{ route('admin.dashboard') }}" method="GET" class="row g-3">
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

    <!-- Menu -->
    <div class="container mt-4">
        <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">
                    Thống kê Tài khoản
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
                    Thống kê Đơn hàng
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="top-products-tab" data-bs-toggle="tab" data-bs-target="#top-products" type="button" role="tab">
                    Sản phẩm bán chạy
                </button>
            </li>
        </ul>

        <!-- Nội dung của từng tab -->
        <div class="tab-content container mt-4">
            <div class="tab-pane fade show active" id="account" role="tabpanel">
                @include('thongke.account')
            </div>
            <div class="tab-pane fade" id="orders" role="tabpanel">
                @include('thongke.orders')
            </div>
            <div class="tab-pane fade" id="top-products" role="tabpanel">
                @include('thongke.topproduct')
            </div>
        </div>
    </div>
@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
