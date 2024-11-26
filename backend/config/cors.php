<?php

return [
    'paths' => ['api/*', 'login', 'sanctum/csrf-cookie', 'logout', 'register'], // Các route cần CORS
    'allowed_methods' => ['*'], // Cho phép tất cả các phương thức HTTP (GET, POST, PUT, DELETE, v.v.)
    'allowed_origins' => ['http://localhost:3006', 'http://localhost:8000'], // Địa chỉ frontend và backend
    'allowed_origins_patterns' => [], // Các pattern origin nếu cần
    'allowed_headers' => ['*'], // Cho phép tất cả các header từ frontend/backend
    'exposed_headers' => [], // Nếu cần thêm header nào khác để gửi về frontend
    'max_age' => 3600, // Thời gian trình duyệt lưu cache cho CORS (1 giờ)
    'supports_credentials' => true, // Quan trọng! Cho phép gửi cookie và xác thực giữa các origin
];

