@extends('account.master')

@section('title')
    Đổi mật khẩu
@endsection

@section('content')
    <h1 class="text-center m-5">Đăng nhập</h1>
    <form action="{{ route('password.change') }}" method="POST">
        @csrf

        <div>
            <label for="current_password">Mật khẩu hiện tại</label>
            <input type="password" class="form-control" name="current_password" id="current_password" required>
        </div>

        <div>
            <label for="new_password">Mật khẩu mới</label>
            <input type="password" class="form-control" name="new_password" id="new_password" required>
        </div>

        <div>
            <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
            <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-success text-center">Đổi mật khẩu</button>
    </form>
@endsection
