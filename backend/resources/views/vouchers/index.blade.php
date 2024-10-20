@extends('master')
@section('title')
    Danh sách voucher
@endsection
@section('content')
    <h1>
        Danh sách voucher
        <a class="btn btn-info" href="{{ route('vouchers.create') }}">Create</a>
    </h1>

    @if (session()->has('success') && !session()->get('success'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    @if (session()->has('success') && session()->get('success'))
        <div class="alert alert-info">
            Thao tác thành công
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-primary">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Code</th>
                    <th scope="col">Type</th>
                    <th scope="col">Discount Value</th>
                    <th scope="col">Status</th>
                    <th scope="col">Created_at</th>
                    <th scope="col">Updated_at</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vouchers as $voucher)
                    <tr class="">
                        <td scope="row">{{ $voucher->id }}</td>
                        <td>{{ $voucher->code }}</td>
                        <td>{{ $voucher->type }}</td>
                        <td>{{ $voucher->discount_value }}</td>
                        <td>{{ $voucher->status }}</td>
                        <td>{{ $voucher->created_at }}</td>
                        <td>{{ $voucher->updated_at }}</td>
                        <td>
                            <a class="btn btn-info" href="{{ route('vouchers.show', $voucher) }}">Show</a>
                            <a class="btn btn-warning" href="{{ route('vouchers.edit', $voucher) }}">Edit</a>

                            <form action="{{ route('vouchers.destroy', $voucher) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Có chắc xóa không?')" class="btn btn-danger">
                                    Xóa
                                </button>

                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
