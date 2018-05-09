<?php
/**
 * Created by PhpStorm.
 * User: Lintean
 * Date: 2018/5/8
 * Time: 21:07
 */
require_once'./connect_init.php';

session_start();
if (!isset($_SESSION['user'])) output(406,'请先登录');
if ($_SERVER['REQUEST_METHOD'] != 'POST') output(401,"连接错误");

$json = file_get_contents('php://input');
$data = json_decode($json,true);
$ID = $data['ID'];

$sql_str = 'SELECT * FROM `student` where ID = ?';
$sth = $pdo->prepare($sql_str);
if ($sth->execute([$ID])){
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($result)){
        output(0,'查询成功',$result[0]);
    }
    else output(403,"无此编号");
}
else{
    output(500,"服务器或数据库错误");
}