@extends('account.master')

@section('title')
    Đăng nhập
@endsection

@section('content')
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <h1 class="text-center m-5">Đăng nhập</h1>
        <div class="container bg-light" style="width: 400px; height: 350px;">

            <div class="mb-3 mt-3">
                <label for="account" class="form-label">Tài khoản</label>
                <input type="text" class="form-control" id="account" placeholder="Enter account"name="account" value="{{old('account')}}">
            </div>

            <div class="mb-4">
                <label for="pwd" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
            </div>

            <button type="submit" class="btn btn-primary mb-3">Submit</button>

            <p>Chưa có tài khoản: <a href="{{ route('register.form') }}">Đăng kí</a></p>
            <p> <a class="text-primary" href="{{ route('password.forgot.form') }}">Quên mật khẩu</a></p>
            <a class="text-secondary" href="/">Quay lại rang chủ</a>
        </div>
        @csrf
    </form>
@endsection
