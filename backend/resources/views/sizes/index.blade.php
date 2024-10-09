@extends('master')
@section('title')
    Danh sách
@endsection
@section('content')
    <h1>
        Danh sách
        <a class="btn btn-info" href="{{ route('sizes.create') }}">Create</a>
    </h1>

    @if(session()->has('success') && !session()->get('success'))
        <div class="alert alert-danger">
            {{ session()->get('error')}}
        </div>
    @endif

    @if(session()->has('success') && session()->get('success'))
        <div class="alert alert-info">
            Thao tác thành công
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-primary">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">SIZE</th>
                    <th scope="col">CREATE AT</th>
                    <th scope="col">UPDATED AT</th>
                    <th scope="col">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $size)
                    <tr class="">
                        <td scope="row">{{ $size->id }}</td>
                        <td>{{ $size->size }}</td>
                        <td>{{ $size->created_at }}</td>
                        <td>{{ $size->updated_at }}</td>
                        <td>
                            <a class="btn btn-info" href="{{ route('sizes.show', $size) }}">Show</a>
                            <a class="btn btn-warning" href="{{ route('sizes.edit', $size) }}">Edit</a>

                            <form action="{{ route('sizes.destroy', $size) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return cònirm('Có chắc xóa không?')" class="btn btn-danger">
                                    Xóa
                                </button>

                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{$data->links()}}
    </div>
@endsection
