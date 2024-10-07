<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>DashBoard User</title>
</head>

<body>

    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="container text-center mt-5">
        <h2>DashBoard User</h2>
        <p>Đây là trang chỉ dành cho thành viên đăng nhập</p>

        <p>Chào bạn
            <strong>
                @if (Auth::user()->fullname)
                    {{ Auth::user()->fullname }}
                @else
                    {{ Auth::user()->username }}
                @endif
            </strong>
        </p>
        <p>Địa chỉ email của bạn là: <strong> 
            @if (Auth::user()->email)
                {{ Auth::user()->email }}
            @else
                bạn chưa thêm địa chỉ email
            @endif</strong> </p>
            
        @if (Auth::user()->email_verified_at == null)
            Trang thái email: <p style="color: red">Chưa xác thức</p>
            <a href="{{route('verify.form')}}" class="btn btn-primary">xác minh email</a>
        @elseif (Auth::user()->email_verified_at != null)
            Trang thái email: <p style="color: green">Đã xác thức</p>
        @endif

        <a href="{{route('edit')}}" class="btn btn-warning">Cập nhật tài khoản</a>
        <a href="{{route('changepass.form')}}" class="btn btn-primary">Thay đổi mật khẩu</a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger mt-3">Log Out</button>
        </form>
    </div>
</body>

</html>
