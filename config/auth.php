<?php
return [
    'defaults' => [
        'guard' => 'api',
    ],
    'guards' => [
        'api' => [
            'driver' => 'jwt',    // <--- SIGUROHA NGA 'jwt' NI, DILI 'passport'
            'provider' => 'users',
        ],
    ],
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],
];