<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="name"> Name:</label>
    <input type="text" name="name" required><br>

    <label for="avatar"> Avatar:</label>
    <input type="file" name="avatar" required><br>

    <label for="images"> Gallery:</label>
    <input type="file" name="images[]" multiple><br>

    <label for="import_price">Import Price:</label>
    <input type="number" name="import_price" step="0.01" required><br>

    <label for="price">Price:</label>
    <input type="number" name="price" step="0.01" required><br>

    <label for="description">Description:</label>
    <textarea name="description"></textarea><br>

    <label for="category_id">Category:</label>
    <select name="category_id" id="category_id">
        @foreach ($categories as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
    </select>

    <div>
        <label>Kích thước:</label><br>
        @foreach($sizes as $size)
            <input type="checkbox" name="sizes[]" value="{{ $size->id }}" id="size_{{ $size->id }}">
            <label for="size_{{ $size->id }}">{{ $size->size }}</label><br>
        @endforeach
    </div>

    <div>
        <label>Màu Sắc:</label><br>
        @foreach($colors as $color)
            <input type="checkbox" name="colors[]" value="{{ $color->id }}" id="color_{{ $color->id }}">
            <label for="color_{{ $color->id }}">{{ $color->name_color }}</label><br>
        @endforeach
    </div>

    <label for="display">Display:</label>
    <input type="checkbox" name="display" value="1" checked><br>

    <label for="status">Status:</label>
    <input type="text" name="status" value="1">

    <button type="submit">Create Product</button>
</form>
