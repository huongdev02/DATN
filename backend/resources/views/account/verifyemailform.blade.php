<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Xác minh email</title>
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


    <p class="text-center mt-5">Chào bạn <strong>{{ Auth::user()->name }}</strong> </p>
    <p class="text-center mt-2">email của bạn là: <strong>{{ Auth::user()->email }}</strong> </p>
    

    <form action="/verify" method="POST">
        @csrf
        <h1 class="text-center m-5">Xác minh email</h1>
        <div class="container bg-light" style="width: 400px; height: 220px;">

            <div class="mb-3 mt-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email" name="email"
                    required>
            </div>

            <button type="submit" class="btn btn-primary mb-3">Submit</button>

            <p><a class="text-secondary" href="/user">Quay lại dashboard</a></p>
        </div>
        @csrf
    </form>
</body>

</html>
