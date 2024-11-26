@extends('Layout.Layout')

@section('title')
    DashBoard Admin
@endsection

@section('content_admin')
    <div class="container text-center mt-5 mb-3">
        <h2>DashBoard Admin</h2>
    </div>
    <!-- Menu -->
    <div class="container mt-4">
        <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button"
                    role="tab">
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

        <!-- Nội dung của từng tab -->
        <div class="mt-4">
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

    <script>
        // Hàm tải dữ liệu qua AJAX
        function loadTabContent(tabId, url) {
            const contentDiv = document.getElementById(`${tabId}-content`);
            if (!contentDiv || !url) return;

            contentDiv.innerHTML = '<div class="text-center">Đang tải dữ liệu...</div>';

            fetch(url)
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
            tab.addEventListener('shown.bs.tab', function (event) {
                const tabId = event.target.dataset.bsTarget.replace('#', '');
                const url = document.getElementById(`${tabId}-content`).dataset.url;
                loadTabContent(tabId, url);
            });
        });

        // Tải dữ liệu của tab đầu tiên
        document.addEventListener('DOMContentLoaded', () => {
            const activeTab = document.querySelector('.nav-link.active');
            const tabId = activeTab.dataset.bsTarget.replace('#', '');
            const url = document.getElementById(`${tabId}-content`).dataset.url;
            loadTabContent(tabId, url);
        });
    </script>
@endsection
