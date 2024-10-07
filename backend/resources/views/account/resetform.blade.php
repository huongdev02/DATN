@extends('account.master')

@section('title')
    Khôi phục tài khoản
@endsection

@section('content')
    <form action="{{ route('password.forgot') }}" method="POST" class="container bg-light mt-5"
        style="width: 500px; height: 350px;">
        <h1>Cập nhật mật khẩu mới</h1>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <label for="email">Email</label>
        <input type="email" name="email" class="form-control mt-3 mb-3" value="" disabled>

        <label for="password">Mật khẩu mới</label>
        <input type="password" name="password" class="form-control mt-3 mb-3">

        <label for="password_confirmation">xác nhận mật khẩu</label>
        <input type="password" name="password_confirmation" class="form-control mt-3 mb-3">

        <button type="submit" class="btn btn-success">Cập nhật mật khẩu</button>
    </form>
@endsection
