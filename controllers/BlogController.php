<?php
namespace controllers;

use models\Blog;
class BlogController
{
    public function store()
    {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $is_show = $_POST['is_show'];
        
        $blog = new Blog;
        $blog->add($title,$content,$is_show);

        // 跳转
        message('发表成功',2,'/blog/index');
    }

    // 显示添加日志的表单
    public function create()
    {
        view('blogs.create');
    }

    // 日志列表
    public function index()
    {
        $blog = new Blog;
        // 搜索数据
        $data =  $blog->search();
        // 加载视图
        view('blogs.index',$data);
  
    }

    // 为所有的日志生成详情页
    public function content_to_html()
    {
        $blog = new Blog;
        $blog->content2html();
    }
    public function index2html()
    {
        $blog = new Blog;
        $blog->index2html();
    }
    public function update_display()
    {
        // 接受日志id
        $id = (int)$_GET['id'];

        // 连接redis
        $redis =\libs\Redis::getInstance();
        // 判断redis是否有这篇日志的浏览量
        $key = "blog-{$id}"; //拼出日志的键

        // 判断hash中是否有这个值
        if($redis->hexists('blog_displays',$key))
        {
            // 累加 返回添加完之后的值
           $newNum = $redis->hincrby('blog_displays',$key,1);
           echo json_encode([
               'display'=>$newNum
           ]);
        }
        else
        {
            // 从数据库中取出浏览量
            $blog = new Blog;
            $display = $blog->getDisplay($id);
            $display++;
            // 加到redis
            $redis->hset('blog_displays',$key,$display);
            echo $display;  
        }
    }
    public function displayToDb()
    {
        $blog = new Blog;
        $blog->displayToDb();
    }
}