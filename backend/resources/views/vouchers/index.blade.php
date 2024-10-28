@extends('Layout.Layout')

@section('content_admin')
    <h1>
        <a class="btn btn-outline-success mb-3" href="{{ route('vouchers.create') }}">Add new voucher</a>
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
                        <td>{{ $voucher->discount_value }}</td>
                        <td>{{ $voucher->description }}</td>
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
                            {{-- <a class="btn btn-info" href="{{ route('vouchers.show', $voucher->id) }}">Show</a> --}}
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
