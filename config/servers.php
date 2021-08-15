<?php

return [
    'tcp_pack_enable' => env('TCP_PACK_ENABLE', false),
    'rabbitmq' => [
        'host' => env('RABBITMQ_HOST'),
        'port' => intval(env('RABBITMQ_PORT', 5672)),
        'username' => env('RABBITMQ_USERNAME'),
        'password' => env('RABBITMQ_PASSWORD')
    ]
];
