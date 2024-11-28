@extends('Layout.Layout')

@section('title')
    DashBoard Admin
@endsection

@section('content_admin')
    <div class="container text-center mt-5 mb-3">
        <h2>DashBoard Admin</h2>
    </div>
    <div class="dashboard-stats">
        <div class="stat-item">
            <h2>Tổng Doanh Thu</h2>
            <p>{{ number_format($totalRevenue, 0, ',', '.') }} VND</p>
        </div>
        <div class="stat-item">
            <h2>Tổng Thành Viên</h2>
            <p>{{ $totalUsers }} Thành viên</p>
        </div>
        <div class="stat-item">
            <h2>Đã Hoàn Thành</h2>
            <p>{{ $completedOrders }} Đơn hàng</p>
        </div>
        <div class="stat-item">
            <h2>Đơn Hàng Chưa Xử Lý</h2>
            <p>{{ $pendingOrders }} Đơn hàng</p>
            <a class="btn btn-success" href="{{route('orders.index')}}">Xử lí ngay</a>
        </div>
    </div>


    <!-- Menu -->
    <div class="container mt-4">
        <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account"
                    type="button" role="tab">
                    Thống kê Tài khoản
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button"
                    role="tab">
                    Thống kê Đơn hàng
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="top-products-tab" data-bs-toggle="tab" data-bs-target="#top-products"
                    type="button" role="tab">
                    Sản phẩm bán chạy
                </button>
            </li>
        </ul>

        <div class="mt-5 mb-5">
            <form id="filter-stats-form" action="" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="start-date" class="form-label">Từ ngày:</label>
                    <input type="date" id="start-date" name="start_date" class="form-control"
                        value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end-date" class="form-label">Đến ngày:</label>
                    <input type="date" id="end-date" name="end_date" class="form-control"
                        value="{{ request('end_date') }}">
                </div>
                <!-- Input ẩn để lưu URL hiện tại -->
                <input type="hidden" id="current-url" name="current_url" value="">
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                </div>
            </form>
        </div>

        

        <!-- Nội dung của từng tab -->
        <div class="tab-content mt-4">
            <div class="tab-pane fade show active" id="account" role="tabpanel">
                <div id="account-content" data-url="{{ route('thongke.account') }}"></div>
            </div>
            <div class="tab-pane fade" id="orders" role="tabpanel">
                <div id="orders-content" data-url="{{ route('thongke.orders') }}"></div>
            </div>
            <div class="tab-pane fade" id="top-products" role="tabpanel">
                <div id="top-products-content" data-url="{{ route('thongke.topproduct') }}"></div>
            </div>
        </div>
    </div>


    <style>
        .dashboard-stats {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .stat-item {
            flex: 1;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .stat-item h2 {
            margin-bottom: 10px;
            font-size: 20px;
            color: #333;
        }

        .stat-item p {
            font-size: 18px;
            color: #555;
            font-weight: bold;
        }
    </style>
    <script>
        // Hàm tải dữ liệu qua AJAX
        function loadTabContent(tabId, url) {
            const contentDiv = document.getElementById(`${tabId}-content`);
            if (!contentDiv || !url) return;

            // Lấy giá trị từ đầu tháng đến hôm nay
            const today = new Date();
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const formattedStartDate = startOfMonth.toISOString().split('T')[0];
            const formattedEndDate = today.toISOString().split('T')[0];

            // Gửi request với start_date và end_date
            const params = new URLSearchParams({
                start_date: formattedStartDate,
                end_date: formattedEndDate,
            });

            contentDiv.innerHTML = '<div class="text-center">Đang tải dữ liệu...</div>';

            fetch(`${url}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.text())
                .then(data => {
                    contentDiv.innerHTML = data;
                })
                .catch(error => {
                    contentDiv.innerHTML = '<div class="text-danger">Không thể tải dữ liệu. Vui lòng thử lại.</div>';
                    console.error('Error loading tab content:', error);
                });
        }


        // Lắng nghe sự kiện tab thay đổi
        document.querySelectorAll('.nav-link').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(event) {
                const tabId = event.target.dataset.bsTarget.replace('#', '');
                const url = document.getElementById(`${tabId}-content`).dataset.url;
                loadTabContent(tabId, url);
            });
        });

        // Tải dữ liệu của tab đầu tiên khi load trang
        document.addEventListener('DOMContentLoaded', () => {
            const activeTab = document.querySelector('.nav-link.active');
            const tabId = activeTab.dataset.bsTarget.replace('#', '');
            const url = document.getElementById(`${tabId}-content`).dataset.url;
            loadTabContent(tabId, url);
        });


        // Tải dữ liệu của tab đầu tiên
        document.addEventListener('DOMContentLoaded', () => {
            const activeTab = document.querySelector('.nav-link.active');
            const tabId = activeTab.dataset.bsTarget.replace('#', '');
            const url = document.getElementById(`${tabId}-content`).dataset.url;
            loadTabContent(tabId, url);
        });

        

        document.addEventListener('DOMContentLoaded', () => {
            // Cập nhật giá trị `current_url` khi tải trang ban đầu
            updateCurrentUrl();

            // Lắng nghe sự kiện khi chuyển tab
            document.querySelectorAll('.nav-link').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(event) {
                    updateCurrentUrl();
                });
            });

            // Hàm cập nhật giá trị `current_url` dựa trên tab đang hoạt động
            function updateCurrentUrl() {
                const activeTab = document.querySelector('.nav-link.active');
                const tabId = activeTab.dataset.bsTarget.replace('#', '');
                const url = document.getElementById(`${tabId}-content`).dataset.url;
                document.getElementById('current-url').value = url;
            }

            // Cập nhật giá trị mặc định cho "Từ ngày" và "Đến ngày"
            const startDateInput = document.getElementById('start-date');
            const endDateInput = document.getElementById('end-date');

            // Ngày đầu tháng
            const firstDayOfMonth = new Date();
            firstDayOfMonth.setDate(1);
            const formattedStartDate = firstDayOfMonth.toISOString().split('T')[0];

            // Ngày hôm nay
            const today = new Date();
            const formattedEndDate = today.toISOString().split('T')[0];

            // Đặt giá trị vào các input
            startDateInput.value = formattedStartDate;
            endDateInput.value = formattedEndDate;

            // Lắng nghe sự kiện khi thay đổi tab để tải lại dữ liệu
            document.querySelectorAll('.nav-link').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(event) {
                    const tabId = event.target.dataset.bsTarget.replace('#', '');
                    const url = document.getElementById(`${tabId}-content`).dataset.url;
                    loadTabContent(tabId, url);
                });
            });

            // Tải dữ liệu của tab đầu tiên
            const activeTab = document.querySelector('.nav-link.active');
            const tabId = activeTab.dataset.bsTarget.replace('#', '');
            const url = document.getElementById(`${tabId}-content`).dataset.url;
            loadTabContent(tabId, url);
        });


        //loc
        document.getElementById('filter-stats-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const form = event.target;
            const url = document.getElementById('current-url').value;
            const formData = new FormData(form);

            fetch(url + '?' + new URLSearchParams(formData).toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.text())
                .then(data => {
                    const activeTab = document.querySelector('.nav-link.active');
                    const tabId = activeTab.dataset.bsTarget.replace('#', '');
                    const contentDiv = document.getElementById(`${tabId}-content`);
                    contentDiv.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
@endsection
