<?php
/**
 * Created by PhpStorm.
 * User: Lintean
 * Date: 2018/5/9
 * Time: 12:08
 */
require_once'./connect_init.php';

$json = file_get_contents('php://input');
$data = json_decode($json,true);
$user = $data['user'];
$pwd = $data['password'];

if (!isset($user)) output(500,"服务器错误,没有接收到user");
if (!isset($pwd)) output(500,"服务器错误,没有接收到pwd");


$sql_str = 'SELECT * FROM `usercenter` WHERE `user`=? and `password`=?';
$sth = $pdo->prepare($sql_str);
if ($sth->execute(array($user, $pwd))){
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($result)){
        session_start();
        $_SESSION['user'] = $user;
        output(0,'登录成功');
    }
    else output(403,"用户名或密码错误");
}
else{
    output(500,"服务器或数据库错误");
}