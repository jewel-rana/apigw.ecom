<?php

return [
    'bkash' => [
        'sandbox_enabled' => env('BKASH_SANDBOX_ENABLED', true),
        'sandbox' => [
            'base_url' => 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/',
            'username' => 'sandboxTokenizedUser02',
            'password' => 'sandboxTokenizedUser02@12345',
            'client_id' => '4f6o0cjiki2rfm34kfdadl1eqq',
            'client_secret' => '2is7hdktrekvrbljjh44ll3d9l1dtjo4pasmjvs5vl5qr3fug4b',
            'callback_url' => 'https://prokash.io/payment/callback',
        ],
        'production' => [
            'base_url' => env('BKASH_BASE_URL'),
            'username' => env('BKASH_USERNAME'),
            'password' => env('BKASH_PASSWORD'),
            'client_id' => env('BKASH_CLIENT_ID'),
            'client_secret' => env('BKASH_CLIENT_SECRET'),
            'callback_url' => env('BKASH_CALLBACK_URL')
        ]
    ]
];
