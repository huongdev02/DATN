@extends('master')

@section('content')
    <h1>Edit Voucher</h1>
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
    <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="code">Code:</label>
        <input type="text" name="code" id="code" value="{{ old('code', $voucher->code) }}" required><br><br>

        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="0" {{ old('type', $voucher->type) == 0 ? 'selected' : '' }}>Giá trị cố định</option>
            <option value="1" {{ old('type', $voucher->type) == 1 ? 'selected' : '' }}>Triết khấu phần trăm</option>
        </select><br><br>

        <label for="discount_value">Discount Value:</label>
        <input type="number" step="0.01" name="discount_value" id="discount_value"
            value="{{ old('discount_value', $voucher->discount_value) }}" required><br><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description">{{ old('description', $voucher->description) }}</textarea><br><br>

        <label for="discount_min">Minimum Discount:</label>
        <input type="number" step="0.01" name="discount_min" id="discount_min"
            value="{{ old('discount_min', $voucher->discount_min) }}" required><br><br>

        <label for="max_discount">Maximum Discount:</label>
        <input type="number" step="0.01" name="max_discount" id="max_discount"
            value="{{ old('max_discount', $voucher->max_discount) }}" required><br><br>

        <label for="min_order_count">Minimum Order Count:</label>
        <input type="number" name="min_order_count" id="min_order_count"
            value="{{ old('min_order_count', $voucher->min_order_count) }}" required><br><br>

        <label for="max_order_count">Maximum Order Count:</label>
        <input type="number" name="max_order_count" id="max_order_count"
            value="{{ old('max_order_count', $voucher->max_order_count) }}" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $voucher->quantity) }}"
            required><br><br>

        <label for="used_times">Used Times:</label>
        <input type="number" name="used_times" id="used_times" value="{{ old('used_times', $voucher->used_times) }}"
            required><br><br>

        <label for="start_day">Start Date:</label>
        <input type="datetime-local" name="start_day" id="start_day"
        value="{{ old('start_day', $voucher->start_day ? \Carbon\Carbon::parse($voucher->start_day)->format('Y-m-d\TH:i') : '') }}"><br><br>

        <label for="end_day">End Date:</label>
        <input type="datetime-local" name="end_day" id="end_day"
            value="{{ old('end_day', $voucher->end_day ? \Carbon\Carbon::parse($voucher->end_day)->format('Y-m-d\TH:i') : '') }}"><br><br>

        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="1" {{ old('status', $voucher->status) == 1 ? 'selected' : '' }}>Đang hoạt động</option>
            <option value="0" {{ old('status', $voucher->status) == 0 ? 'selected' : '' }}>Không hoạt động</option>
            <option value="2" {{ old('status', $voucher->status) == 2 ? 'selected' : '' }}>Đã hết hạn</option>
            <option value="3" {{ old('status', $voucher->status) == 3 ? 'selected' : '' }}>Chờ phát hành</option>
        </select><br><br>

        <button type="submit">Update Voucher</button>
    </form>
@endsection
