<?php

return [
    'paths' => ['api/*', '/*'], // Bao gồm cả các route không phải API nếu cần
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:3000'], // Địa chỉ frontend của bạn
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [], // Thêm nếu cần
    'max_age' => 3600, // Thay đổi thành 3600 giây hoặc giá trị khác nếu cần
    'supports_credentials' => true,
];
