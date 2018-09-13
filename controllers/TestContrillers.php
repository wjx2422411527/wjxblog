<?php
namespace controllers;

class TestController
{
    public function register()
    {

    }

    // 发邮件
    public function mail()
    {
    // 设置 socket 永不超时
    ini_set('default_socket_timeout', -1); 

    echo "邮件程序已启动...等会...";
    // 连接redis
    $redis = new \Predis\Client([
       'scheme' => 'tcp',
       'host' => '127.0.0.1',
       'port' => 6379,
    ]);

    // 循环监听一个列表
    while(true)
    {
        // 从队列中取数据，设置为永久不超时
        $data = $redis->brpop('email', 0);
        
        echo '开始发邮件';
        // 处理数据
        var_dump($data);
        echo "发完邮件，继续等待\r\n";
        
    }
    }
}
