@extends('Layout.Layout')

@section('title')
    Thêm mới danh mục
@endsection

@section('content_admin')
    <h1 class="text-center mt-5">Thêm danh mục</h1>

    <form action="{{ route('categories.store') }}" method="POST" class="mt-3">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label mt-3">Trạng thái</label>
            <div class="mt-3 mb-3">
                <label>
                    <input type="radio" name="is_active" value="1" {{ old('is_active', 1) == 1 ? 'checked' : '' }}>
                    Hoạt động
                </label>
                <label class="ms-3">
                    <input type="radio" name="is_active" value="0" {{ old('is_active') == 0 ? 'checked' : '' }}>
                    Không hoạt động
                </label>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success">Thêm mới</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
@endsection
