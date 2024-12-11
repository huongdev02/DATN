<?php

return [
    'paths' => ['api/*', 'login', 'sanctum/csrf-cookie', 'logout', 'register'], 
    'allowed_methods' => ['*'], 
    'allowed_origins' => ['http://localhost:5002','http://127.0.0.1:8000'], 
    'allowed_origins_patterns' => [], 
    'allowed_headers' => ['*'], 
    'exposed_headers' => [],
    'max_age' => 3600, 
    'supports_credentials' => true, 
];


