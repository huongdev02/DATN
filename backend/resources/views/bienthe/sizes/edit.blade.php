@extends('Layout.Layout')

@section('title')
    Cập nhật Kích Thước: {{ $size->size }}
@endsection

@section('content_admin')
    <h1>Cập nhật Kích Thước: {{ $size->size }}</h1>

    @if (session()->has('success') && !session()->get('success'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    @if (session()->has('success') && session()->get('success'))
        <div class="alert alert-info">
            Thao tác thành công
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
        <form method="POST" action="{{ route('sizes.update', $size->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3 row">
                <label for="size" class="col-4 col-form-label">Kích Thước</label>
                <div class="col-8">
                    <input type="text" class="form-control" name="size" id="size"
                        value="{{ old('size', $size->size) }}" required />
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
