@extends('Layout.Layout')
@section('content_admin')
    <a href="{{ route('promotion.create') }}" class="btn btn-outline-success mb-3">Add New Promotion</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product ID</th>
                <th>Start Day</th>
                <th>End Day</th>
                <th>Price Discount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promotions as $promotion)
                <tr>
                    <td>{{ $promotion->id }}</td>
                    <td>{{ $promotion->product_id }}</td>
                    <td>{{ $promotion->start_day }}</td>
                    <td>{{ $promotion->end_day }}</td>
                    <td>{{ $promotion->price_discount }}</td>
                    <td>
                        <a href="{{ route('promotion.edit', $promotion->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('promotion.destroy', $promotion->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
