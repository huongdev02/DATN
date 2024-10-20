@extends('master')
@section('title')
    Thêm mới voucher
@endsection
@section('content')
    <h1>Thêm mới voucher</h1>
    @if (session()->has('success') && !session()->get('success'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container">
        <form method="POST" action="{{ route('vouchers.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
                <label for="code" class="col-2 col-form-label">Code:</label>
                <div class="col-8">
                    <input type="text" class="form-control" name="code" id="code" value="{{ old('code') }}" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="type" class="col-2 col-form-label">Type:</label>
                <div class="col-8">
                    <select name="type" id="type" class="form-select" required >
                        <option value="0" {{ old('type') == 0 ? 'selected' : '' }}>Giá trị cố định</option>
                        <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>Triết khấu phần trăm</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="discount_value" class="col-2 col-form-label">Discount Value:</label>
                <div class="col-8">
                    <input type="number" step="0.01" class="form-control" name="discount_value" id="discount_value"
                        value="{{ old('discount_value') }}" required/>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="description" class="col-2 col-form-label">Description:</label>
                <div class="col-8">
                    <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="discount_min" class="col-2 col-form-label">Minimum Discount:</label>
                <div class="col-8">
                    <input type="number" class="form-control" step="0.01" name="discount_min" id="discount_min" value="{{ old('discount_min', 0) }}" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="max_discount" class="col-2 col-form-label">Maximum Discount:</label>
                <div class="col-8">
                    <input type="number" class="form-control" step="0.01" name="max_discount" id="max_discount"value="{{ old('max_discount', $voucher->max_discount) }}" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="min_order_count" class="col-2 col-form-label">Minimum Order Count:</label>
                <div class="col-8">
                    <input type="number" class="form-control" name="min_order_count" id="min_order_count" value="{{ old('min_order_count', 1) }}" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="max_order_count" class="col-2 col-form-label">Maximum Order Count:</label>
                <div class="col-8">
                    <input type="number" class="form-control" name="max_order_count" id="max_order_count" value="{{ old('max_order_count', 1) }}" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="quantity" class="col-2 col-form-label">Quantity:</label>
                <div class="col-8">
                    <input type="number" class="form-control" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="used_times" class="col-2 col-form-label">Used time:</label>
                <div class="col-8">
                    <input type="number" class="form-control" name="used_times" id="used_times" value="{{ old('used_times', $voucher->used_times) }}" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="start_day" class="col-2 col-form-label">Start Date:</label>
                <div class="col-8">
                    <input type="datetime-local" class="form-control" name="start_day" id="start_day" value="{{ old('start_day') }}">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="end_day" class="col-2 col-form-label">End Date:</label>
                <div class="col-8">
                    <input type="datetime-local" class="form-control" name="end_day" id="end_day" value="{{ old('end_day') }}">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="status" class="col-2 col-form-label">Status:</label>
                <div class="col-8">
                    <select name="status" class="form-control"   id="status">
                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Không hoạt động</option>
                        <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Đã hết hạn</option>
                        <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Chờ phát hành</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="offset-sm-4 col-sm-8">
                    <button type="submit" class="btn btn-primary">
                        Create
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
