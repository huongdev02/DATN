@extends('user.master')

@section('title')
    Thêm Thẻ ATM
@endsection

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Thêm Thẻ ATM</h1>

    <form action="{{ route('card.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="card_number" class="form-label">Số thẻ:</label>
            <input type="text" name="card_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="card_name" class="form-label">Tên chủ thẻ:</label>
            <input type="text" name="card_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="expiration_date" class="form-label">Ngày hết hạn:</label>
            <input type="date" name="expiration_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="issue_date" class="form-label">Ngày phát hành:</label>
            <input type="date" name="issue_date" class="form-control">
        </div>

        <div class="mb-3">
            <label for="cvv" class="form-label">CVV:</label>
            <input type="text" name="cvv" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Thêm thẻ</button>
    </form>
</div>
@endsection
