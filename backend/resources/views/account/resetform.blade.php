<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cập nhật mật khẩu</title>
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
    
    <form action="{{ route('password.update') }}" method="POST" class="container bg-light mt-5"
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
</body>

</html>
