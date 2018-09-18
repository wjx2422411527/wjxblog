<?php
namespace libs;

use PDO;

class Redis
{
    public static $redis =null;
    private function __clone(){}
    private function __construct(){}
    
    static function getInstance()
    {
        // 从配置文件中读取账号
        $config = config('redis');

        // 如果还没有 redis 就生成一个
        // 只有每 一次 才会连接
        if(self::$redis === null)
        {
        // 连接redis
        self::$redis = new \Predis\Client($config);
        }
        // 直接返回已有的redis
        return self::$redis;
    }
}