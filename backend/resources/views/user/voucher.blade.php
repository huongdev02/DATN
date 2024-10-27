@extends('user.master')

@section('title')
    Danh sách Voucher
@endsection

@section('content')
    <div class="container mt-4">
        <!-- Header -->
        <div class="row align-items-center mb-3">
            <div class="col-md-8">
                <h1>Kho Voucher</h1>
            </div>
            <div class="col-md-4 text-end">
                <input type="text" class="form-control d-inline w-75" placeholder="Nhập mã voucher tại đây">
                <button class="btn btn-primary d-inline">Lưu</button>
            </div>
        </div>

        <!-- Voucher List -->
        <div class="row">
            @foreach ($vouchers as $voucher)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span class="badge bg-danger">{{ $voucher->type == 0 ? 'Shop Style' : 'Voucher Độc Quyền' }}</span>
                            </div>
                            <h5 class="card-title mt-2">Giảm {{ $voucher->discount_value }}% Giảm tối đa {{ $voucher->max_discount }}đ</h5>
                            <p class="card-text">Đơn tối thiểu: {{ $voucher->discount_min }}đ</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Có hiệu lực: {{ $voucher->used_times }}</small>
                                <button class="btn btn-outline-primary">Dùng Sau</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection