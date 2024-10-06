<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label for="name">Product Name:</label>
    <input type="text" name="name" value="{{ $product->name }}" required><br>

    <label for="avatar">Product Avatar:</label>
    <input type="file" name="avatar"><br>
    <img src="{{ asset('storage/' . $product->avatar) }}" alt="{{ $product->name }}" width="100"><br>

    <label for="images">Product Gallery:</label>
    <input type="file" name="images[]" multiple><br>
    @foreach ($product->galleries as $gallery)
        <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="Gallery Image" width="100"><br>
    @endforeach

    <label for="import_price">Import Price:</label>
    <input type="number" name="import_price" value="{{ $product->import_price }}" step="0.01" required><br>

    <label for="price">Price:</label>
    <input type="number" name="price" value="{{ $product->price }}" step="0.01" required><br>

    <label for="description">Description:</label>
    <textarea name="description">{{ $product->description }}</textarea><br>

    <label for="category_id">Category:</label>
    <input type="number" name="category_id" value="{{ $product->category_id }}" required><br>

    <label for="display">Display:</label>
    <input type="checkbox" name="display" value="1" {{ $product->display ? 'checked' : '' }}><br>

    <button type="submit">Update Product</button>
</form>
