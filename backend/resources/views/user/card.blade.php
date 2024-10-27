
@extends('user.master')

@section('title')
    Dashboard
@endsection

@section('content')
<div class="container">
    <h1 class="text-center">Danh Sách Thẻ ATM</h1>

    <a href="{{route('card.create')}}" class="btn btn-success">Thêm thẻ</a>

    <div class="row">
        @foreach ($cards as $card)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $card->card_name }}</h5>
                        <p class="card-text">Số thẻ: {{ $card->card_number }}</p>
                        <p class="card-text">Ngày hết hạn: {{ $card->expiration_date->format('d/m/Y') }}</p>
                        <p class="card-text">Ngày phát hành: {{ $card->issue_date ? $card->issue_date->format('d/m/Y') : 'Chưa có' }}</p>
                        <p class="card-text">CVV: {{ $card->cvv }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <a href="{{ route('cards.edit', $card->id) }}" class="btn btn-sm btn-outline-secondary">Sửa</a>
                                <form action="{{ route('cards.destroy', $card->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection