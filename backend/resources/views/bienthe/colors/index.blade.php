@extends('bienthe.master')
@section('title')
    Danh sách
@endsection
@section('content')
    <h1>
        Danh sách
        <a class="btn btn-info" href="{{ route('colors.create') }}">Create</a>
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
                    <th scope="col">NAME</th>
                    <th scope="col">CREATE AT</th>
                    <th scope="col">UPDATED AT</th>
                    <th scope="col">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $color)
                    <tr class="">
                        <td scope="row">{{ $color->id }}</td>
                        <td>{{ $color->name_color }}</td>
                        <td>{{ $color->created_at }}</td>
                        <td>{{ $color->updated_at }}</td>
                        <td>
                            <a class="btn btn-info" href="{{ route('colors.show', $color) }}">Show</a>
                            <a class="btn btn-warning" href="{{ route('colors.edit', $color) }}">Edit</a>

                            <form action="{{ route('colors.destroy', $color) }}" method="POST">
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
