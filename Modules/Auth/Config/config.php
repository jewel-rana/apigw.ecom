<?php

return [
    'name' => 'Auth',
    '2fa_enabled' => env('AUTH_2FA_ENABLED', true),
    'register_enabled' => env('AUTH_REGISTER_ENABLED', false),
    'default_login_enabled' => env('AUTH_DEFAULT_LOGIN_ENABLED', false),
    'forgot_password_enabled' => env('AUTH_FORGOT_PASSWORD_ENABLED', false),
    'user' => [
        'statuses' => [
            0 => 'Pending',
            1 => 'Active',
            9 => 'Inactive',
        ]
    ],
    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'site_secret' => env('RECAPTCHA_SITE_SECRET'),
    ]
];
