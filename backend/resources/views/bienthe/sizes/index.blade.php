@extends('Layout.Layout')

@section('title')
    Danh sách Size
@endsection

@section('content_admin')

    <a class="btn btn-outline-success mb-3" href="{{ route('sizes.create') }}">Thêm mới</a>

<div class="table-responsive">
    <table class="table table-bordered table-hover text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Size</th>
                <th>Tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $size)
                <tr>
                    <td>{{ $size->id }}</td>
                    <td>{{ $size->size }}</td>
                    <td>{{ $size->created_at ? $size->created_at->format('d/m/Y H:i') : 'N/A' }}</td>

                    <td>
                        {{-- <a class="btn btn-info" href="{{ route('sizes.show', $size->id) }}">Xem</a> --}}
                        <a class="btn btn-outline-warning mb-3" href="{{ route('sizes.edit', $size->id) }}">Edit</a>

                        <form action="{{ route('sizes.destroy', $size->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')" class="btn btn-outline-danger mb-3">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}
</div>
@endsection
