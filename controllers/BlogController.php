<?php
namespace controllers;
use PDO;
class BlogController
{
    // 日志列表
    public function index()
    {
        // 取日志的数据
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=blog','root','');
        // 设置数据库编码
        $pdo->exec('SET NAMES utf8');

        // 执行SQL
        $stmt = $pdo->query("SELECT * FROM blogs");
        // 取数据
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo '<pre>';
        // var_dump($data);
        // 加载视图
        view('blogs.index',[
            'data' => $data,
        ]);
    }
}