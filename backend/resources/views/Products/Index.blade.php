@extends('Layout.Layout')

@section('title')
    Danh sách sản phẩm
@endsection

@section('content_admin')
<style>
    .gallery-flex {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .gallery-image {
        width: calc(33.33% - 10px);
        height: auto;
    }
</style>
<div class="container">
    <h1 class="text-center mb-3">Danh sách sản phẩm</h1>

    <form action="{{ route('products.index') }}" method="GET" class="mb-3 p-3 text-white " id="filterForm">
        <div class="row">
            <!-- Status Filter -->
            <div class="col-md-3">
                <label for="status" class="form-label fw-bold">Trạng thái</label>
                <select name="status" id="status" class="form-select bg-light text-dark" onchange="this.form.submit()">
                    <option value="">Tất cả trạng thái</option>
                    <option value="0" {{ request('status') == 0 ? 'selected' : '' }}>Không hoạt động</option>
                    <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Đang mở bán</option>
                    <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Ngừng bán</option>
                    <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>Chờ duyệt</option>
                </select>
            </div>
    
            <!-- Display Filter -->
            <div class="col-md-3">
                <label for="display" class="form-label fw-bold">Hiển thị</label>
                <select name="display" id="display" class="form-select bg-light text-dark" onchange="this.form.submit()">
                    <option value="">Tất cả hiển thị</option>
                    <option value="1" {{ request('display') == 1 ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ request('display') == 0 ? 'selected' : '' }}>Không hiển thị</option>
                </select>
            </div>
    
            <!-- Price Range Filter -->
            <div class="col-md-3">
                <label for="price_range" class="form-label fw-bold">Mức giá</label>
                <select name="price_range" id="price_range" class="form-select bg-light text-dark" onchange="this.form.submit()">
                    <option value="">Tất cả mức giá</option>
                    <option value="under_200k" {{ request('price_range') == 'under_200k' ? 'selected' : '' }}>Dưới 200k</option>
                    <option value="200k_500k" {{ request('price_range') == '200k_500k' ? 'selected' : '' }}>Từ 200k đến 500k</option>
                    <option value="over_500k" {{ request('price_range') == 'over_500k' ? 'selected' : '' }}>Trên 500k</option>
                </select>
            </div>
    
            <!-- Price Order Filter -->
            <div class="col-md-3">
                <label for="price_order" class="form-label fw-bold">Sắp xếp theo giá</label>
                <select name="price_order" id="price_order" class="form-select bg-light text-dark" onchange="this.form.submit()">
                    <option value="">Sắp xếp theo giá</option>
                    <option value="asc" {{ request('price_order') == 'asc' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="desc" {{ request('price_order') == 'desc' ? 'selected' : '' }}>Giá giảm dần</option>
                </select>
            </div>
        </div>
    </form>
    
    
    <style>
        .filter-form {
            background-color: #007bff; /* Customize this color as needed */
            color: white;
            padding: 15px; /* Space around the content */
        }
    
        .filter-form .form-select {
            background-color: #f8f9fa; /* Light background for the dropdowns */
            color: #212529; /* Dark text color */
        }
    </style>
    
    

    <a href="{{ route('products.create') }}" class="btn btn-outline-success mb-3">Add New Product</a>

    @if ($products->isEmpty())
        <div class="alert alert-warning text-center">Không có sản phẩm phù hợp với bộ lọc của bạn.</div>
    @else
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Avatar</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Sell</th>
                    <th>Galleries</th>
                    <th>Size</th>
                    <th>Color</th>
                    <th>View</th>
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
                        <img src="{{ asset('storage/' . $item->avatar) }}" alt="{{ $item->name }}" style="width: 50px; height: auto;">
                    </td>
                    <td>{{ number_format($item->price, 2) }} VND</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->sell_quantity }}</td>
                    <td>
                        @if($item->galleries->isNotEmpty())
                            <div class="gallery-flex">
                                @foreach ($item->galleries as $gallery)
                                    <img src="{{ $gallery->image_path }}" alt="Gallery Image" class="gallery-image">
                                @endforeach
                            </div>
                        @else
                            không có ảnh
                        @endif
                    </td>
                    <td>
                        @if($item->sizes->isNotEmpty())
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
                        @if($item->colors->isNotEmpty())
                            @foreach ($item->colors as $color)
                                @php
                                    $colorCode = $colorMap[$color->name_color] ?? '#000000'; 
                                @endphp
                                <span class="color-circle" style="background-color: {{ $colorCode }}; display: inline-block; width: 20px; height: 20px; border-radius: 50%; margin-right: 5px;"></span>
                            @endforeach
                        @else
                            không có màu
                        @endif
                    </td>
                    <td>{{$item->view}}</td>
                    <td>
                        <a href="{{ route('products.edit', $item->id) }}" class="btn btn-outline-warning">Edit</a>
                        <form action="{{ route('products.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('chắc chắn muốn xóa sản phẩm này')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
