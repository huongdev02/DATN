@extends('Layout.Layout')
@section('content_admin')
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="name">Name:</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="avatar">Avatar:</label>
        <input type="file" name="avatar" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="images">Gallery:</label>
        <input type="file" name="images[]" multiple class="form-control">
    </div>

    <div class="mb-3">
        <label for="import_price">Import Price:</label>
        <input type="number" name="import_price" step="0.01" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="price">Price:</label>
        <input type="number" name="price" step="0.01" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="description">Description:</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" class="form-control">
            @foreach ($categories as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Kích thước:</label><br>
        @foreach($sizes as $size)
            <input type="checkbox" name="sizes[]" value="{{ $size->id }}" id="size_{{ $size->id }}">
            <label for="size_{{ $size->id }}">{{ $size->size }}</label><br>
        @endforeach
    </div>

    <div class="mb-3">
        <label>Màu Sắc:</label><br>
        @foreach($colors as $color)
            <input type="checkbox" name="colors[]" value="{{ $color->id }}" id="color_{{ $color->id }}">
            <label for="color_{{ $color->id }}">{{ $color->name_color }}</label><br>
        @endforeach
    </div>

    <div class="mb-3">
        <label for="display">Display:</label>
        <input type="checkbox" name="display" value="1" checked>
    </div>

    <div class="mb-3">
        <label for="status">Status:</label>
        <input type="text" name="status" value="1" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Create Product</button>
</form>
@endsection
