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


                        <!-- Icon mở chat -->
                        <a href="javascript:void(0)" class="chat-icon btn btn-primary" onclick="toggleChatPopup()"
                            style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
                            <i class="fa-solid fa-comments"></i>
                        </a>

                        <!-- Chat Popup -->
                        <div id="chatPopup" class="chat-popup shadow-lg" style="display: none;">
                            <div class="chat-header">
                                <h5>Giao tiếp với quản trị viên</h5>
                                <button type="button" class="close-btn" onclick="toggleChatPopup()">&times;</button>
                            </div>
                            <div class="chat-body" id="chatMessages">
                                <!-- Tin nhắn sẽ được tải qua AJAX -->
                                <p>Đang tải...</p>
                            </div>
                            <div class="chat-footer">
                                <form id="chatForm" onsubmit="sendMessage(event)">
                                    <input type="text" id="messageInput" placeholder="Nhập tin nhắn..."
                                        class="form-control" />
                                    <button type="submit" class="btn btn-primary">Gửi</button>
                                </form>
                            </div>
                        </div>

                        <script>
                            // Toggle chat popup visibility
                            function toggleChatPopup() {
                                const chatPopup = document.getElementById('chatPopup');
                                const isVisible = chatPopup.style.display === 'block';
                                chatPopup.style.display = isVisible ? 'none' : 'block';

                                // Nếu hiển thị, tải tin nhắn
                                if (!isVisible) loadChatMessages();
                            }

                            // Load chat messages
                            function loadChatMessages() {
                                const chatMessages = document.getElementById('chatMessages');
                                chatMessages.innerHTML = '<p>Đang tải...</p>';

                                fetch('{{ route('userchat.index') }}')
                                    .then(response => response.json())
                                    .then(data => {
                                        chatMessages.innerHTML = '';
                                        if (data.length === 0) {
                                            chatMessages.innerHTML = '<p>Không có tin nhắn nào.</p>';
                                            return;
                                        }

                                        // Nếu có quá nhiều tin nhắn, hiển thị ba chấm
                                        const MAX_MESSAGES = 10;
                                        if (data.length > MAX_MESSAGES) {
                                            chatMessages.innerHTML = '<p>...</p>'; // Hiển thị ba chấm nếu có quá nhiều tin nhắn
                                        }

                                        // Duyệt qua các tin nhắn và hiển thị
                                        data.slice(-MAX_MESSAGES).forEach(conversation => { // Lấy 10 tin nhắn cuối
                                            conversation.messages.forEach(msg => {
                                                const messageDiv = document.createElement('div');
                                                messageDiv.className = msg.sender_id === {{ Auth::id() }} ? 'text-end' :
                                                    'text-start';
                                                messageDiv.innerHTML =
                                                    `<p><strong>${msg.sender_name}</strong>: ${msg.message}</p>`;
                                                chatMessages.appendChild(messageDiv);
                                            });
                                        });

                                        // Cuộn xuống cuối
                                        chatMessages.scrollTop = chatMessages.scrollHeight;

                                        // Thêm sự kiện cuộn chuột để xem tin nhắn cũ
                                        chatMessages.addEventListener('scroll', function() {
                                            if (chatMessages.scrollTop === 0) {
                                                loadMoreMessages(); // Nếu cuộn lên đầu, tải thêm tin nhắn
                                            }
                                        });
                                    })
                                    .catch(err => {
                                        chatMessages.innerHTML = '<p>Không thể tải tin nhắn. Vui lòng thử lại.</p>';
                                        console.error(err);
                                    });
                            }

                            // Load thêm tin nhắn cũ khi cuộn lên
                            function loadMoreMessages() {
                                const chatMessages = document.getElementById('chatMessages');
                                chatMessages.innerHTML = '<p>Đang tải thêm...</p>';

                                fetch('{{ route('userchat.index') }}')
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.length === 0) {
                                            chatMessages.innerHTML = '<p>Không còn tin nhắn cũ.</p>';
                                            return;
                                        }

                                        // Duyệt qua các tin nhắn cũ và thêm vào
                                        data.forEach(conversation => {
                                            conversation.messages.forEach(msg => {
                                                const messageDiv = document.createElement('div');
                                                messageDiv.className = msg.sender_id === admin ? 'text-end' : 'text-start';
                                                messageDiv.innerHTML =
                                                    `<p><strong>${msg.sender_name}</strong>: ${msg.message}</p>`;
                                                chatMessages.prepend(messageDiv); // Thêm vào đầu danh sách tin nhắn
                                            });
                                        });

                                        // Cuộn xuống cuối để xem tin nhắn mới nhất
                                        chatMessages.scrollTop = chatMessages.scrollHeight;
                                    })
                                    .catch(err => {
                                        chatMessages.innerHTML = '<p>Không thể tải thêm tin nhắn. Vui lòng thử lại.</p>';
                                        console.error(err);
                                    });
                            }

                            // Send chat message
                            function sendMessage(event) {
                                event.preventDefault();

                                const messageInput = document.getElementById('messageInput');
                                const chatMessages = document.getElementById('chatMessages');

                                if (messageInput.value.trim() === '') return;

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
                                        const messageDiv = document.createElement('div');
                                        messageDiv.className = 'text-end';
                                        messageDiv.innerHTML =
                                            `<p><strong>{{ Auth::user()->fullname ?? 'Bạn' }}</strong>: ${data.message}</p>`;
                                        chatMessages.appendChild(messageDiv);

                                        // Cuộn xuống cuối
                                        chatMessages.scrollTop = chatMessages.scrollHeight;
                                        messageInput.value = ''; // Xóa nội dung input
                                    })
                                    .catch(err => {
                                        console.error('Lỗi khi gửi tin nhắn:', err);
                                        alert('Không thể gửi tin nhắn. Vui lòng thử lại.');
                                    });
                            }
                        </script>

                        <!-- CSS -->
                        <style>
                            .chat-popup {
                                position: fixed;
                                bottom: 80px;
                                right: 20px;
                                width: 400px;
                                /* Tăng chiều rộng */
                                max-height: 500px;
                                /* Tăng chiều cao */
                                background: white;
                                border: 1px solid #ccc;
                                border-radius: 10px;
                                overflow: hidden;
                                z-index: 1001;
                                display: flex;
                                flex-direction: column;
                            }

                            .chat-header {
                                background: #007bff;
                                color: white;
                                padding: 10px;
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                            }

                            .chat-header h5 {
                                margin: 0;
                                font-size: 16px;
                            }

                            .chat-header .close-btn {
                                background: none;
                                border: none;
                                color: white;
                                font-size: 20px;
                                cursor: pointer;
                            }

                            .chat-body {
                                flex: 1;
                                padding: 10px;
                                overflow-y: auto;
                                max-height: calc(100% - 60px);
                                /* Điều chỉnh chiều cao tối đa cho phù hợp */
                            }

                            .chat-body .more-messages {
                                display: none;
                                text-align: center;
                                font-style: italic;
                            }

                            .chat-footer {
                                padding: 10px;
                                border-top: 1px solid #ccc;
                                display: flex;
                            }

                            .chat-footer form {
                                display: flex;
                                width: 100%;
                            }

                            .chat-footer input {
                                flex: 1;
                                margin-right: 5px;
                            }
                        </style>





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
