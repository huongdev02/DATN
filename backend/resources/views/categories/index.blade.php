@extends('Layout.Layout')

@section('content_admin')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('categories.create') }}" class="btn btn-outline-success mb-3">Add new category</a>
    </div>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-outline-warning mb-3">Edit</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger mb-3" onclick="return confirm('Bạn có chắc muốn xóa không?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $categories->links() }} <!-- Hiển thị phân trang nếu có -->
    </div>
@endsection
