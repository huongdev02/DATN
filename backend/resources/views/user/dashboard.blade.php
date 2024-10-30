@extends('user.master')

@section('title')
    Dashboard
@endsection

@section('content')
    <a href="http://localhost:3000/" class="btn btn-primary"> Quay về trang chủ</a>

    @if(isset($token))
        <p>Token của bạn: {{ $token }}</p>
    @else
        <p>Không tìm thấy token.</p>
    @endif
@endsection