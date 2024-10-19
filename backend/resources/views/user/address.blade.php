@extends('user.master')

@section('title')
    Địa chỉ
@endsection

@section('content')
    <h1 class="text-center"> Địa chỉ của tôi</h1>
    <a href="{{ route('address.create') }}" class="btn btn-success">Thêm mới</a>

    <ul class="list-group mt-5">
        @foreach ($addresses as $address)
            <li class="list-group-item {{ $address->is_default ? 'list-group-item-primary' : '' }}">
                <strong>Tên người nhận:</strong> {{ $address->recipient_name }}<br>
                <strong>Địa chỉ:</strong> {{ $address->ship_address }}<br>
                <strong>Số điện thoại:</strong> {{ $address->phone_number }}<br>
                @if ($address->is_default)
                    <span class="badge bg-primary">Địa chỉ mặc định</span>
                @else
                    <form action="{{ route('address.set-default', $address->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-outline-primary">Đặt làm địa chỉ mặc định</button>
                    </form>
                @endif
                <a href="{{ route('address.edit', $address->id) }}" class="btn btn-sm btn-warning float-end">Cập nhật</a>
            </li>
        @endforeach
    </ul>
    
@endsection