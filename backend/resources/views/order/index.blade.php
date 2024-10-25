@extends('Layout.Layout')

@section('title')
    Danh sách đơn hàng
@endsection

@section('content_admin')
<h1 class="text-center mb-3">Danh sách đơn hàng</h1>

<div class="container mt-2">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Người dùng</th>
                    <th>Địa chỉ giao hàng</th>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->email }}</td>
                        <td>{{ $order->shipAddress->ship_address }}</td>
                        <td>{{ $order->product->name }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ number_format($order->total_amount, 2) }} VNĐ</td>
                        <td>
                            <form action="{{ route('orders.index') }}" method="GET" id="orderStatusForm-{{ $order->id }}">
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <select name="status" class="form-select" onchange="confirmAndSubmit(this)">
                                    <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>Đã xử lý</option>
                                    <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Đang vận chuyển</option>
                                    <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>Giao hàng thành công</option>
                                    <option value="4" {{ $order->status == 4 ? 'selected' : '' }}>Đã hủy</option>
                                    <option value="5" {{ $order->status == 5 ? 'selected' : '' }}>Đã trả lại</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">Chi tiết</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function confirmAndSubmit(selectElement) {
        const form = selectElement.closest('form');
        const selectedStatus = selectElement.value;

        if (confirm('Có chắc muốn chỉnh sửa trạng thái đơn hàng này?')) {
            form.submit();
        } else {
            selectElement.value = ''; 
        }
    }
</script>
@endsection
