
@extends('Layout.Layout')

@section('title')
Create
@endsection

@section('content_admin')
<div class="container">
    <h1>{{ isset($logoBanner) ? 'Edit Logo/Banner' : 'Add New Logo/Banner' }}</h1>

    <form action="{{ isset($logoBanner) ? route('logo_banners.update', $logoBanner->id) : route('logo_banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($logoBanner))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="type">Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="1" {{ isset($logoBanner) && $logoBanner->type == 1 ? 'selected' : '' }}>Banner</option>
                <option value="2" {{ isset($logoBanner) && $logoBanner->type == 2 ? 'selected' : '' }}>Logo</option>
            </select>
        </div>

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $logoBanner->title ?? old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $logoBanner->description ?? old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" class="form-control" value="{{ $logoBanner->image ?? old('image') }}" required>
        </div>

        <div class="form-group">
            <label for="is_active">Active</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="1" {{ isset($logoBanner) && $logoBanner->is_active ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ isset($logoBanner) && !$logoBanner->is_active ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">{{ isset($logoBanner) ? 'Update' : 'Create' }}</button>
    </form>
</div>
@endsection
