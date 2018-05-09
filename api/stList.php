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
if ($_SERVER['REQUEST_METHOD'] != 'GET') output(401,"连接错误");

$sql_str = 'SELECT ID,name FROM `student`';
$sth = $pdo->prepare($sql_str);
if ($sth->execute()){
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($result)){
        output(0,'',$result);
    }
    else output(403,"无学生数据");
}
else{
    output(500,"服务器或数据库错误");
}