<?php
namespace controllers;
use \models\Blog;
class IndexController
{
    public function index()
    {
        $blog = new Blog;
        $data = $blog->getBlog();
        // var_dump($data);
        view('index.index',[
            'data'=>$data
        ]);
    }
    public function indexs(){
        view('index.indexs');
    }
}