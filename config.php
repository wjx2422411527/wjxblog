<?php
return [
    'redis' => [
        'scheme' => 'tcp',
        'host' => '127.0.0.1',
        'port' => 6379,
    ],
    'db' => [
        'host' => '127.0.0.1',
        'dbname' =>'blog',
        'user' => 'root',
        'pass' => '123456',
        'charset' =>'utf8',
    ],
    'email' =>[
        'mode' =>'debug', //debug 调试模式 production：生产模式
        'port' => 25,
        'host' => 'smtp.126.com',
        'email' =>'wjx2422411527@126.com',        
        'code' => 'wangjingxiao123',
    ]
];
