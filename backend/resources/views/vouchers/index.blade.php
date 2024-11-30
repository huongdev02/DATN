@extends('Layout.Layout')

@section('content_admin')
<h1 class="text-center">Danh sách voucher</h1>

<form method="GET" action="{{ route('vouchers.index') }}" id="filterForm" class="mb-3 p-3">
    <div class="row">
        <div class="col-md-4">
            <label for="status" class="form-label fw-bold">Trạng thái</label>
            <select name="status" id="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                <option value="" class="text-dark">Tất cả trạng thái</option>
                <option value="1" class="text-dark" {{ request('status') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="0" class="text-dark" {{ request('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                <option value="2" class="text-dark" {{ request('status') == '2' ? 'selected' : '' }}>Đã hết hạn</option>
                <option value="3" class="text-dark" {{ request('status') == '3' ? 'selected' : '' }}>Chờ phát hành</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="type" class="form-label fw-bold">Loại Voucher</label>
            <select name="type" id="type" class="form-select" onchange="document.getElementById('filterForm').submit()">
                <option value="" class="text-dark">Tất cả loại</option>
                <option value="0" class="text-dark" {{ request('type') == '0' ? 'selected' : '' }}>Giá trị cố định</option>
                <option value="1" class="text-dark" {{ request('type') == '1' ? 'selected' : '' }}>Triết khấu phần trăm</option>
            </select>
        </div>
    </div>
</form>

<h1>
    <a class="btn btn-outline-success mb-3 mt-3" href="{{ route('vouchers.create') }}">Add new voucher</a>
</h1>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Code</th>
                <th scope="col">Type</th>
                <th scope="col">Discount Value</th>
                <th scope="col">Description</th>
                <th scope="col">Status</th>
                <th scope="col">Start</th>
                <th scope="col">End</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vouchers as $voucher)
                <tr>
                    <td>{{ $voucher->id }}</td>
                    <td>{{ $voucher->code }}</td>
                    <td>
                        @if($voucher->type == 0)
                            Giá trị cố định
                        @else
                            Triết khấu phần trăm
                        @endif
                    </td>
                    <td>{{ $voucher->discount_value ?? 'N/A' }}</td>
                    <td>{{ $voucher->description ?? 'N/A' }}</td>
                    <td>
                        @switch($voucher->status)
                            @case(0)
                                Không hoạt động
                                @break
                            @case(1)
                                Đang hoạt động
                                @break
                            @case(2)
                                Đã hết hạn
                                @break
                            @case(3)
                                Chờ phát hành
                                @break
                            @default
                                Không rõ
                        @endswitch
                    </td>
                    <td>{{ \Carbon\Carbon::parse($voucher->start_day)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($voucher->end_day)->format('d-m-Y') }}</td>
                    <td>
                        <a class="btn btn-outline-warning mb-3" href="{{ route('vouchers.edit', $voucher->id) }}">Edit</a>
                        <form action="{{ route('vouchers.destroy', $voucher->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Có chắc xóa không?')" class="btn btn-outline-danger mb-3">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Phân trang --}}
    <div class="pagination justify-content-center">
        {{ $vouchers->links() }}
    </div>
</div>
@endsection
