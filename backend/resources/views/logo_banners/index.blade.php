@extends('Layout.Layout')

@section('title')
    Danh sách Logo - Banner
@endsection

@section('content_admin')
    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-center mt-5 mb-3">Danh sách Logo - Banner</h1>

    <a href="{{ route('logo_banners.create') }}" class="btn btn-primary mb-3">Thêm mới</a>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Loại</th>
                <th>Tiêu đề</th>
                <th>Mô tả</th>
                <th>Hình ảnh</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logoBanners as $banner)
                <tr>
                    <td>{{ $banner->id }}</td>
                    <td>{{ $banner->type == 1 ? 'Banner' : 'Logo' }}</td>
                    <td>{{ $banner->title }}</td>
                    <td>{{ $banner->description }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $banner->image) }}" alt="Image" style="width: 50px;">
                    </td>
                    <td>  <span class="badge {{ $banner->is_active ? 'badge-success' : 'badge-danger' }}">
                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                    </span></td>
                    <td>
                        <a href="{{ route('logo_banners.edit', $banner->id) }}" class="btn btn-warning btn-sm">Cập nhật</a>
                        <form action="{{ route('logo_banners.destroy', $banner->id) }}" method="POST"
                            style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Không có Logo/Banners.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
