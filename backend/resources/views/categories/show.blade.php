@extends('master')

@section('content')
    <h1>Category Details</h1>
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Name: {{ $category->name }}</h5>
        </div>
    </div>
    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning mt-3">Edit</a>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary mt-3">Back</a>

    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="mt-3">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
@endsection
