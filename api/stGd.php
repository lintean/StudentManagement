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
$name = $data['name'];
$course_id = $data['course_id'];

$sql_str = "select sum(
            case grade
                when 'A' then 10
                when 'A+' then 10
                when 'A-' then 10
                when 'B' then 8
                when 'B+' then 8
                when 'B-' then 8
                when 'C' then 6
                when 'C+' then 6
                when 'C-' then 6
                when 'D' then 4
                when 'D+' then 4
                when 'D-' then 4
            end
        ) as 'total_credits'
        from takes inner join student on takes.ID = student.ID ";

if ($course_id == ''){
    global $sql_str;
    $sql_str = $sql_str."where (student.ID = ?) or (student.name = ?)";
    $upload = array($ID, $name);
}
else{
    global $sql_str;
    $sql_str = $sql_str."where (student.ID = ? and takes.course_id = ?) or (student.name = ? and takes.course_id = ?)";
    $upload = array($ID, $course_id, $name, $course_id);
}

$sth = $pdo->prepare($sql_str);
if ($sth->execute($upload)){
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($result[0]['total_credits'])){
        output(0,'登录成功',$result[0]['total_credits']);
    }
    else output(403,"无此信息");
}
else{
    output(500,"服务器或数据库错误");
}