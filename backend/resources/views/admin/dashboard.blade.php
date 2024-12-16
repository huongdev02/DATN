@extends('Layout.Layout')

@section('title')
    Trang chủ
@endsection

@section('content_admin')
    <div class="container text-center mt-5 mb-3">
        <h2>Trang chủ quản trị viên</h2>
    </div>
    <div class="dashboard-stats mt-3">
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
            <h2>Chưa Xử Lí </h2>
            <p>{{ $pendingOrders }} Đơn hàng</p>
            <a class="btn btn-success" href="{{ route('orders.index') }}">Xử lí ngay</a>
        </div>

        <div class="stat-item">
            <h2>Đoạn Chat</h2>
            <p>? chua xu li</p>
            <button class="btn btn-success" id="openModalBtn">Xử lý ngay</button>
        </div>

        <!-- Modal Hiển Thị Danh Sách Hội Thoại -->
        <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="chatModalLabel">Danh sách hội thoại</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul id="conversationList" class="list-group">
                            <!-- Danh sách hội thoại sẽ được thêm vào đây bằng JavaScript -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Popup Chi Tiết Tin Nhắn -->
        <div id="chatPopup" class="chat-popup">
            <div class="chat-header">
                <h5 id="chatPopupTitle">Chi tiết hội thoại</h5>
                <button id="closeChatPopup" class="btn-close"></button>
            </div>
            <div id="messageList" class="chat-messages">
                <!-- Danh sách tin nhắn sẽ được thêm vào đây -->
            </div>
            <div class="chat-footer">
                <input type="text" id="chatInput" class="form-control" placeholder="Nhập tin nhắn...">
                <button id="sendMessageBtn" class="btn btn-primary">Gửi</button>
            </div>
        </div>

        <style>
            .chat-popup {
                display: none;
                position: fixed;
                bottom: 0;
                right: 20px;
                width: 300px;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }

            .chat-header {
                padding: 10px;
                background: #007bff;
                color: #fff;
                border-top-left-radius: 8px;
                border-top-right-radius: 8px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .chat-messages {
                max-height: 300px;
                overflow-y: auto;
                padding: 10px;
            }

            .message-item {
                margin-bottom: 10px;
            }

            .message-item.sent {
                text-align: right;
            }

            .message-item.received {
                text-align: left;
            }

            .message-content {
                display: inline-block;
                padding: 8px 12px;
                border-radius: 8px;
                background: #f1f1f1;
                max-width: 80%;
            }

            .message-item.sent .message-content {
                background: #007bff;
                color: #fff;
            }

            .message-time {
                font-size: 12px;
                color: #888;
            }
        </style>


        <script>
            document.getElementById("openModalBtn").addEventListener("click", function() {
                // Hiển thị Modal khi nhấn nút "Xử lý ngay"
                const modal = new bootstrap.Modal(document.getElementById("chatModal"));
                modal.show();

                // Gửi AJAX để lấy danh sách hội thoại
                fetchConversations();
            });

            function fetchConversations() {
                fetch('{{ route('adminchat.index') }}') // Gọi route để lấy dữ liệu hội thoại
                    .then((response) => response.json())
                    .then((data) => {
                        const conversationList = document.getElementById("conversationList");
                        conversationList.innerHTML = ""; // Xóa danh sách cũ

                        if (data.length > 0) {
                            data.forEach((conversation) => {
                                const listItem = document.createElement("li");
                                listItem.classList.add("list-group-item", "d-flex", "justify-content-between",
                                    "align-items-center");
                                listItem.innerHTML = `
                        Hội thoại với <strong>${conversation.email?.fullname ?? "Người dùng ẩn danh"}</strong>
                        <button class="btn btn-sm btn-primary open-chat-btn" data-id="${conversation.id}">
                            Xem chi tiết
                        </button>
                    `;

                                // Gắn sự kiện click để mở chi tiết hội thoại
                                listItem.querySelector(".open-chat-btn").addEventListener("click", function() {
                                    openChatPopup(conversation.id);
                                });

                                conversationList.appendChild(listItem);
                            });
                        } else {
                            conversationList.innerHTML = '<li class="list-group-item">Không có hội thoại nào</li>';
                        }
                    })
                    .catch((error) => {
                        console.error("Lỗi khi tải danh sách hội thoại:", error);
                    });
            }

            function openChatPopup(conversationId) {
                // Gửi AJAX để lấy dữ liệu hội thoại
                fetch(`/adminchat/${conversationId}`)
                    .then((response) => response.json())
                    .then((data) => {
                        const chatPopupTitle = document.getElementById("chatPopupTitle");
                        const messageList = document.getElementById("messageList");

                        // Cập nhật tiêu đề của popup
                        chatPopupTitle.textContent = `Hội thoại với ${data.user?.fullname ?? "Người dùng ẩn danh"}`;

                        // Xóa danh sách tin nhắn cũ
                        messageList.innerHTML = "";

                        // Hiển thị tin nhắn trong hội thoại
                        if (data.messages.length > 0) {
                            data.messages.forEach((message) => {
                                const messageItem = document.createElement("div");
                                messageItem.classList.add("message-item", message.sender_id === data.user_id ?
                                    "sent" : "received");

                                messageItem.innerHTML = `
                        <div class="message-content">${message.message}</div>
                        <div class="message-time">${new Date(message.created_at).toLocaleString()}</div>
                    `;

                                messageList.appendChild(messageItem);
                            });
                        } else {
                            messageList.innerHTML = `<div class="no-messages">Không có tin nhắn nào</div>`;
                        }

                        // Hiển thị popup
                        document.getElementById("chatPopup").style.display = "block";
                    })
                    .catch((error) => {
                        console.error("Lỗi khi tải chi tiết hội thoại:", error);
                    });
            }
            // Đóng popup
            document.getElementById("closeChatPopup").addEventListener("click", function() {
                document.getElementById("chatPopup").style.display = "none";
            });

            document.getElementById('sendMessageBtn').addEventListener('click', function() {
                const messageInput = document.getElementById('chatInput');
                const message = messageInput.value.trim();
                const conversationId = conversation.id;

                if (!message) {
                    return; // Nếu không có tin nhắn, không gửi
                }

                // Gửi yêu cầu AJAX để gửi tin nhắn
                fetch(`/adminchat/${conversationId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'), // Đảm bảo CSRF token
                        },
                        body: JSON.stringify({
                            message: message
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Cập nhật danh sách tin nhắn trong chat
                            updateMessageList(data.messages);
                            messageInput.value = ''; // Clear input
                        } else {
                            alert('Có lỗi xảy ra khi gửi tin nhắn');
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi gửi tin nhắn:', error);
                    });
            });

            function updateMessageList(messages) {
                const messageList = document.getElementById('messageList');
                messageList.innerHTML = ''; // Clear tin nhắn hiện tại

                // Thêm tất cả tin nhắn vào danh sách
                messages.forEach(msg => {
                    const messageItem = document.createElement('div');
                    messageItem.classList.add('message-item', msg.sender_id === msg.user_id ? 'sent' : 'received');
                    messageItem.innerHTML = `
            <div class="message-content">${msg.message}</div>
            <div class="message-time">${new Date(msg.created_at).toLocaleString()}</div>
        `;
                    messageList.appendChild(messageItem);
                });
            }
        </script>


    </div>

    <!-- Menu -->
    <div class="container mt-4">
        <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account"
                    type="button" role="tab">
                    Tài khoản
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button"
                    role="tab">
                    Doanh thu - Dơn hàng
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="top-products-tab" data-bs-toggle="tab" data-bs-target="#top-products"
                    type="button" role="tab">
                    Sản phẩm bán chạy
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tonkho-tab" data-bs-toggle="tab" data-bs-target="#tonkho" type="button"
                    role="tab">
                    Tồn kho - Sắp hết
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="voucher-tab" data-bs-toggle="tab" data-bs-target="#voucher" type="button"
                    role="tab">
                    Voucher
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tiledon-tab" data-bs-toggle="tab" data-bs-target="#tiledon" type="button"
                    role="tab">
                    Tỉ lệ đơn
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="khachhang-tab" data-bs-toggle="tab" data-bs-target="#khachhang"
                    type="button" role="tab">
                    Khách hàng
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
            <div class="tab-pane fade" id="tonkho" role="tabpanel">
                <div id="tonkho-content" data-url="{{ route('thongke.tonkho') }}"></div>
            </div>
            <div class="tab-pane fade" id="voucher" role="tabpanel">
                <div id="voucher-content" data-url="{{ route('thongke.voucher') }}"></div>
            </div>
            <div class="tab-pane fade" id="tiledon" role="tabpanel">
                <div id="tiledon-content" data-url="{{ route('thongke.tiledon') }}"></div>
            </div>
            <div class="tab-pane fade" id="khachhang" role="tabpanel">
                <div id="khachhang-content" data-url="{{ route('thongke.khachhang') }}"></div>
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

        // Cập nhật giá trị `current_url` khi tải trang ban đầu
        document.addEventListener('DOMContentLoaded', () => {
            updateCurrentUrl();

            document.querySelectorAll('.nav-link').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(event) {
                    updateCurrentUrl();
                });
            });

            function updateCurrentUrl() {
                const activeTab = document.querySelector('.nav-link.active');
                const tabId = activeTab.dataset.bsTarget.replace('#', '');
                const url = document.getElementById(`${tabId}-content`).dataset.url;
                document.getElementById('current-url').value = url;
            }

            const startDateInput = document.getElementById('start-date');
            const endDateInput = document.getElementById('end-date');

            const firstDayOfMonth = new Date();
            firstDayOfMonth.setDate(1);
            const formattedStartDate = firstDayOfMonth.toISOString().split('T')[0];

            const today = new Date();
            const formattedEndDate = today.toISOString().split('T')[0];

            startDateInput.value = formattedStartDate;
            endDateInput.value = formattedEndDate;
        });

        // Lọc dữ liệu khi submit form
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
