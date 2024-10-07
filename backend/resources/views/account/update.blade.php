@extends('account.master')

@section('title')
    Cập nhật tài khoản
@endsection

@section('content')
    <h1 class="text-center m-5">Cập nhật tài khoản</h1>

    <form action="{{ route('update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="fullname">Full Name</label>
        <input type="text" class="form-control mb-3" name="fullname" id="fullname" value="{{ old('fullname', Auth::user()->fullname) }}">

        <label for="birth_day">Birth Day</label>
        <input type="date" class="form-control mb-3" name="birth_day" id="birth_day" value="{{ old('birth_day', Auth::user()->birth_day) }}">

        <label for="phone">Phone</label>
        <input type="text" class="form-control mb-3" name="phone" id="phone" value="{{ old('phone', Auth::user()->phone) }}">

        <label for="email">Email</label>
        <input type="email" class="form-control mb-3" name="email" id="email" value="{{ old('email', Auth::user()->email) }}">

        <label for="address">Address</label>
        <input type="text" class="form-control mb-3" name="address" id="address" value="{{ old('address', Auth::user()->address) }}">

        <label for="avatar">Avatar</label>
        <img src="{{ \Storage::url(Auth::user()->image)}}" width="50px">
        <input type="file" class="form-control mb-3" name="avatar" id="avatar">

        <button type="submit" class="btn btn-success mt-3 text-center">Cập nhật</button>
        <a href="{{route('user.dashboard')}}" class="btn btn-secondary">Quay lai</a>
    </form>
@endsection
