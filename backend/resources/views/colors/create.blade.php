@extends('master')
@section('title')
Thêm mới
@endsection
@section('content')
<h1>Thêm mới </h1>
@if(session()->has('success') && !session()->get('success'))
    <div class="alert alert-danger">
        {{ session()->get('error')}}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
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
