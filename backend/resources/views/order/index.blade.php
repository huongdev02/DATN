@extends('Layout.Layout')

@section('title')
    Danh sách đơn hàng
@endsection

@section('content_admin')
    <div class="d-flex justify-content-between px-3">
        <h1 class="text-center mb-3">Danh sách đơn hàng</h1>
    <form action="{{ route('orders.index') }}" method="GET" class="mb-3">
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">Tất cả trạng thái</option>
            <option value="0" {{ request('status') == 0 ? 'selected' : '' }}>Chờ xử lý</option>
            <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Đã xử lý</option>
            <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Đang vận chuyển</option>
            <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>Giao hàng thành công</option>
            <option value="4" {{ request('status') == 4 ? 'selected' : '' }}>Đã hủy</option>
            <option value="5" {{ request('status') == 5 ? 'selected' : '' }}>Đã trả lại</option>
        </select>
    </form>
    </div>
    <div class="container mt-2">
     
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Địa chỉ giao hàng</th>
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
                            <td>{{ $order->quantity }}</td>
                            <td>{{ number_format($order->total_amount, 2) }} VNĐ</td>
                            <td>
                                <form action="{{ route('orders.index') }}" method="GET"
                                    id="orderStatusForm-{{ $order->id }}">
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <select name="status" class="form-select" onchange="confirmAndSubmit(this)">
                                        <option value="0" style="color: gray;"
                                            {{ $order->status == 0 ? 'selected' : '' }}>Chờ xử lý</option>
                                        <option value="1" style="color: blue;"
                                            {{ $order->status == 1 ? 'selected' : '' }}>Đã xử lý</option>
                                        <option value="2" style="color: orange;"
                                            {{ $order->status == 2 ? 'selected' : '' }}>Đang vận chuyển</option>
                                        <option value="3" style="color: green;"
                                            {{ $order->status == 3 ? 'selected' : '' }}>Giao hàng thành công</option>
                                        <option value="4" style="color: red;"
                                            {{ $order->status == 4 ? 'selected' : '' }}>Đã hủy</option>
                                        <option value="5" style="color: purple;"
                                            {{ $order->status == 5 ? 'selected' : '' }}>Đã trả lại</option>
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
