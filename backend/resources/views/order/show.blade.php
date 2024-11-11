@extends('Layout.Layout')

@section('title')
    Chi tiết đơn hàng
@endsection

@section('content_admin')
<h1 class="text-center my-4">Chi tiết đơn hàng</h1>

<div class="container">
    <h2 class="my-4">Chi tiết đơn hàng #{{ $order->id }}</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderDetails as $detail)
                    <tr>
                        <td>{{ $detail->product->id }}</td>
                        <td>{{ $detail->product->name }}</td>
                        <td class="text-center">
                            <img src="{{ asset('storage/' . $detail->product->avatar) }}" alt="image" style="width: 50px; height: 50px;">
                        </td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price) }} VNĐ</td>
                        <td>{{ number_format($detail->total) }} VNĐ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="my-4">
        <h4>Tổng tiền: <span class="text-success">{{ number_format($order->total_amount) }} VNĐ</span></h4>
        <h4>Người dùng: {{ $order->user->email }}</h4>
        <h4>Địa chỉ giao hàng:</h4>
        <p>
            <strong>Tên người nhận:</strong> {{ $order->shipAddress->recipient_name }}<br>
            <strong>Số điện thoại:</strong> {{ $order->shipAddress->phone_number }}<br>
            <strong>Địa chỉ:</strong> {{ $order->shipAddress->ship_address }}
        </p>
        <h4>Trạng thái: 
            @switch($order->status)
                @case(0) <span class="badge bg-warning text-dark">Chờ xử lý</span> @break
                @case(1) <span class="badge bg-info text-dark">Đã xử lý</span> @break
                @case(2) <span class="badge bg-primary text-white">Đang vận chuyển</span> @break
                @case(3) <span class="badge bg-success">Giao hàng thành công</span> @break
                @case(4) <span class="badge bg-danger">Đã hủy</span> @break
                @case(5) <span class="badge bg-secondary">Đã trả lại</span> @break
            @endswitch
        </h4>
    </div>
    
    <div class="text-center">
        <a href="{{ route('orders.index') }}" class="btn btn-primary">Quay lại danh sách đơn hàng</a>
    </div>
</div>

@endsection
