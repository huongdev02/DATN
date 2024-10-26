@extends('Layout.Layout')

@section('content_admin')
    <div class="container mt-4">
        <h1 class="text-center">Edit Voucher</h1>
        <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST" class="bg-light p-4 border rounded">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="code" class="form-label">Code:</label>
                <input type="text" class="form-control" name="code" id="code" value="{{ old('code', $voucher->code) }}" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type:</label>
                <select class="form-select" name="type" id="type" required>
                    <option value="0" {{ old('type', $voucher->type) == 0 ? 'selected' : '' }}>Giá trị cố định</option>
                    <option value="1" {{ old('type', $voucher->type) == 1 ? 'selected' : '' }}>Triết khấu phần trăm</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="discount_value" class="form-label">Discount Value:</label>
                <input type="number" step="1" class="form-control" name="discount_value" id="discount_value" value="{{ old('discount_value', $voucher->discount_value) }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" name="description" id="description">{{ old('description', $voucher->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="discount_min" class="form-label">Minimum Discount:</label>
                <input type="number" step="1" class="form-control" name="discount_min" id="discount_min" value="{{ old('discount_min', $voucher->discount_min) }}" required>
            </div>

            <div class="mb-3">
                <label for="max_discount" class="form-label">Maximum Discount:</label>
                <input type="number" step="1" class="form-control" name="max_discount" id="max_discount" value="{{ old('max_discount', $voucher->max_discount) }}" required>
            </div>

            <div class="mb-3">
                <label for="min_order_count" class="form-label">Minimum Order Count:</label>
                <input type="number" class="form-control" name="min_order_count" id="min_order_count" value="{{ old('min_order_count', $voucher->min_order_count) }}" required>
            </div>

            <div class="mb-3">
                <label for="max_order_count" class="form-label">Maximum Order Count:</label>
                <input type="number" class="form-control" name="max_order_count" id="max_order_count" value="{{ old('max_order_count', $voucher->max_order_count) }}" required>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" class="form-control" name="quantity" id="quantity" value="{{ old('quantity', $voucher->quantity) }}" required>
            </div>

            <div class="mb-3">
                <label for="used_times" class="form-label">Used Times:</label>
                <input type="number" class="form-control" name="used_times" id="used_times" value="{{ old('used_times', $voucher->used_times) }}" required>
            </div>

            <div class="mb-3">
                <label for="start_day" class="form-label">Start Date:</label>
                <input type="datetime-local" class="form-control" name="start_day" id="start_day" value="{{ old('start_day', $voucher->start_day ? \Carbon\Carbon::parse($voucher->start_day)->format('Y-m-d\TH:i') : '') }}">
            </div>

            <div class="mb-3">
                <label for="end_day" class="form-label">End Date:</label>
                <input type="datetime-local" class="form-control" name="end_day" id="end_day" value="{{ old('end_day', $voucher->end_day ? \Carbon\Carbon::parse($voucher->end_day)->format('Y-m-d\TH:i') : '') }}">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select class="form-select" name="status" id="status">
                    <option value="1" {{ old('status', $voucher->status) == 1 ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="0" {{ old('status', $voucher->status) == 0 ? 'selected' : '' }}>Không hoạt động</option>
                    <option value="2" {{ old('status', $voucher->status) == 2 ? 'selected' : '' }}>Đã hết hạn</option>
                    <option value="3" {{ old('status', $voucher->status) == 3 ? 'selected' : '' }}>Chờ phát hành</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Voucher</button>
        </form>
    </div>
@endsection
