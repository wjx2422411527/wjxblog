<?php
// 定义常量
define('ROOT', dirname(__FILE__) . '/../');

// 引入composer自动加载文件
require(ROOT.'vendor/autoload.php');

// 实现类的自动加载
function autoload($class)
{
    $path = str_replace('\\','/',$class);
    require(ROOT . $path . '.php');
}
spl_autoload_register('autoload');
 
// 添加路由：解析 URL 上的路径：控制器/方法
// 获取URL
// 添加路由：解析 URL 浏览器上 blog/index CLI中就是blog index
if(php_sapi_name() == 'cli')
{
    $controller = ucfirst($argv[1]) . 'Controller';
    $action = $argv[2];
}
else
{
    if( isset($_SERVER['PATH_INFO']))
    {
        $pathInfo = $_SERVER['PATH_INFO'];
        // 根据 / 转换数组
        $pathInfo = explode('/',$pathInfo);
    
        // 得到控制器名和方法名;
        $controller = ucfirst($pathInfo[1]) .'Controller';
        $action = $pathInfo[2]; 
    
    }
    else
    {
        // 默认控制器和方法
        $controller = 'IndexController';
        $action = 'index';
    }
}

    // 为控制器添加命名空间
    $fullController = 'controllers\\'.$controller;


    $_C = new $fullController;
    $_C->$action();



// 参数视图
// 参数一，加载的视图文件名
// 参数二，向视图中传的数据
function view($viewFileName,$data=[])
{
    // 解压数组变量 
    extract($data);
    $path = str_replace('.','/',$viewFileName).'.html';

    // 加载视图
    require(ROOT. '/views/' .$path);
}

function getUrlParams(){
    
}


// 获取配置文件
function config($name)
{
    static $config = null;
    if($config === null){
        // 引入配置文件
        $config = require_once(ROOT.'config.php');
    }
        return $config[$name];

}