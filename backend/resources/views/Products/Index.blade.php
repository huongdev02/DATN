@extends('Layout.Layout')
@section('content_admin')
    <h2 class="text-center my-4">Danh sách sản phẩm</h2>
    <a href="{{ route('products.create') }}" class="btn btn-outline-success mb-4"
        style="font-size: 1.1em; padding: 10px 20px;">Add New Product</a>

    <div class="d-flex gap-3 mb-4">
        <!-- Price Order Filter -->
        <form style="width: 200px;" method="GET" action="{{ route('products.index') }}">
            <select style="padding: 10px;" name="price_order" class="form-control" onchange="this.form.submit()">
                <option value="asc" {{ request('price_order') == 'asc' ? 'selected' : '' }}>Giá tăng dần</option>
                <option value="desc" {{ request('price_order') == 'desc' ? 'selected' : '' }}>Giá giảm dần</option>
            </select>
        </form>
    
        <!-- Status Filter -->
        <form style="width: 200px;" method="GET" action="{{ route('products.index') }}">
            <select style="padding: 10px;" name="status" class="form-control" onchange="this.form.submit()">
                <option value="">Tất cả</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang mở bán</option>
                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Ngừng bán</option>
                <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Chờ duyệt</option>
            </select>
        </form>
    
        <!-- Display Filter -->
        <form style="width: 200px;" method="GET" action="{{ route('products.index') }}">
            <select style="padding: 10px;" name="display" class="form-control" onchange="this.form.submit()">
                <option value="">Tất cả</option>
                <option value="1" {{ request('display') == '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ request('display') == '0' ? 'selected' : '' }}>Không hiển thị</option>
            </select>
        </form>
    
        <!-- Price Range Filter -->
        <form style="width: 200px;" method="GET" action="{{ route('products.index') }}">
            <select style="padding: 10px;" name="price_range" class="form-control" onchange="this.form.submit()">
                <option value="">Chọn mức giá</option>
                <option value="under_200k" {{ request('price_range') == 'under_200k' ? 'selected' : '' }}>Dưới 200k</option>
                <option value="200k_500k" {{ request('price_range') == '200k_500k' ? 'selected' : '' }}>200k - 500k</option>
                <option value="over_500k" {{ request('price_range') == 'over_500k' ? 'selected' : '' }}>Trên 500k</option>
            </select>
        </form>
    </div>

    @if ($products->isEmpty())
        <p class="text-center text-muted">Không có sản phẩm nào phù hợp với bộ lọc của bạn.</p>
    @else
        <table class="table table-bordered table-hover text-center">
            <thead class="table-light text-center">
                <tr>
                    <th>STT</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Avatar</th>
                    <th>Quantity</th>
                    <th>Sell</th>
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
                                style="width: 50px; height: auto; border-radius: 8px;">
                        </td>
                        <td>{{ number_format($item->quantity) }}</td>
                        <td>{{ number_format($item->sell_quantity) }}</td>
                        <td>{{ number_format($item->price) }} VND</td>
                        <td>{{ $item->description }}</td>
                        <td>
                            @if ($item->galleries->isNotEmpty())
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($item->galleries as $gallery)
                                        <img src="{{ $gallery->image_path }}" alt="Gallery Image" class="gallery-image"
                                            style="width: 40px; height: auto; border-radius: 5px;">
                                    @endforeach
                                </div>
                            @else
                                không có ảnh
                            @endif
                        </td>
                        <td>
                            @if ($item->sizes->isNotEmpty())
                                {{ $item->sizes->pluck('size')->implode(', ') }}
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
                                        style="background-color: {{ $colorCode }}; width: 20px; height: 20px; display: inline-block; border-radius: 50%; margin-right: 5px;"></span>
                                @endforeach
                            @else
                                không có màu
                            @endif
                        </td>
                        <td class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('products.edit', $item->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                            <form action="{{ route('products.destroy', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <style>
        .gallery-image {
            width: 40px;
            height: 40px;
            margin: 2px;
            border-radius: 5px;
        }
        .color-circle {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 5px;
        }
    </style>
@endsection