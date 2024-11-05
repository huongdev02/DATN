@extends('user.master')

@section('title')
    Danh sách Đơn hàng
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
            <a class="nav-link {{ request()->get('status') == 1 ? 'active' : '' }}" href="{{ route('userorder.index', ['status' => 1]) }}">Đã xử lý</a>
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
                            <img src="{{ $order->product->avatar ?? 'path_to_placeholder_image' }}" alt="" class="img-fluid">
                        </div>
                        <div class="col-md-6">
                            <h5>{{ $order->product->name ?? 'Không rõ' }}</h5>
                            <p>Phân loại hàng: {{ $order->product && $order->product->category ? $order->product->category->name : 'Không rõ' }}</p>
                            <p>Số lượng: x{{ $order->quantity }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <h5 class="text-danger">₫{{ number_format($order->total_amount, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div>
                        @if ($order->status == 0 || $order->status == 1) <!-- Chờ thanh toán hoặc đã xử lý -->
                            <button class="btn btn-outline-danger btn-sm" onclick="showCancelReasons({{ $order->id }})">Hủy</button>
                        @elseif ($order->status == 2) <!-- Vận chuyển -->
                            <span>Đơn hàng đang vận chuyển không thể hủy</span>
                        @elseif ($order->status == 3) <!-- Hoàn thành -->
                            <button class="btn btn-outline-success btn-sm" onclick="confirmReceiveOrder({{ $order->id }})">Đã nhận hàng</button>
                        @endif
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

        <!-- Modal for Cancel Reasons -->
        <div class="modal fade" id="cancelReasonsModal" tabindex="-1" aria-labelledby="cancelReasonsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelReasonsModalLabel">Lý do hủy đơn hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="cancelOrderForm">
                            <label for="cancelReason">Chọn lý do hủy:</label>
                            <select class="form-select" id="cancelReason" name="cancelReason">
                                <option value="1">Không còn nhu cầu</option>
                                <option value="2">Chưa nhận hàng</option>
                                <option value="3">Khác</option>
                            </select>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" onclick="submitCancelOrder()">Xác nhận hủy</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function showCancelReasons(orderId) {
        $('#cancelReasonsModal').modal('show');
        $('#cancelOrderForm').data('orderId', orderId);
    }

    function submitCancelOrder() {
        const orderId = $('#cancelOrderForm').data('orderId');
        const reason = $('#cancelReason').val();

        // Gửi yêu cầu hủy đơn hàng
        $.ajax({
            url: `/order/${orderId}/cancel`, // Thay đổi đường dẫn nếu cần
            type: 'POST',
            data: { reason: reason, _token: '{{ csrf_token() }}' },
            success: function(response) {
                location.reload(); // Tải lại trang sau khi hủy
            },
            error: function(error) {
                alert('Có lỗi xảy ra! Vui lòng thử lại.');
            }
        });
    }

    function confirmReceiveOrder(orderId) {
        if (confirm("Bạn có chắc chắn đã nhận hàng?")) {
            // Gửi yêu cầu xác nhận đã nhận hàng
            $.ajax({
                url: `/order/${orderId}/confirm-receive`, // Thay đổi đường dẫn nếu cần
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    location.reload(); // Tải lại trang sau khi xác nhận
                },
                error: function(error) {
                    alert('Có lỗi xảy ra! Vui lòng thử lại.');
                }
            });
        }
    }
</script>
@endsection
