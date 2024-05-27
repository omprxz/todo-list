<?php
session_start();
require('action/conn.php');
$table='todoTasks';
$username=$_POST['username'];
$taskid=$_POST['taskid'];
$taskstatus=$_POST['taskstatus'];

$sql="update $table set taskstatus = '$taskstatus' where username = '$username' && taskid = '$taskid'";
$Qsql=mysqli_query($conn,$sql);
$status='error';
if (!$Qsql) {
  $status='error';
}else {
  $status=$taskstatus;
}

$res=array('status' => "$status");
$res = json_encode($res);
echo($res);



?>