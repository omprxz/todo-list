<?php

$host = 'localhost';
$uname = 'root';
$pass = '';
$db = 'projects';

$conn = mysqli_connect($host, $uname, $pass, $db);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>