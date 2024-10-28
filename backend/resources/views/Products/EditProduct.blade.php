@extends('Layout.Layout')

@section('content_admin')

<h1>Edit Product</h1>
<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name">Name:</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
    </div>

    <div class="mb-3">
        <label for="avatar">Avatar:</label>
        <input type="file" name="avatar" class="form-control">
        @if ($product->avatar)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $product->avatar) }}" alt="Current Avatar"
                    style="width: 100px; height: auto;">
            </div>
        @endif
    </div>

    <div class="mb-3">
        <label for="images">Gallery:</label>
        <input type="file" id="image-input" name="images[]" multiple class="form-control" accept="image/*">
        @if ($product->galleries->isNotEmpty())
            <div class="mt-2" style="display: flex; gap: 10px" id="existing-images">
                @foreach ($product->galleries as $gallery)
                    <div class="image-preview">
                        <img src="{{ $gallery->image_path }}" alt="Gallery Image"
                            style="width: 100px; height: 60px; margin-right: 10px;">
                        <input type="checkbox" name="delete_gallery[]" value="{{ $gallery->id }}" class="delete-checkbox">
                        <label for="delete_gallery">Xóa</label>
                    </div>
                @endforeach
            </div>
        @endif
        <div id="image-preview-container" class="mt-2"></div>
        <p id="image-count" class="mt-1">Có thể chọn nhiều ảnh</p>
    </div>

    <div class="mb-3">
        <label for="import_price">Import Price:</label>
        <input type="number" name="import_price" step="0.01" class="form-control"
            value="{{ old('import_price', $product->import_price) }}" required>
    </div>

    <div class="mb-3">
        <label for="price">Price:</label>
        <input type="number" name="price" step="0.01" class="form-control"
            value="{{ old('price', $product->price) }}" required>
    </div>

    <div class="mb-3">
        <label for="price">Quantity</label>
        <input type="number" name="quantity" class="form-control"
            value="{{ old('price', $product->quantity) }}" required>
    </div>

    <div class="mb-3">
        <label for="description">Description:</label>
        <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
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
        @foreach ($sizes as $size)
            <input type="checkbox" name="sizes[]" value="{{ $size->id }}" id="size_{{ $size->id }}"
                {{ $product->sizes->contains($size->id) ? 'checked' : '' }}>
            <label for="size_{{ $size->id }}">{{ $size->size }}</label><br>
        @endforeach
    </div>

    <div class="mb-3">
        <label>Màu Sắc:</label><br>
        @foreach ($colors as $color)
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
        <select name="status" class="form-control">
            <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Inactive</option>
            <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Active</option>
            <option value="2" {{ $product->status == 2 ? 'selected' : '' }}>Discontinued</option>
            <option value="3" {{ $product->status == 3 ? 'selected' : '' }}>Pending</option>
        </select>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>

<!-- Image Preview Script -->
<script>
    const imageInput = document.getElementById('image-input');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const imageCount = document.getElementById('image-count');

    imageInput.addEventListener('change', function() {
        const files = Array.from(imageInput.files);
        imageCount.textContent = `Đã chọn ${files.length} ảnh`;
        imagePreviewContainer.innerHTML = ''; // Clear previous previews

        files.forEach((file) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePreview = document.createElement('div');
                imagePreview.classList.add('image-preview');

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('preview-img');

                // Create delete button overlay
                const deleteButton = document.createElement('button');
                deleteButton.classList.add('delete-btn');
                deleteButton.innerHTML = 'X';
                deleteButton.addEventListener('click', () => {
                    imagePreview.remove();
                    files.splice(files.indexOf(file), 1); // Remove the file from the list
                    imageCount.textContent = `Đã chọn ${files.length} ảnh`;
                });

                imagePreview.appendChild(img);
                imagePreview.appendChild(deleteButton);
                imagePreviewContainer.appendChild(imagePreview);
            };
            reader.readAsDataURL(file);
        });
    });
</script>

<!-- Style for Preview Images -->
<style>
    .image-preview {
        position: relative;
        display: inline-block;
        margin-right: 10px;
    }

    .preview-img {
        width: 100px;
        height: auto;
        object-fit: cover;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .delete-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: red;
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
    }
</style>
@endsection
