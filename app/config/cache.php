<?php

return [
    'redis' => [
        ['id' => 'default',  'host' => 'redis', 'port' => '6379'],
        ['id' => 'redis_cache',  'host' => 'redis', 'port' => '6379'],
        ['id' => 'redis_session',  'host' => 'redis', 'port' => '6379'],
        ['id' => 'redis_queue',  'host' => 'redis', 'port' => '6379'],
        ['id' => 'redis_userinfo',  'host' => 'redis', 'port' => '6379'],
        ['id' => 'redis_content',  'host' => 'redis', 'port' => '6379'],
        ['id' => 'redis_user',  'host' => 'redis', 'port' => '6379', 'password' => '123456!'],
    ]
];
