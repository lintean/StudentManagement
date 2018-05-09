<?php
/**
 * Created by PhpStorm.
 * User: Lintean
 * Date: 2018/5/9
 * Time: 22:20
 */

require_once'./connect_init.php';

session_start();
if (isset($_SESSION['user'])) output(0,'已经登录',$_SESSION['user']);
else output(406,'请先登录');