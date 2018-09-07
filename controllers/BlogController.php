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


        /* 分页 */
        $perpage = 10; //每页显示10条数据
        // 接受当前页码
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1 ;
        // 计算开始的下标
        $offset = ($page-1)*$perpage;
        // 制作按钮
        // 取出总的记录数
        $stmt = $pdo->prepare("SELECT COUNT(*)FROM blogs WHERE $where");
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_COLUMN);

        // 计算总的页数
        $pageCount =ceil( $count / $perpage);

        $btns = '';
        for($i=1; $i<=$pageCount; $i++)
        {
            $params = getUrlParams(['page']);
            // 给当前页数的页码设置样式
            $class = $page==$i ? 'active' :'';
           $btns .= "<a class='$class'  href='?{$params}page=$i'>$i</a>";
        } 

        // 执行SQL
        $stmt = $pdo->query("SELECT * FROM blogs WHERE $where LIMIT $offset,$perpage");
        // 取数据
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo '<pre>';
        // var_dump($data);
        // 加载视图
        view('blogs.index',[
            'data' => $data,
            'btns' => $btns,
        ]);
    }
}