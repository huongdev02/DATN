@extends('Layout.Layout')
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
        <div class="d-flex justify-content-between">
            <a href="{{ route('products.create') }}" class="btn btn-outline-success mb-3">Add New Product</a>
            <div class="d-flex gap-2">
                <form style="width: 200px;" method="GET" action="{{ route('products.index') }}" id="filterForm"> 
                    <div class="mb-3">
                        <select style="padding: 15px" name="sort" id="sort" class="form-control" onchange="this.form.submit()">
                            <option value="price" {{ $sort == 'price' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                        </select>
                    </div>
                </form>
                <form  style="width: 200px;" action="{{ route('products.index') }}" method="GET" id="filterForm">
                   <div class="mb-3">
                    <select style="padding: 15px" name="status" id="statusFilter" class="form-control" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang mở bán</option>
                        <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Ngừng bán</option>
                        <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Chờ duyệt</option>
                    </select>
                   </div>
                </form>
                <form  style="width: 200px;" action="{{ route('products.index') }}" method="GET" id="filterForm">
                  <div class="mb-3">
                    <select style="padding: 15px" name="display" id="displayFilter" class="form-control" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <option value="1" {{ request('display') == '1' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ request('display') == '0' ? 'selected' : '' }}>Không hiện thị</option>
                    </select>
                  </div>
                </form>
            </div>
        </div>
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
                            <img src="{{ asset('storage/' . $item->avatar) }}" alt="{{ $item->name }}"
                                style="width: 50px; height: 50px">
                        </td>
                        <td>{{ number_format($item->price, 2) }} VND</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->sell_quantity }}</td>
                        <td>
                            @if ($item->galleries->isNotEmpty())
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
                            @php
                                $sizes = [];
                            @endphp
                            @foreach ($item->product_detail as $detail)
                                @if (!in_array($detail->size->size, $sizes))
                                    {{ $detail->size->size }}
                                    @php
                                        $sizes[] = $detail->size->size;
                                    @endphp
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @php
                                $colors = [];
                            @endphp
                            @foreach ($item->product_detail as $detail)
                                @if (!in_array($detail->color->name_color, $colors))
                                    {{ $detail->color->name_color }}
                                    @php
                                        $colors[] = $detail->color->name_color;
                                    @endphp
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $item->view }}</td>
                        <td>
                            <a href="{{ route('products.edit', $item->id) }}" class="btn btn-outline-warning">Edit</a>
                            <form action="{{ route('products.destroy', $item->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"
                                    onclick="return confirm('Chắc chắn muốn xóa sản phẩm này?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection