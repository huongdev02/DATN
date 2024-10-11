<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>DashBoard Admin</title>
</head>

<body>
    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="container text-center mt-5">
        <h2>DashBoard Admin</h2>
        <p>Chào bạn <strong>{{ Auth::user()->name }}</strong> </p>
        <p>Đây là trang chỉ dành cho Admin</p>

        <a href="/loaitin"><button class="btn btn-primary m-3">Chuyển đến trang quản trị</button></a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Log Out</button>
        </form>
    </div>
</body>

</html>
