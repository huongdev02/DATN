@extends('Layout.Layout')
@section('title')
Create size
@endsection
@section('content_admin')
<h1>Create size </h1>
<div class="container">
    <form method="POST" action="{{ route('sizes.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3 row">
            <label for="size" class="col-4 col-form-label">Size</label>
            <div class="col-8">
                <input type="text" class="form-control" name="size" id="size" value="{{ old('size') }}" />
            </div>
        </div>

        <div class="mb-3 row">
            <div class="offset-sm-4 col-sm-8">
                <button type="submit" class="btn btn-primary">
                    Submit
                </button>
            </div>
        </div>
    </form>
</div>

@endsection
