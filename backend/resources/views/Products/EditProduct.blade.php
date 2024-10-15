@extends('Layout.Layout')
@section('content_admin')
<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="name">Name:</label>
        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
    </div>

    <div class="mb-3">
        <label for="avatar">Avatar:</label>
        <input type="file" name="avatar" class="form-control">
        @if($product->avatar)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $product->avatar) }}" alt="Current Avatar" style="width: 100px; height: auto;">
            </div>
        @endif
    </div>

    <div class="mb-3">
        <label for="images">Gallery:</label>
        <input type="file" name="images[]" multiple class="form-control">
        @if($product->galleries->isNotEmpty())
            <div class="mt-2" style="display: flex; gap: 10px">
                @foreach($product->galleries as $gallery)
                    <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="Gallery Image" style="width: 100px; height: auto; margin-right: 10px;">
                @endforeach
            </div>
        @endif
    </div>

    <div class="mb-3">
        <label for="import_price">Import Price:</label>
        <input type="number" name="import_price" step="0.01" class="form-control" value="{{ $product->import_price }}" required>
    </div>

    <div class="mb-3">
        <label for="price">Price:</label>
        <input type="number" name="price" step="0.01" class="form-control" value="{{ $product->price }}" required>
    </div>

    <div class="mb-3">
        <label for="description">Description:</label>
        <textarea name="description" class="form-control">{{ $product->description }}</textarea>
    </div>

    <div class="mb-3">
        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" class="form-control">
            @foreach ($categories as $item)
                <option value="{{ $item->id }}" {{ $item->id == $product->category_id ? 'selected' : '' }}>
                    {{ $item->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Kích thước:</label><br>
        @foreach($sizes as $size)
            <input type="checkbox" name="sizes[]" value="{{ $size->id }}" id="size_{{ $size->id }}"
                {{ $product->sizes->contains($size->id) ? 'checked' : '' }}>
            <label for="size_{{ $size->id }}">{{ $size->size }}</label><br>
        @endforeach
    </div>

    <div class="mb-3">
        <label>Màu Sắc:</label><br>
        @foreach($colors as $color)
            <input type="checkbox" name="colors[]" value="{{ $color->id }}" id="color_{{ $color->id }}"
                {{ $product->colors->contains($color->id) ? 'checked' : '' }}>
            <label for="color_{{ $color->id }}">{{ $color->name_color }}</label><br>
        @endforeach
    </div>

    <div class="mb-3">
        <label for="display">Display:</label>
        <input type="checkbox" name="display" value="1" {{ $product->display ? 'checked' : '' }}>
    </div>

    <div class="mb-3">
        <label for="status">Status:</label>
        <input type="text" name="status" value="{{ $product->status }}" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Update Product</button>
</form>
@endsection
