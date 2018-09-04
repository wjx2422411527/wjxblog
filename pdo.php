<?php
$host = '127.0.0.1';   // 主机地址
$dbname = 'blog';  // 数据库名
$user = 'root';       // 账号
$pass = '';   // 密码

// 连接数据库
$pdo = new PDO("mysql:host={$host};dbname={$dbname}", $user, $pass);
// 设置编码
$pdo->exec('SET NAMES utf8');

$stmt = $pdo->prepare('INSERT INTO blogs(title,content) VALUES(?,?)');
$ret = $stmt->execute([
    '标题xx',
    '内容xx',
]);
if($ret)
{
echo "添加成功新纪录的ID=".$pdo->lastInsertId();
}
else
{   
    $error = $stmt->errorInfo();
    var_dump($error);
}





/*$id = $_GET['id'];
$title = $_GET['title'];

$sql = "DELETE FROM blogs WHERE id= ?OR title = ?";
$stmt = $pdo->prepare($sql);
$ret = $stmt->execute([
    $id,
    $title
]);
if(!$ret)
{
    die('出错了！');
}

$sql = "DELETE FROM blogs WHERE id= :blogid OR title = :title";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':title' =>$title,
    ':blogid' =>$id, 
]);*/


// 取出前十条
// $stmt = $pdo->query('SELECT * FROM blogs LIMIT  1');
// $data = $stmt->fetch(PDO::FETCH_ASSOC);
// $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($data);






// 在数据库里生成随机汉字
// for($i=0;$i<100;$i++){
//     $title = getChar(rand(20,100));
//     $content = getChar(rand(100,500));
//     $date = date('Y-m-d H:i:s');
//     var_dump($date);
//     $ret = $pdo->exec("INSERT INTO blogs(title,content,created_at) VALUES('$title','$content','$date')");
//     // var_dump($ret);
// }

//     function getChar($num)
//     {
//         $b = '';
//         for ($i=0; $i<$num; $i++) {
//             // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
//             $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
//             // 转码
//             $b .= iconv('GB2312', 'UTF-8', $a);
//         }
//         return $b;
//     }