<?php
namespace controllers;

class TestController
{
    public function register()
    {
        // 注册成功

        
        // 发邮件
    // 连接redis
    $redis = new \Predis\Client([
        'scheme' => 'tcp',
        'host' => '127.0.0.1',
        'port' => 6379,
     ]);

        // 消息队列
        $data = [
            'email' =>'wjx2422411527@126.com',
            'title' =>'标题',
            'content'=>'内容',
        ];
        // 数组转成json
        $data = json_encode($data);

        $redis->lpush('emal',$data);

        echo "注册成功";
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
    public function testMail()
    {
        // 设置邮件服务器账号
        $transport = (new \Swift_SmtpTransport('smtp.126.com',25))//邮件服务器ip地址和端口号
        ->setUsername('wjx2422411527@126.com')//发邮件账号
        ->setPassword('2422411527wjx');//授权码

        // 创建发邮件对象
        $mailer = new \Swift_Mailer($transport);

        // 创建邮件消息
        $message = new \Swift_Message();

        $message->setSubject('测试标题')//标题
                ->setFrom(['wjx2422411527@126.com' => '王景笑'])//发件人
                ->setTo(['wjx2422411527@126.com','wjx2422411527@126.com'=>'王笑笑'])//收件人
                ->setBody('Hello <a href="http://localhost:9999">点击激活</a> World~','text/html');//邮件内容及邮件内容类型

        //发送邮件
        $ret = $mailer->send($message);
        var_dump($ret);  
    }
}
