<?php

return [
    'defaults' => [
        'guard' => 'customer',
        'passwords' => 'customers',
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'customer',
        ],
        'customer' => [
            'driver' => 'jwt',
            'provider' => 'customer',
        ],
        'admin' => [
            'driver' => 'jwt',
            'provider' => 'admin',
        ],
    ],

    'providers' => [
        'customer' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],
        'admin' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],

    'passwords' => [
        'customers' => [
            'provider' => 'customer',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admins' => [
            'provider' => 'admin',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];

