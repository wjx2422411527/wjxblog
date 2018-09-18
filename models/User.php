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
}