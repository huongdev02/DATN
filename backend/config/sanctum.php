<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Statefull Domains
    |--------------------------------------------------------------------------
    |
    | Here you may specify which domains should receive stateful authentication
    | cookies. The domains specified here should not contain any wildcards.
    |
    */

    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost:3000,localhost')),

    /*
    |--------------------------------------------------------------------------
    | Expiration Minutes
    |--------------------------------------------------------------------------
    |
    | This value controls the number of minutes until an issued token will
    | be considered expired. If you want to allow users to stay logged in
    | indefinitely, you may set this value to null. Otherwise, it will
    | expire after the specified number of minutes.
    |
    */

    'expiration' => null,

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | You may specify the middleware that should be applied to your Sanctum
    | routes. You may also set the middleware to the default 'web' and 
    | 'api' middleware if necessary.
    |
    */

    'middleware' => [
        'api' => ['throttle:api', 'bindings'],
        'web' => ['web'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Prefix
    |--------------------------------------------------------------------------
    |
    | This value is the prefix used when registering routes. You may modify
    | it if you wish to have your routes under a different prefix. 
    |
    */

    'prefix' => 'api',

    /*
    |--------------------------------------------------------------------------
    | Guards
    |--------------------------------------------------------------------------
    |
    | Here you can specify which authentication guards should be used for 
    | Sanctum authentication. Typically, this will be the "web" guard.
    |
    */

    'guards' => [
        'web',
        'api',
    ],
];
