<?php
return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
        'customer' => [
            'driver' => 'jwt',
            'provider' => 'customers',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\MUser::class
        ],
        'customers' => [
            'driver' => 'eloquent',
            'model' => \App\Models\MAccountCustomer::class,
        ],
        
    ]
];