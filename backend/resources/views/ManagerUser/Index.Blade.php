@extends('Layout.Layout')

@section('content_admin')
<div class="container">
    <h1>Danh sách Người dùng</h1>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Avatar</th>
                <th>Tên Đăng Nhập</th>
                <th>Họ Tên</th>
                <th>Ngày Sinh</th>
                <th>Số Điện Thoại</th>
                <th>Địa Chỉ</th>
                <th>Email</th>
                <th>Vai Trò</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td><img src="{{ $user->avatar }}" alt="Avatar" style="width: 50px; height: 50px;"></td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->fullname }}</td>
                <td>{{ $user->birth_day }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->address }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
