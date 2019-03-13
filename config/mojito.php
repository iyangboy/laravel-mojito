<?php

return [

    // TOKEN 有效期，单位为天
    'passport_token_ttl' => env('PASSPORT_TOKEN_TTL', 1),

    // TOKEN 刷新有效期，单位为天，暂时无用
    'passport_refresh_token_ttl' => env('PASSPORT_REFRESH_TOKEN_TTL', 7),

    'super_admin' => [
        //  后台用户的 provider，登录时需要
        'provider' => env('MOJITO_SUPER_ADMIN_PROVIDER', 'admin'),

        // 后台用户的鉴权中间件
        'auth' => env('MOJITO_SUPER_ADMIN_AUTH', 'auth:admin'),

        // 后台用户的 guard
        'guard' => env('MOJITO_SUPER_ADMIN_GRARD', 'admin')
    ],

    'multi_auth_guards' => env('MOJITO_MULTI_AUTH_GUARDS'),

];
