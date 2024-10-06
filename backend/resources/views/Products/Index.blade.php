<a href="{{ route('products.create') }}">Thêm</a>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>Name</th>
            <th>Avatar</th>
            <th>Category ID</th>
            <th>Price</th>
            <th>Description</th>
            <th>Display</th>
            <th>Status</th>
            <th>Galleries</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td><img src="{{ asset('storage/' . $product->avatar) }}" alt="{{ $product->name }}" width="50"></td>
                <td>{{ $product->category_id }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->display }}</td>
                <td>{{ $product->status }}</td>
                
                <td>
                    @if($product->galleries->isNotEmpty())
                        @foreach ($product->galleries as $gallery)
                            <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="Gallery Image" width="50">
                        @endforeach
                    @else
                        không có ảnh
                    @endif
                </td>

                <td>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Bạn có phải NVK không ?')">Delete</button>
                    </form>
                    <a href="{{ route('products.edit', $product->id) }}">Update</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
