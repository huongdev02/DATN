@extends('Layout.Layout')

@section('title', 'index Blog')

@section('content_admin')
<div class="container">
    <h1>Blog List</h1>
    <a href="{{ route('blog.create') }}" class="btn btn-primary">Add New Blog</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Image</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($blogs as $blog)
                <tr>
                    <td>{{ $blog->id }}</td>
                    <td>{{ $blog->title }}</td>
                    <td>{{ $blog->category->name ?? 'N/A' }}</td>
                    <td><img src="{{ asset('storage/' . $blog->image) }}" width="50" height="50"></td>
                    <td>{{ $blog->is_active ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('blog.edit', $blog->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('blog.destroy', $blog->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
