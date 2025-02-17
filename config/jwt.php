<?php

return [
    'secret' => env('JWT_SECRET'),
    'ttl' => 60,
    'algo' => 'HS256',
    'keys' => [
        'public' => null,
        'private' => null,
    ],
    'user' => 'App\Models\User',
];
