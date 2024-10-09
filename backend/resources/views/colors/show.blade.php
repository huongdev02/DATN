@extends('master')
@section('title')
    Xem chi tiết : {{ $color->name_color }}
@endsection
@section('content')
    <h1>Xem chi tiết : {{ $color->name_color }}</h1>
    <div class="table-responsive">
        <table class="table table-primary">
            <thead>
                <tr>
                    <th scope="col">Tên trường</th>
                    <th scope="col">Giá trị</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($color->toArray() as $key => $value )
                    <tr>
                        <td scope="row">{{strtoupper($key)}}</td>
                        <td>{{$value}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
