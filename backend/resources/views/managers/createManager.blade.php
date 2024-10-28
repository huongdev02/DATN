@extends('Layout.Layout')

@section('title')
    Thêm Quản lý 
@endsection

@section('content_admin')
<div class="container mt-2">
    <h1 class="mb-3 text-center">Thêm Quản lý mới</h1>
    <form action="{{ route('managers.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label>Mật Khẩu</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <div class="form-group">
            <label>Xác Nhận Mật Khẩu</label>
            <input type="password" class="form-control" name="password_confirmation" required>
        </div>
        
        <button type="submit" class="btn btn-primary mt-3">Thêm Quản lý</button>
    </form>
</div>
@endsection
