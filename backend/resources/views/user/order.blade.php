@extends('user.master')

@section('title')
    Danh sách Voucher
@endsection

@section('content')
<h1 class="text-center mb-3">Danh sách đơn hàng</h1>

<div class="container mt-2">
    <!-- Navigation Tabs for Filtering by Order Status -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link {{ request()->get('status') === null ? 'active' : '' }}" href="{{ route('userorder.index') }}">Tất cả</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->get('status') == 0 ? 'active' : '' }}" href="{{ route('userorder.index', ['status' => 0]) }}">Chờ thanh toán</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->get('status') == 1 ? 'active' : '' }}" href="{{ route('userorder.index', ['status' => 1]) }}">Đã xử lí</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->get('status') == 2 ? 'active' : '' }}" href="{{ route('userorder.index', ['status' => 2]) }}">Vận chuyển</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->get('status') == 3 ? 'active' : '' }}" href="{{ route('userorder.index', ['status' => 3]) }}">Hoàn thành</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->get('status') == 4 ? 'active' : '' }}" href="{{ route('userorder.index', ['status' => 4]) }}">Đã hủy</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->get('status') == 5 ? 'active' : '' }}" href="{{ route('userorder.index', ['status' => 5]) }}">Trả hàng/Hoàn tiền</a>
        </li>
    </ul>

    <!-- Order List -->
    @if($orders->isEmpty())
        <p class="text-center mt-4">Vui lòng mua sắm</p>
    @else
        @foreach ($orders as $order)
            <div class="card my-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><strong>Mall</strong> {{ $order->shop_name ?? 'Shop' }}</span>
                    <div>
                        <span class="badge 
                            @if($order->status == 3) bg-success 
                            @elseif($order->status == 4) bg-danger 
                            @else bg-info 
                            @endif">
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
                            <p>Phân loại hàng: {{ $order->product->category->name ?? 'Không rõ' }}</p>
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

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
