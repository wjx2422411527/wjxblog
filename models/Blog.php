<?php
namespace models;

use PDO;

class Blog  extends Base
{

    public function add($title,$content,$is_show)
    {
        $stmt = self::$pdo->prepare("INSERT INTO blogs(title,content,is_show,user_id)VALUES(?,?,?,?)");
        $ret = $stmt->execute([
            $title,
            $content,
            $is_show,
            $_SESSION['id'],
        ]);
        if(!$ret)
        {
            echo '失败';
            // 获取失败消息
            $error = $stmt->errorInfo();
            echo '<pre>';
            // var_dump($error);
            exit;
        }
        // 返回新插入的记录ID
        return self::$pdo->lastInsertId();
    }

    public function getBlog(){
        $stmt =  self::$pdo->query("SELECT * FROM blogs WHERE is_show=1 ORDER BY id DESC LIMIT 20");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    // 搜索日志
    public function search()
    {
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
        $stmt =self::$pdo->prepare("SELECT COUNT(*)FROM blogs WHERE $where");
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
        $stmt = self::$pdo->query("SELECT * FROM blogs WHERE $where LIMIT $offset,$perpage");
        // 取数据
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'btns' => $btns,
            'data' => $data,
        ];
    }
    public function content2html()
    {
    $stmt = self::$pdo->query('SELECT * FROM blogs');
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ob_start();
    // 生成静态页
    foreach($blogs as $v)
    {
        view('blogs.content',[
            'blog' =>$v,
        ]);
        $str = ob_get_contents();
         
       file_put_contents(ROOT.'public/contents/'.$v['id'].'.html',$str);
      
       ob_clean();
    }
    }
    public function index2html()
    {
        // 取前二十条记录数据
        $stmt =  self::$pdo->query("SELECT * FROM blogs WHERE is_show=1 ORDER BY id DESC LIMIT 20");
        $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 开启一个缓冲区
        ob_start();

        // 加载视图文件到缓冲区
        view('index.index',[
            'blogs' => $blogs,
        ]);
        // 从缓冲区取出页面
        $str = ob_get_contents();
        
        //把页面的内容生成到一个静态页中
        file_put_contents(ROOT.'public/index.html',$str); 
    }

    // 从数据库中取出日志的浏览量   
    public function getDisplay($id)
    {
        $stmt = self::$pdo->prepare('SELECT dispaly FROM blogs WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_COLUMN);
    }

    // 把内存中浏览量会写到数据库中
    public function displayToDb()
    {
        // 先取出内存中所有的浏览量
        // 连接redisx
         $redis = \libs\Redis::getInstance();;

        $data = $redis->hgetall('blog_displays');

        // 更新回数据库
        foreach($data as $k => $v)
        {
        $id = str_replace('blog-', '',$k);
        $sql = "UPDATE blogs SET display={$v} WHERE id = {$id}";
        self::$pdo->exec($sql);
        }

    }
}