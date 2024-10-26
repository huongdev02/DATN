@extends('Layout.Layout')

@section('content_admin')
    <div class="container">
        <h1>Edit Promotion</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form chỉnh sửa promotion -->
        <form action="{{ route('promotion.update', $promotion->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Sử dụng phương thức PUT để cập nhật dữ liệu -->

            <!-- Chọn Product ID (Dropdown danh sách sản phẩm) -->
            <div class="form-group">
                <label for="product_id">Product:</label>
                <select name="product_id" id="product_id" class="form-control">
                    @foreach ($products as $item)
                        <option value="{{ $item->id }}" {{ $item->id == $promotion->product_id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Chọn ngày bắt đầu (Start Day) -->
            <div class="form-group">
                <label for="start_day">Start Day</label>
                <input type="date" name="start_day" class="form-control"
                    value="{{ old('start_day', $promotion->start_day) }}" required>
            </div>

            <!-- Chọn ngày kết thúc (End Day) -->
            <div class="form-group">
                <label for="end_day">End Day</label>
                <input type="date" name="end_day" class="form-control" value="{{ old('end_day', $promotion->end_day) }}"
                    required>
            </div>

            <!-- Nhập mức giảm giá (Price Discount) -->
            <div class="form-group">
                <label for="price_discount">Price Discount</label>
                <input type="number" step="0.01" name="price_discount" class="form-control"
                    value="{{ old('price_discount', $promotion->price_discount) }}" required>
            </div>

            <!-- Nút cập nhật -->
            <button type="submit" class="btn btn-primary mt-3">Update Promotion</button>
        </form>
    </div>
@endsection
