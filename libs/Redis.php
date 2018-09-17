<?php
namespace libs;

use PDO;

class Redis
{
    public static $redis =null;
    private function __clone(){}
    private function __construct(){}
    
    public function __getInstance()
    {
        if(self::$redis === null)
        {
        // 连接redis
        self::$redis = new \Predis\Client([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
        ]);
        }
        return self::$redis;
    }
}