@extends('Layout.Layout')

@section('title')
Create color
@endsection
@section('content_admin')
<h1>Create color </h1>
<div class="container">
    <form method="POST" action="{{ route('colors.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3 row">
            <label for="name_color" class="col-4 col-form-label">Name</label>
            <div class="col-8">
                <input type="text" class="form-control" name="name_color" id="name_color" value="{{ old('name_color') }}" />
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
