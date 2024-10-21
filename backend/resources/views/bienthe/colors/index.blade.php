@extends('Layout.Layout')

@section('title')
    DashBoard Admin - Colors
@endsection

@section('content_admin')


<a class="btn btn-outline-success mb-3" href="{{ route('colors.create') }}">Add new color</a>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name color</th>
                <th scope="col">Created at</th>
                <th scope="col">Updated at</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $color)
                <tr>
                    <td>{{ $color->id }}</td>
                    <td>{{ $color->name_color }}</td>
                    <td>{{ $color->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $color->updated_at->format('d/m/Y H:i') }}</td>
                    <td>
                        {{-- <a class="btn btn-info" href="{{ route('colors.show', $color->id) }}">Xem</a> --}}
                        <a class="btn btn-outline-warning mb-3" href="{{ route('colors.edit', $color->id) }}">Edit</a>
                        <form action="{{ route('colors.destroy', $color->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')" class="btn btn-outline-danger mb-3">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}
</div>
@endsection
