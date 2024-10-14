<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <h2>Danh sách khuyến mãi</h2>
        <a href="{{ route('promotions.create') }}" class="btn btn-success">Tạo khuyến mãi mới</a>
        @if (session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sản phẩm</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Giá giảm</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($promotions as $promotion)
                    <tr>
                        <td>{{ $promotion->id }}</td>
                        <td>{{ $promotion->product_id }}</td>
                        <td>{{ $promotion->start_day }}</td>
                        <td>{{ $promotion->end_day }}</td>
                        <td>{{ number_format($promotion->price_discount, 0, ',', '.') }} đ</td>
                        <td>
                            <a href="{{ route('promotions.edit', $promotion->id) }}" class="btn btn-warning">Chỉnh
                                sửa</a>
                            <form action="{{ route('promotions.destroy', $promotion->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Bạn có muốn xóa không?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Hiển thị các liên kết phân trang -->
        <div class="d-flex justify-content-center">
            {{ $promotions->links('pagination::bootstrap-4') }}
        </div>
    </div>
</body>

</html>
