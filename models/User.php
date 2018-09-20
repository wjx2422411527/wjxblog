<?php
namespace models;

use PDO;

class User
{
    // 保存 PDO 对象
    public $pdo;

    public function __construct()
    {
        // 取日志的数据
        $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=blog', 'root', '123456');
        $this->pdo->exec('SET NAMES utf8');
    }

    public function add($email,$password)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (email,password) VALUES(?,?)");
        return $stmt->execute([
            $email,
            $password,
    ]); 
    }
    public function login($email,$password)
    {
        // 根据email和password查询数据库
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email=? AND password=?");
        // 执行SQL
        $stmt->execute([
            $email,
            $password
        ]);
        // 取出数据
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($user);
        // die;
        // 是否有这个账号
        if($user)
        {
            // 登录成功，把用户信息保存在session
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            return TRUE;
        }
        else
        {
            return FALSE;   
        }
    }
}