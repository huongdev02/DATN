@extends('user.master')

@section('title')
    Danh sách Voucher
@endsection

@section('content')
<h1 class="text-center mb-3">Danh sách đơn hàng</h1>

<div class="container mt-2">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#">Tất cả</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Chờ thanh toán</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Vận chuyển</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Chờ giao hàng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Hoàn thành</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Đã hủy</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Trả hàng/Hoàn tiền</a>
        </li>
    </ul>

    @foreach ($orders as $order)
        <div class="card my-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><strong>Mall</strong> {{ $order->shop_name ?? 'Shop' }}</span>
                <div>
                    <span class="badge {{ $order->status == 3 ? 'bg-success' : ($order->status == 4 ? 'bg-danger' : 'bg-info') }}">
                        {{ $order->status == 3 ? 'Giao hàng thành công' : ($order->status == 4 ? 'Đã hủy' : 'Đang xử lý') }}
                    </span>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <img src="{{ $order->product->avatar ?? 'path_to_placeholder_image' }}" alt="{{ $order->product->name }}" class="img-fluid">
                    </div>
                    <div class="col-md-6">
                        <h5>{{ $order->product->name }}</h5>
                        <p>Phân loại hàng: {{ $order->product->category_id ?? 'Không rõ' }}</p>
                        <p>Số lượng: x{{ $order->quantity }}</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <h5 class="text-danger">₫{{ number_format($order->total_amount, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-outline-primary btn-sm">15 ngày trả hàng</button>
                    <button class="btn btn-outline-danger btn-sm">Trả hàng miễn phí 15 ngày</button>
                </div>
                <div>
                    <a href="#" class="btn btn-outline-secondary btn-sm">Đánh Giá</a>
                    <a href="#" class="btn btn-outline-secondary btn-sm">Yêu Cầu Trả Hàng/Hoàn Tiền</a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Thêm
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Liên hệ hỗ trợ</a></li>
                            <li><a class="dropdown-item" href="#">Xem chi tiết</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
