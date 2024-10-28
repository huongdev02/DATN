@extends('Layout.Layout')
@section('content_admin')
    <a href="{{ route('products.create') }}" class="btn btn-outline-success mb-3">Add New Product</a>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>STT</th>
                <th>Name</th>
                <th>Category</th>
                <th>Avatar</th>
                <th>Old Price</th>
                <th>Price</th>
                <th>Description</th>
                <th>Galleries</th>
                <th>Size</th>
                <th>Color</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->categories->name }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $item->avatar) }}" alt="{{ $item->name }}"
                            style="width: 50px; height: auto;">
                    </td>
                    <td>{{ number_format($item->import_price, 2) }} VND</td>
                    <td>{{ number_format($item->price, 2) }} VND</td>
                    <td>{{ $item->description }}</td>
                    <td>
                        @if ($item->galleries->isNotEmpty())
                            <div class="gallery-flex">
                                @foreach ($item->galleries as $gallery)
                                    <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="Gallery Image"
                                        class="gallery-image">
                                @endforeach
                            </div>
                        @else
                            không có ảnh
                        @endif
                    </td>
                    <td>
                        @if ($item->sizes->isNotEmpty())
                            @foreach ($item->sizes as $size)
                                <span>{{ $size->size }}</span>
                                @if (!$loop->last)
                                    <span>, </span>
                                @endif
                            @endforeach
                        @else
                            không có kích thước
                        @endif
                    </td>
                    <td>
                        @if ($item->colors->isNotEmpty())
                            @foreach ($item->colors as $color)
                                @php
                                    $colorCode = $colorMap[$color->name_color] ?? '#000000';
                                @endphp
                                <span class="color-circle"
                                    style="background-color: {{ $colorCode }}; display: inline-block; width: 20px; height: 20px; border-radius: 50%; margin-right: 5px;"></span>
                            @endforeach
                        @else
                            không có màu
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('products.edit', $item->id) }}" class="btn btn-outline-warning">Edit</a>
                        <form action="{{ route('products.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
