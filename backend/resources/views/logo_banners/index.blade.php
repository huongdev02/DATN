@extends('Layout.Layout')

@section('title')
index
@endsection

@section('content_admin')
<div class="container">
    <h1>Logo/Banners Management</h1>

    <a href="{{ route('logo_banners.create') }}" class="btn btn-primary mb-3">Add New</a>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logoBanners as $banner)
                <tr>
                    <td>{{ $banner->id }}</td>
                    <td>{{ $banner->type == 1 ? 'Banner' : 'Logo' }}</td>
                    <td>{{ $banner->title }}</td>
                    <td>{{ $banner->description }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $banner->image) }}" alt="Image" style="width: 50px;">
                    </td>
                    <td>{{ $banner->is_active ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('logo_banners.edit', $banner->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('logo_banners.destroy', $banner->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No Logo/Banners found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
