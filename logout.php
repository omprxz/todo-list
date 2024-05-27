<meta http-equiv="refresh" content="5;url=user.php" />
<?php
session_start();
if (isset($_SESSION['userid'])) {
session_destroy();
echo('<script>location.replace("user.php");</script>');
}
else {
  header('Location:user.php');
}
?>