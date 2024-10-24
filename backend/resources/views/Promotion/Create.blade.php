@extends('Layout.Layout')
@section('content_admin')
<div class="container">
    <h1>Create Promotion</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('promotion.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="product_id">Product ID</label>
            
    <div class="mb-3">
        <label for="product_id">Product:</label>
        <select name="product_id" id="product_id" class="form-control">
            @foreach ($products as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>

        </div>

        <div class="form-group">
            <label for="start_day">Start Day</label>
            <input type="date" name="start_day" class="form-control" value="{{ old('start_day') }}" required>
        </div>

        <div class="form-group">
            <label for="end_day">End Day</label>
            <input type="date" name="end_day" class="form-control" value="{{ old('end_day') }}" required>
        </div>

        <div class="form-group">
            <label for="price_discount">Price Discount</label>
            <input type="number" step="0.01" name="price_discount" class="form-control" value="{{ old('price_discount') }}" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Create Promotion</button>
    </form>
</div>
@endsection