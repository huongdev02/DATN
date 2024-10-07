<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Quên mật khẩu</title>
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

    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <h1 class="text-center m-5">Quên mật khẩu</h1>
        <div class="container bg-light" style="width: 400px; height: 350px;">

            <div class="mb-3 mt-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email" name="email"
                    required>
            </div>

            <button type="submit" class="btn btn-primary mb-3">Submit</button>

            <p>Chưa có tài khoản: <a href="{{ route('register') }}">Chuyển đến đăng kí</a></p>
            <p>Đã có tài khoản: <a href="{{ route('login') }}">Chuyển đến đăng nhập</a></p>
            <a class="text-secondary" href="/">Quay lại rang chủ</a>
        </div>
        @csrf
    </form>
</body>

</html>
