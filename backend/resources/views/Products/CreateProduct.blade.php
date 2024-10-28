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

    <div class="mb-3" id="gallery-container">
        <label for="images">Gallery:</label>
        <div class="gallery-input">
            <input type="file" name="images[]" class="form-control mb-2" multiple>
            <button type="button" class="btn btn-danger btn-sm remove-image">Xóa</button>
        </div>
        <button type="button" class="btn btn-success mt-2" id="add-image">Thêm</button>
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
        <label for="display">Hiển thị</label>
        <input type="checkbox" name="display" value="1" checked>
    </div>

    <div class="mb-3">
        <label for="status">Status:</label>
        <select name="status" class="form-control mb-3 mt-2">
            <option class="form-control" value="0">Không hoạt động</option>
            <option class="form-control" value="1" selected>Đang mở bán</option>
            <option class="form-control" value="2">Ngừng bán</option>
            <option class="form-control" value="3">Chờ duyệt</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Create Product</button>
</form>

<script>
    document.getElementById('add-image').addEventListener('click', function() {
        const galleryContainer = document.getElementById('gallery-container');
        const newInput = document.createElement('div');
        newInput.classList.add('gallery-input');

        // Tạo input file mới
        const inputFile = document.createElement('input');
        inputFile.type = 'file';
        inputFile.name = 'images[]';
        inputFile.classList.add('form-control', 'mb-2');

        // Tạo nút "Xóa"
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('btn', 'btn-danger', 'btn-sm', 'remove-image');
        removeButton.textContent = 'Xóa';

        // Thêm input file và nút "Xóa" vào div
        newInput.appendChild(inputFile);
        newInput.appendChild(removeButton);

        galleryContainer.insertBefore(newInput, this); // Thêm input mới trước nút "+"
        
        // Thêm sự kiện click cho nút "Xóa"
        removeButton.addEventListener('click', function() {
            galleryContainer.removeChild(newInput); // Xóa input file khi nhấn nút "Xóa"
        });
    });
</script>
@endsection
