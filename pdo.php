<?php
$host = '127.0.0.1';   // 主机地址
$dbname = 'blog';  // 数据库名
$user = 'root';       // 账号
$pass = '';   // 密码

// 连接数据库
$pdo = new PDO("mysql:host={$host};dbname={$dbname}", $user, $pass);