<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Đăng nhập</title>
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

    <form action="{{route('login')}}" method="POST">
        @csrf
        <h1 class="text-center m-5">Đăng nhập</h1>
        <div class="container bg-light" style="width: 400px; height: 350px;">

            <div class="mb-3 mt-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email" name="email"
                    required>
            </div>

            <div class="mb-4">
                <label for="pwd" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" placeholder="Enter password" name="password"
                    required>
            </div>

            <button type="submit" class="btn btn-primary mb-3">Submit</button>

            <p>Chưa có tài khoản: <a href="{{ route('register.form') }}">Đăng kí</a></p>
            <p> <a class="text-primary" href="{{ route('password.forgot.form') }}">Quên mật khẩu</a></p>
            <a class="text-secondary" href="/">Quay lại rang chủ</a>
        </div>
        @csrf
    </form>

</body>

</html>
