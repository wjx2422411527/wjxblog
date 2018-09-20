<?php
namespace controllers;

// 引入模型类
use \models\User;

class UserController
{
    public function logout(){
        $_SESSION = [];
        // header("Location:/user/login");
        success('退出成功',1,'/');
    }
    // 处理登录表单
    public function dologin()
    {
        // 接受表单
        $email = $_POST['email'];
        $password = md5($_POST['password']);

        // 使用模型
        $user = new User;
        if($user->login($email,$password))
        {
            // var_dump($_SESSION);
            header('Location:/index/index');
        }
        else
        {
            die("用户名或者密码错误");
        }
    }
    public function login()
    {
        view('users.login');
    }
    
    public function register()
    {
        // 显示视图
        view('users.add');
    }
    
    public function store()
    {
        // 接受表单
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        // 插入数据库中
        $user = new User;
        $ret = $user->add($email,$password);
        // var_dump($ret);die;

        if(!$ret)
        {
            die("注册失败");
        }


        // 把消息放到队列中
         // 从邮箱地址中取出姓名 
       $name = explode('@', $email);
       // 构造收件人地址[    wjx24222411527 @ 126.com   ,   wjx  ]
         $from = [$email, $name[0]];


        // 构造消息数组
        $message = [
            'title' => '欢迎加入全栈1班',
            'content' => "点击以下链接进行激活：<br> <a href=''>点击激活</a>。",
            'from' => $from,
        ];
        // 把消息转成字符串(JSON ==> 序列化)
        $message = json_encode($message);

        // 放到队列中
        $redis = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);
        $redis->lpush('email', $message);
        echo 'ok';
    }
}