@extends('Layout.Layout')

@section('tiltle')
    DashBoard Admin
@endsection

@section('content_admin')
    <div class="container text-center mt-5 mb-3">
        <h2>DashBoard Admin</h2>
    </div>

    <!-- Thống kê người dùng -->
    <div class="container mt-4">
        <h4>Thống kê người dùng mới</h4>
        <div class="form-group">
            <select id="timeframe-select" class="form-control">
                <option value="this_week">--- Chọn khoảng thời gian ---</option>
                <option value="this_week">Tuần này</option>
                <option value="this_month">Tháng này</option>
                <option value="last_week">Tuần trước</option>
                <option value="last_month">Tháng trước</option>
            </select>
        </div>

        <div id="statistics" class="mt-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Số lượng người dùng mới</h5>
                    <p class="card-text" id="userCount">0 người dùng mới</p>
                    <p class="card-text" id="changeIndicator">
                        So với tuần trước/tháng trước:
                        <span id="changeValue" class="font-weight-bold">0</span>
                        <i id="changeIcon" class="fas"></i> <!-- Biểu tượng mũi tên -->
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê doanh thu -->
    <div class="container mt-4">
        <h4>Thống kê doanh thu</h4>
        <div id="revenueStatistics" class="mt-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Tổng Doanh Thu</h5>
                    <p class="card-text" id="totalRevenue">0 VNĐ</p>
                    <p class="card-text" id="revenueChangeIndicator">
                        Thay đổi:
                        <span id="revenueChangeValue" class="font-weight-bold">0</span>
                        <i id="revenueChangeIcon" class="fas"></i> <!-- Biểu tượng mũi tên doanh thu -->
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome cho biểu tượng mũi tên -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#timeframe-select').change(function() {
                let timeframe = $(this).val();

                $.ajax({
                    url: '{{ route("admin.dashboard") }}',
                    type: 'GET',
                    data: { timeframe: timeframe },
                    success: function(data) {
                        // Cập nhật thông tin người dùng
                        $('#userCount').text(data.account.count + ' người dùng mới');

                        let changeText = data.account.change >= 0 ? '+' + data.account.change : data.account.change;
                        $('#changeValue').text(changeText);
                        
                        // Cập nhật biểu tượng thay đổi người dùng
                        $('#changeIcon').removeClass('fa-arrow-up fa-arrow-down text-success text-danger');
                        if (data.account.change > 0) {
                            $('#changeIcon').addClass('fa-arrow-up text-success');
                        } else if (data.account.change < 0) {
                            $('#changeIcon').addClass('fa-arrow-down text-danger');
                        } else {
                            $('#changeIcon').addClass('fa-equals text-muted');
                        }

                        // Cập nhật thông tin doanh thu
                        $('#totalRevenue').text(data.order.total + ' VNĐ');
                        
                        let revenueChangeText = data.order.change >= 0 ? '+' + data.order.change : data.order.change;
                        $('#revenueChangeValue').text(revenueChangeText);
                        
                        // Cập nhật biểu tượng thay đổi doanh thu
                        $('#revenueChangeIcon').removeClass('fa-arrow-up fa-arrow-down text-success text-danger');
                        if (data.order.change > 0) {
                            $('#revenueChangeIcon').addClass('fa-arrow-up text-success');
                        } else if (data.order.change < 0) {
                            $('#revenueChangeIcon').addClass('fa-arrow-down text-danger');
                        } else {
                            $('#revenueChangeIcon').addClass('fa-equals text-muted');
                        }
                    }
                });
            });

            // Gọi AJAX ban đầu để load dữ liệu mặc định (Tuần này)
            $('#timeframe-select').trigger('change');
        });
    </script>
@endsection