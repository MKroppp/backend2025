<?php

return [
    'defaults' => [
        'guard' => 'api', // Здесь мы говорим, что по умолчанию используем 'api' guard
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',  // Здесь указываем, что будем использовать JWT для аутентификации
            'provider' => 'users',  // Указываем, что provider для этого guard — это модель User
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,  // Указываем модель User для работы с базой данных
        ],
    ],
];
