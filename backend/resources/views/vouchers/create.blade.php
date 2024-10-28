@extends('Layout.Layout')

@section('content_admin')
    <h1>Thêm mới voucher</h1>
    <div class="container">
        <form method="POST" action="{{ route('vouchers.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
                <label for="code" class="col-2 col-form-label">Code:</label>
                <div class="col-8">
                    <input type="text" class="form-control" name="code" id="code" value="{{ old('code') }}" required>
                    <button type="button" class="btn btn-secondary mt-2" id="generateCodeBtn">Tạo mã ngẫu nhiên</button>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="type" class="col-2 col-form-label">Type:</label>
                <div class="col-8">
                    <select name="type" id="type" class="form-select" required>
                        <option value="0" {{ old('type') == 0 ? 'selected' : '' }}>Giá trị cố định</option>
                        <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>Triết khấu phần trăm</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3 row">
                <label for="discount_value" class="col-2 col-form-label">Giá trị giảm giá</label>
                <div class="col-8">
                    <input type="text" placeholder="exam: 100.000 VND or 10%" class="form-control" name="discount_value" id="discount_value"
                        value="{{ old('discount_value') }}" required/>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="description" class="col-2 col-form-label">Mô tả</label>
                <div class="col-8">
                    <textarea class="form-control" name="description" id="description" rows="5">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="discount_min" class="col-2 col-form-label">Giảm tối thiểu</label>
                <div class="col-8">
                    <input type="number" class="form-control" step="1" name="discount_min" id="discount_min" value="{{ old('discount_min', 0) }}" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="max_discount" class="col-2 col-form-label">Giảm tối đa</label>
                <div class="col-8">
                    <input type="number" class="form-control" step="1" name="max_discount" id="max_discount" value="{{ old('max_discount') }}" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="min_order_count" class="col-2 col-form-label">Số lượng đặt hàng tối thiểu</label>
                <div class="col-8">
                    <input type="text" class="form-control" name="min_order_count" id="min_order_count" value="{{ old('min_order_count', 1) }}" required placeholder="exam: 1.000">
                </div>
            </div>
            
            <div class="mb-3 row">
                <label for="max_order_count" class="col-2 col-form-label">Số lượng đặt hàng tối đa</label>
                <div class="col-8">
                    <input type="text" class="form-control" name="max_order_count" id="max_order_count" value="{{ old('max_order_count', 1) }}" required placeholder="exam: 10.000">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="quantity" class="col-2 col-form-label">Số lượng</label>
                <div class="col-8">
                    <input type="number" class="form-control" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="used_times" class="col-2 col-form-label">Số lần sử dụng:</label>
                <div class="col-8">
                    <input type="number" class="form-control" name="used_times" id="used_times" value="0" disabled >
                    <input type="number" class="form-control" name="used_times" id="used_times" value="0" hidden >
                </div>
            </div>

            <div class="mb-3 row">
                <label for="start_day" class="col-2 col-form-label">Ngày bắt đầu</label>
                <div class="col-8">
                    <input type="date" class="form-control" name="start_day" id="start_day" value="{{ old('start_day') }}">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="end_day" class="col-2 col-form-label">Ngày kết thúc</label>
                <div class="col-8">
                    <input type="date" class="form-control" name="end_day" id="end_day" value="{{ old('end_day') }}">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="status" class="col-2 col-form-label">Status:</label>
                <div class="col-8">
                    <select name="status" class="form-control"   id="status">
                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Không hoạt động</option>
                        <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Đã hết hạn</option>
                        <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Chờ phát hành</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="offset-sm-4 col-sm-8">
                    <button type="submit" class="btn btn-outline-success ">
                        Create
                    </button>
                    <a href="{{route('vouchers.index')}}" class="btn btn-outline-secondary">Quay lại</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Hàm định dạng số với dấu phân cách hàng nghìn
        function formatNumber(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Thêm dấu chấm
        }
    
        // Hàm tự động định dạng giá trị nhập cho ô giảm tối thiểu và tối đa
        function setupInputFormatting(inputId) {
            const inputField = document.getElementById(inputId);
            
            inputField.addEventListener('input', function() {
                let value = this.value.replace(/\./g, ''); // Xóa dấu chấm
                if (!isNaN(value) && value !== "") {
                    this.value = formatNumber(value); // Định dạng số
                } else {
                    this.value = ''; // Nếu không phải số, xóa ô
                }
            });
    
            // Khi người dùng rời khỏi ô nhập (blur), định dạng lại
            inputField.addEventListener('blur', function() {
                let value = this.value.replace(/\./g, ''); // Xóa dấu chấm
                if (value === "") {
                    this.value = '0'; // Nếu không có giá trị, mặc định là 0
                } else {
                    this.value = formatNumber(value); // Định dạng số
                }
            });
        }
    
        // Áp dụng định dạng cho ô Giảm tối thiểu
        setupInputFormatting('discount_min');
    
        // Áp dụng định dạng cho ô Giảm tối đa
        setupInputFormatting('max_discount');
    
        // Tạo mã ngẫu nhiên
        document.getElementById('generateCodeBtn').addEventListener('click', function() {
            const codeLength = 5;
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let randomCode = '';
        
            for (let i = 0; i < codeLength; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                randomCode += characters.charAt(randomIndex);
            }
        
            document.getElementById('code').value = randomCode;
        });
    
        // Thiết lập định dạng cho discount_value
        const typeSelect = document.getElementById('type');
        const discountInput = document.getElementById('discount_value');
    
        discountInput.addEventListener('input', function() {
            let value = this.value.replace(/\./g, '').replace('%', ''); // Xóa dấu chấm và dấu phần trăm
            const type = typeSelect.value;
    
            if (type == 0) {
                // Nếu là giá trị cố định
                if (!isNaN(value) && value !== "") {
                    this.value = formatNumber(value); // Định dạng số
                } else {
                    this.value = ''; // Nếu không phải số, xóa ô
                }
            } else if (type == 1) {
                // Nếu là triết khấu phần trăm
                if (!isNaN(value) && value !== "") {
                    this.value = value + '%'; // Thêm dấu %
                } else {
                    this.value = ''; // Nếu không phải số, xóa ô
                }
            }
        });
    
        // Thay đổi placeholder và xóa ký tự không phù hợp
        typeSelect.addEventListener('change', function() {
            const type = this.value;
            if (type == 0) {
                discountInput.placeholder = "exam: 100.000";
                discountInput.value = discountInput.value.replace('%', ''); // Xóa ký tự %
            } else if (type == 1) {
                discountInput.placeholder = "exam: 10%";
                discountInput.value = discountInput.value.replace(/\.\d{0,3}/, ''); // Xóa giá trị cố định
            }
        });
    
        // Áp dụng định dạng cho các ô nhập cần thiết
        setupInputFormatting('min_order_count');
        setupInputFormatting('max_order_count');
    </script>
    
    

@endsection
