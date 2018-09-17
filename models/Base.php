<?php
// 所有模型的父模型
namespace models;

use PDO;

class Base
{
      // 保存 PDO 对象
    public static $pdo = null;
       
    public function __construct()
       {
           if(self::$pdo === null){
           // 取日志的数据
           self::$pdo = new PDO('mysql:host=127.0.0.1;dbname=blog','root','123456');
           // 设置数据库编码
           self::$pdo->exec('SET NAMES utf8');
       }
    }
}