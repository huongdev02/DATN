<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Đăng kí</title>
</head>

<body>

    @if ($errors->any())
        <div class="alert alert-danger text-center">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('register') }}" class="container bg-light" style="width: 600px; height: 600px;" method="POST">
        @csrf
        <h1 class="text-center m-5">Đăng kí tài khoản</h1>
        <div class="mb-3 mt-3">
            <label for="name" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" placeholder="Enter Username" name="username" required>
        </div>

        <div class="mb-3">
            <label for="pwd" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Enter password" name="password"
                required>
        </div>

        <div class="mb-3">
            <label for="pwd" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm password"
                name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary mb-1 text-center">Đăng kí</button>

        <p>Đã có tài khoản: <a href="{{ route('login') }}">Đăng nhập</a></p>
        <p> <a class="text-primary" href="{{ route('password.forgot.form') }}">Quên mật khẩu</a></p>
        <a class="text-secondary" href="/">Quay lại rang chủ</a>

        @csrf
    </form>
</body>

</html>
