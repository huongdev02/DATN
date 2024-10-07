@extends('account.master')

@section('title')
    Khôi phục tài khoản
@endsection

@section('content')
    <form action="{{ route('password.reset') }}" method="POST">
        @csrf
        <h1 class="text-center m-5">Quên mật khẩu</h1>
        <div class="container bg-light" style="width: 400px; height: 350px;">

            <div class="mb-3 mt-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
            </div>

            <button type="submit" class="btn btn-primary mb-3">Submit</button>

            <p>Chưa có tài khoản: <a href="{{ route('register.form') }}">Chuyển đến đăng kí</a></p>
            <p>Đã có tài khoản: <a href="{{ route('login') }}">Chuyển đến đăng nhập</a></p>
            <a class="text-secondary" href="/">Quay lại rang chủ</a>
        </div>
        @csrf
    </form>
@endsection
