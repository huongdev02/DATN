<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Nhúng Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('user/style.css') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>

<body class="container mt-5">
    <div class="d-flex">
        <!-- Phần Menu -->
        <div class="menu-container bg-light p-3">
            <a href="{{ route('user.edit') }}">
                <div class="nav-profile-text d-flex align-items-center">

                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" width="50px" alt="profile"
                        class="img-profile rounded-circle" />
                    <span class="login-status online"></span>

                    <div class="ms-2">
                        <span class="d-block" style="color: cadetblue">Xin chào</span>
                        <span
                            style="color: purple">{{ Auth::user()->fullname ?? (Auth::user()->email ?? Auth::user()->username) }}</span>
                    </div>

                    <i class="fa-solid fa-circle ms-2 mt-4" style="color: green; font-size: 10px;"></i>
                </div>
            </a>
            <ul class="menu list-unstyled">
                <li><a href="http://localhost:3000" class="btn btn-light w-100 text-start">Quay lại trang chủ</a></li>
                <li class="dropdown">
                    <button class="dropdown-btn btn btn-light w-100 text-start" onclick="toggleDropdown(this)">
                        <i class="icon-user"></i> Tài Khoản Của Tôi
                    </button>
                    <ul class="dropdown-content list-unstyled ps-3" style="display: block;"> <!-- Đặt display: block -->
                        <li><a href="{{ route('user.edit') }}">Hồ Sơ</a></li>
                        <li><a href="{{ route('address.index') }}">Địa Chỉ</a></li>
                        <li><a href="{{ route('user.changepass.form') }}">Đổi Mật Khẩu</a></li>
                        <a href="javascript:void(0)" class="chat-icon btn btn-primary" onclick="openChatPopup()">
                            <i class="fa-solid fa-comments"></i>
                        </a>
                        <!-- Modal Popup -->
                        <div id="chatPopup" class="modal fade" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Giao tiếp với quản trị viên</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Nội dung đoạn chat -->
                                        <div id="chatMessages" style="max-height: 400px; overflow-y: auto;">
                                            <!-- Chat messages sẽ được load từ AJAX -->
                                        </div>
                                        <!-- Input để gửi tin nhắn -->
                                        <form id="chatForm" onsubmit="sendMessage(event)">
                                            <div class="input-group mt-3">
                                                <input type="text" class="form-control" id="messageInput"
                                                    placeholder="Nhập tin nhắn...">
                                                <button type="submit" class="btn btn-primary">Gửi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            // Mở popup
                            function openChatPopup() {
                                // Hiển thị modal
                                const chatPopup = new bootstrap.Modal(document.getElementById('chatPopup'));
                                chatPopup.show();

                                // Tải đoạn chat từ server
                                loadChatMessages();
                            }

                            // AJAX để tải đoạn chat
                            function loadChatMessages() {
                                const chatMessages = document.getElementById('chatMessages');
                                chatMessages.innerHTML = '<p>Đang tải...</p>';

                                fetch('{{ route('userchat.index') }}') // Route để lấy tin nhắn
                                    .then(response => response.json())
                                    .then(data => {
                                        chatMessages.innerHTML = '';
                                        data.messages.forEach(msg => {
                                            const messageDiv = document.createElement('div');
                                            messageDiv.className = msg.sender_id === {{ Auth::id() }} ? 'text-end' :
                                            'text-start';
                                            messageDiv.innerHTML = `<p><strong>${msg.sender_name}</strong>: ${msg.message}</p>`;
                                            chatMessages.appendChild(messageDiv);
                                        });
                                    })
                                    .catch(err => {
                                        chatMessages.innerHTML = '<p>Không thể tải tin nhắn. Vui lòng thử lại.</p>';
                                        console.error(err);
                                    });
                            }

                            // Gửi tin nhắn
                            function sendMessage(event) {
                                event.preventDefault();

                                const messageInput = document.getElementById('messageInput');
                                const chatMessages = document.getElementById('chatMessages');

                                if (messageInput.value.trim() === '') return;

                                // Gửi tin nhắn qua AJAX
                                fetch('{{ route('userchat.store') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({
                                            message: messageInput.value
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        // Thêm tin nhắn vào giao diện
                                        const messageDiv = document.createElement('div');
                                        messageDiv.className = 'text-end';
                                        messageDiv.innerHTML =
                                            `<p><strong>{{ Auth::user()->fullname ?? 'Bạn' }}</strong>: ${messageInput.value}</p>`;
                                        chatMessages.appendChild(messageDiv);

                                        // Cuộn xuống cuối
                                        chatMessages.scrollTop = chatMessages.scrollHeight;

                                        // Xóa nội dung input
                                        messageInput.value = '';
                                    })
                                    .catch(err => {
                                        console.error('Lỗi khi gửi tin nhắn:', err);
                                        alert('Không thể gửi tin nhắn. Vui lòng thử lại.');
                                    });
                            }
                        </script>


                    </ul>
                </li>
                <li><a href="{{ route('userorder.index') }}" class="btn btn-light w-100 text-start">Đơn Mua</a></li>
                <li><a href="{{ route('uservouchers.index') }}" class="btn btn-light w-100 text-start">Kho Voucher</a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn badge bg-danger ms-3 mt-2"
                            onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">Đăng xuất</button>
                    </form>
                </li>
            </ul>
        </div>

        <style>
            /* Định vị icon chat ở góc phải màn hình */
            .chat-icon {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 60px;
                height: 60px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                z-index: 999;
                /* Đảm bảo luôn nằm trên các phần tử khác */
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            }

            /* Màu nền và hiệu ứng hover */
            .chat-icon {
                background-color: #007bff;
                /* Màu xanh của Bootstrap */
                color: white;
                text-decoration: none;
                transition: background-color 0.3s ease;
            }

            .chat-icon:hover {
                background-color: #0056b3;
                /* Màu xanh đậm hơn khi hover */
            }
        </style>

        <!-- Phần Content -->
        <div class="content-container p-4 flex-grow-1">
            @if ($errors->any())
                <div class="alert alert-danger text-center">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info">
                    {{ session('info') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>


    <!-- Nhúng Bootstrap JavaScript -->
    <script src="{{ asset('user/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
