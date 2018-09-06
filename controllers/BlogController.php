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

        // 设置 $where
        $where = 1;
        // 如果有keyword 并值不为空时
        // 关键字搜索
       if(isset($_GET['keyword']) && $_GET['keyword'])
       {
        $where .=" AND (title LIKE '%{$_GET['keyword']}%' OR content LIKE '%{$_GET['keyword']}%')"; 
       } 

        // 发表时间搜索   
        if(isset($_GET['start_data']) && $_GET['start_data'])
        {
            $where .= "AND created_at >= '{$_GET['start_date']}'"; 
        } 
        // 修改时间搜索
        if(isset($_GET['end_data']) && $_GET['end_data'])
        {
        $where .= "AND created_at <= '{$_GET['end_date']}'"; 
        } 

        // 全部显示
        if(isset($_GET['is_show']) && ($_GET['is_show']==1 || $_GET['is_show']==='0'))
        {
        $where .= "AND is_show <= '{$_GET['is_show']}'"; 
        } 


        // 默认的排序条件
        $orderBy = 'created_at';
        $orderyWay = 'desc';

        // 设置排序字段
        if(isset($_GET['order_by']) && $_GET['order_by'] == 'display')
        {
            $orderBy = 'display';
        }
        // 设置排序方式
        if(isset($_GET['order_way']) && $_GET['order_way'] == 'asc')
        {
            $orderyWay = 'asc';
        }
        




        // 执行SQL
        $stmt = $pdo->query("SELECT * FROM blogs WHERE $where");
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