<?php
session_start();
require('action/conn.php');
$table='todoTasks';
$username=$_POST['username'];
$taskname=$_POST['taskname'];
$ip=getenv('REMOTE_ADDR');

$sql="insert into $table (username,taskname,ip) values('$username','$taskname','$ip')";
$Qsql=mysqli_query($conn,$sql);
$status='error';
if (!$Qsql) {
  $status='error';
}else {
  $status='success';
}

$sql1="select taskid from $table order by taskid desc limit 1";
$Qsql1 =mysqli_query($conn,$sql1);
$prevTaskid=mysqli_fetch_array($Qsql1)['taskid'];
$taskid=$prevTaskid;

$add= array('status' => "$status",'taskid' => "$taskid");
$addJson = json_encode($add);
echo($addJson);



?>