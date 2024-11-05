@extends('Layout.Layout')

@section('title')
    Cập nhật Size
@endsection

@section('content_admin')
    <h1 class="text-center">Cập nhật Size</h1>
    <div class="container">
        <form method="POST" action="{{ route('sizes.update', $size->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3 row">
                <label for="size" class="col-4 col-form-label">Size</label>
            
                    <input type="text" class="form-control" name="size" id="size"
                        value="{{ old('size', $size->size) }}" required />
     
            </div>

            <div class="mb-3 row">
                <div class="offset-sm-4 col-sm-8">
                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                    <a href="{{route('sizes.index')}}" class="btn btn-secondary"> Quay lại</a>
                </div>
            </div>
        </form>
    </div>
@endsection
