@extends('account.master')

@section('title')
    Xác minh email tài khoản
@endsection

@section('content')
    <form action="/verify" method="POST">
        @csrf
        <h1 class="text-center m-5">Xác minh email</h1>
        <div class="container bg-light" style="width: 400px; height: 220px;">

            <div class="mb-3 mt-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
            </div>

            <button type="submit" class="btn btn-primary mb-3">Submit</button>

            <p><a class="text-secondary" href="/user">Quay lại dashboard</a></p>
        </div>
        @csrf
    </form>
@endsection
