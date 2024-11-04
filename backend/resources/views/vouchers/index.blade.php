@extends('Layout.Layout')

@section('content_admin')
    <h1>
        <a class="btn btn-outline-success mb-3" href="{{ route('vouchers.create') }}">Add new voucher</a>
    </h1>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('vouchers.index') }}" class="mb-3 p-3 rounded bg-light shadow-sm" id="filterForm">
        <div class="row">
            <div class="col-md-4">
                <label for="status" class="form-label fw-bold">Trạng thái</label>
                <select name="status" id="status" class="form-select text-white bg-primary" onchange="document.getElementById('filterForm').submit()">
                    <option value="" class="text-dark">Tất cả trạng thái</option>
                    <option value="1" class="text-dark" {{ request('status') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="0" class="text-dark" {{ request('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                    <option value="2" class="text-dark" {{ request('status') == '2' ? 'selected' : '' }}>Đã hết hạn</option>
                    <option value="3" class="text-dark" {{ request('status') == '3' ? 'selected' : '' }}>Chờ phát hành</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="type" class="form-label fw-bold">Loại Voucher</label>
                <select name="type" id="type" class="form-select text-white bg-success" onchange="document.getElementById('filterForm').submit()">
                    <option value="" class="text-dark">Tất cả loại</option>
                    <option value="0" class="text-dark" {{ request('type') == '0' ? 'selected' : '' }}>Giá trị cố định</option>
                    <option value="1" class="text-dark" {{ request('type') == '1' ? 'selected' : '' }}>Triết khấu phần trăm</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Voucher List -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Code</th>
                    <th scope="col">Loại</th>
                    <th scope="col">Hiệu lực</th>
                    <th scope="col">Giảm tối thiểu</th>
                    <th scope="col">Giảm tối đa</th>
                    <th scope="col">Đặt tối thiểu</th>
                    <th scope="col">Đặt tối đa</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vouchers as $voucher)
                    <tr>
                        <td>{{ $voucher->id }}</td>
                        <td>{{ $voucher->code }}</td>
                        <td>{{ $voucher->type == 0 ? 'Giá trị cố định' : 'Triết khấu phần trăm' }}</td>
                        <td>
                            @php
                                $remainingDays = now()->diffInDays($voucher->end_day, false);
                            @endphp
                            @if ($remainingDays > 0)
                                <span class="text-success fw-bold">{{ $remainingDays }} ngày còn lại</span>
                            @else
                                <span class="text-danger fw-bold">Hết hạn</span>
                            @endif
                        </td>
                        <td>{{ number_format($voucher->discount_min, 2) }}</td>
                        <td>{{ number_format($voucher->max_discount, 2) }}</td>
                        <td>{{ $voucher->min_order_count }}</td>
                        <td>{{ $voucher->max_order_count }}</td>
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
                        <td>
                            <a class="btn btn-outline-warning" href="{{ route('vouchers.edit', $voucher->id) }}">Edit</a>
                            <form action="{{ route('vouchers.destroy', $voucher->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Có chắc xóa không?')" class="btn btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">
                            @if ($status || $type)
                                Không có voucher phù hợp với bộ lọc.
                            @else
                                Không có dữ liệu voucher.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination justify-content-center">
            {{ $vouchers->links() }}
        </div>
    </div>
@endsection
