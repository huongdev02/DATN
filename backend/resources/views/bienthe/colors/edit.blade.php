@extends('Layout.Layout')

@section('title')
    Cập nhật Màu: {{ $color->name_color }}
@endsection

@section('content_admin')
    <h1>Cập nhật Màu: {{ $color->name_color }}</h1>

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
        <form method="POST" action="{{ route('colors.update', $color->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3 row">
                <label for="name_color" class="col-4 col-form-label">Tên Màu</label>
                <div class="col-8">
                    <input type="text" class="form-control" name="name_color" id="name_color"
                        value="{{ old('name_color', $color->name_color) }}" required />
                </div>
            </div>

            <div class="mb-3 row">
                <div class="offset-sm-4 col-sm-8">
                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                </div>
            </div>
        </form>
    </div>
@endsection
