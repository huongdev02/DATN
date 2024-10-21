@extends('Layout.Layout')

@section('content_admin')
    <h1>Create New Category</h1>


    <form action="{{ route('categories.store') }}" method="POST" class="mt-3">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <button type="submit" class="btn btn-success">Create</button>
    </form>
@endsection
