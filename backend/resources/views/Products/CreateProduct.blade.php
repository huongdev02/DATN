@extends('Layout.Layout')

@section('title') <!-- Corrected 'tiltle' to 'title' -->
    Thêm mới sản phẩm
@endsection

@section('content_admin')
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="name">Name:</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="avatar">Avatar:</label>
        <input type="file" name="avatar" class="form-control" accept="image/*" required> <!-- Added 'accept' for image types -->
    </div>

    <div class="mb-3">
        <label for="images">Gallery:</label>
        <input type="file" id="image-input" class="form-control" name="image_path[]" multiple accept="image/*" placeholder="Có thể chọn nhiều ảnh">
        <div id="image-preview-container" class="mt-2"></div>
        <p id="image-count" class="mt-1">Có thể chọn nhiều ảnh</p>
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
        <textarea name="description" class="form-control" rows="10"></textarea>
    </div>

    <div class="mb-3">
        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" class="form-control" required>
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
        <label for="display">Hiển thị</label>
        <input type="checkbox" name="display" value="1" checked>
    </div>

    <div class="mb-3">
        <label for="status">Status:</label>
        <select name="status" class="form-control mb-3 mt-2" required>
            <option value="0">Không hoạt động</option>
            <option value="1" selected>Đang mở bán</option>
            <option value="2">Ngừng bán</option>
            <option value="3">Chờ duyệt</option>
        </select>
    </div>

    <div class="text-center mb-5 mt-3">
        <button type="submit" class="btn btn-primary">Create Product</button>
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

        files.forEach((file, index) => {
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
                    files.splice(index, 1); // Remove the file from the list
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
        width: 150px;
        height: 100px;
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
