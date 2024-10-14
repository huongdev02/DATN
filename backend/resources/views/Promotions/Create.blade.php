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
        <h2>Tạo khuyến mãi mới</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('promotions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="product_id">ID sản phẩm:</label>
                <input type="number" class="form-control" id="product_id" name="product_id" value="{{ old('product_id') }}" required>
            </div>
            <div class="form-group">
                <label for="start_day">Ngày bắt đầu:</label>
                <input type="datetime-local" class="form-control" id="start_day" name="start_day" value="{{ old('start_day') }}" required>
            </div>
            <div class="form-group">
                <label for="end_day">Ngày kết thúc:</label>
                <input type="datetime-local" class="form-control" id="end_day" name="end_day" value="{{ old('end_day') }}" required>
            </div>
            <div class="form-group">
                <label for="price_discount">Giá giảm:</label>
                <input type="text" class="form-control" id="price_discount" name="price_discount" value="{{ old('price_discount') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Tạo khuyến mãi</button>
        </form>
    </div>
</body>
</html>