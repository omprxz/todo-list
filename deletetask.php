<?php
session_start();
require('action/conn.php');
$table='todoTasks';
$username=$_POST['username'];
$taskid=$_POST['taskid'];


$sql="delete from $table where username = '$username' && taskid = '$taskid'";
$Qsql=mysqli_query($conn,$sql);
$status='error';
if (!$Qsql) {
  $status='error';
}else {
  $status='deleted';
}

$deld=array('status' => "$status");
$deldJson = json_encode($deld);
echo($deldJson);



?>