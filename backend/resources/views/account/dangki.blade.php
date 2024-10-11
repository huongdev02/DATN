@extends('account.master')

@section('title')
    Đăng kí tài khoản
@endsection

@section('content')
    <form action="{{ route('register') }}" class="container bg-light" style="width: 600px; height: 600px;" method="POST">
        @csrf
        @method('post')

        <h1 class="text-center m-5">Đăng kí tài khoản</h1>
        <div class="mb-3 mt-3">
            <label for="name" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" placeholder="Enter Username" name="username" value="{{old('username')}}">
        </div>

        <div class="mb-3">
            <label for="pwd" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" >
        </div>

        <div class="mb-3">
            <label for="pwd" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm password"
                name="password_confirmation" >
        </div>

        <button type="submit" class="btn btn-primary mb-1 text-center">Đăng kí</button>

        <p>Đã có tài khoản: <a href="{{ route('login') }}">Đăng nhập</a></p>
        <p> <a class="text-primary" href="{{ route('password.forgot.form') }}">Quên mật khẩu</a></p>
        <a class="text-secondary" href="/">Quay lại rang chủ</a>

        @csrf
    </form>
@endsection
