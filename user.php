<?php $toolCode = 'todo-list';?>
<?php
session_start();
if (isset($_SESSION['userid'])) {
  header("location:index.php");
}
if (isset($_POST['register'])) {
include('action/conn.php');
$table="todoUsers";
$name=mysqli_real_escape_string($conn,$_POST['name']);
$username=mysqli_real_escape_string($conn,$_POST['username']);
$pass=mysqli_real_escape_string($conn,$_POST['pass']);
$ip=mysqli_real_escape_string($conn,$_SERVER['REMOTE_ADDR']);

$getusernameX="select username from $table where username = '$username'";
$getusernameQ=mysqli_query($conn,$getusernameX);
if(mysqli_num_rows($getusernameQ)>0) {
?>
  <script>
  alert('User name already exists.\nTry logging into your accoount.');
  </script>
  <?php
}
else{
$registerX="insert into $table (name,username,password,creation_ip) values('$name','$username','$pass','$ip')";
if ($registerQ=mysqli_query($conn,$registerX)) {
  $getuseridX="select userid from $table where username = '$username' && password='$pass'";
  $getuseridQ=mysqli_query($conn,$getuseridX);
  if (!$getuseridQ) {
  }
  while($useridA=mysqli_fetch_array($getuseridQ)){
    $userid=$useridA['userid'];
  }
?>
<script>
  alert("Registered Successfully.\nYour UserID is <?php echo $userid;?>\nKeep it to log into your account.");
</script>
<?php
}
else {
  ?>
<script>
  alert('Error while creating your account.\nTry again.')
</script>
<?php
}
}
}
?>

<?php
if (isset($_POST['login'])) {
  include('action/conn.php');
  $table = 'todoUsers';
  $userid = mysqli_real_escape_string($conn,$_REQUEST['userid']);
  $password = mysqli_real_escape_string($conn,$_REQUEST['pass']);
  $checkX = "select * from $table where ( userid='$userid' OR username = '$userid') and password='$password'";
  $checkQ=mysqli_query($conn,$checkX);
  if (!$checkQ) {
  }
  if (mysqli_num_rows($checkQ)>0) {
    while($credsA=mysqli_fetch_array($checkQ)){
        $session_duration = 180000; // 30 minutes
        session_set_cookie_params($session_duration);

    $_SESSION['name']=$credsA['name'];
    $_SESSION['username']=$credsA['username'];
    $_SESSION['userid']=$credsA['userid'];
    $_SESSION['pass']=$credsA['password'];
    }
    echo("<script>location.replace('index.php');</script>");
  }
  else {
     $checkuidX = "select * from $table where userid='$userid' OR username = '$userid'";
     $checkuidQ=mysqli_query($conn,$checkuidX);
     if (mysqli_num_rows($checkuidQ)>0) {
       ?>
       <script>
         alert('Incorrect password.\Try again.');
       </script>
       <?php
     }
     else {
        ?>
       <script>
         alert('No such username or userdid.\nConsider Registering.');
       </script>
       <?php
     }
  }
  
  
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>To Do List</title>
  <meta name="Description" content="To Do List">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="index, follow" />
  <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
  <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.css" />
<style>
      .snackbar {
      visibility: hidden;
      min-width: 250px;
      margin-left: -125px;
      background-color: #000;
      color: #fff;
      text-align: center;
      border-radius: 40px;
      padding: 12px;
      position: fixed;
      opacity: 0.95;
      z-index: 1;
      left: 50%;
      bottom: 30px;
    }
    .snackbar.show {
      visibility: visible;
      -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
      animation: fadein 0.5s, fadeout 0.5s 2.5s;
    }
    @-webkit-keyframes fadein {
      from {
        bottom: 0;
        opacity: 0;
      }
      to {
        bottom: 30px;
        opacity: 0.95;
      }
    }
    @keyframes fadein {
      from {
        bottom: 0;
        opacity: 0;
      }
      to {
        bottom: 30px;
        opacity: 0.95;
      }
    }
    @-webkit-keyframes fadeout {
      from {
        bottom: 30px;
        opacity: 0.95;
      }
      to {
        bottom: 0;
        opacity: 0;
      }
    }
    @keyframes fadeout {
      from {
        bottom: 30px;
        opacity: 0.95;
      }
      to {
        bottom: 0;
        opacity: 0;
      }
    }
    </style>
</head>
<body>
  
  <?php 
  include $_SERVER['DOCUMENT_ROOT'].'/nav.php';?>
  
  <h1 class="text-center fw-bold mt-2">To-Do List</h1>
  <div id="tabs">
  <ul class="pagination my-2 px-2 d-flex flex-row justify-content-streched">
    <li class="page-item text-center active" style="flex-grow:1;" id="loginLi">
      <a class="page-link" href="#loginDiv">Log in</a>
    </li>
    <li class="page-item text-center" style="flex-grow:1;" id="signupLi">
      <a class="page-link" href="#signupDiv">Sign up</a>
    </li>
  </ul>

  <div class="main">
    <div class="loginDiv" id="loginDiv">
      
      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="px-2">
					<input type="text" name="userid" class="form-control mt-4 mb-3" placeholder="User ID or username" required="">
					<input type="password" class="form-control my-2" name="pass" placeholder="Password" required="">
					<center>
					<button class="btn btn-outline-success my-1" type="submit" name="login">Login</button>
					</center>
					  <center class="alert alert-danger mt-2 mb-3">
					Remember Your Password.
					</center>
				</form>
      
    </div>
    <div class="signupDiv" id="signupDiv">
      
      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="px-2">
					<input type="text" name="name" placeholder="Name" class="form-control mt-4 mb-3" required="">
					<input type="username" name="username" class="form-control my-3" placeholder="Username" required="">
					<input type="password" name="pass" class="form-control my-3" placeholder="Password" required="">
					<center>
					<button class="btn btn-outline-info my-1" type="submit" name="register">Sign up</button>
					</center>
					<center class="alert alert-danger mt-2 mb-3">
					Remember Your Password.
					</center>
				</form>
      
    </div>
  </div>
</div>
  
  
   <?php include $_SERVER['DOCUMENT_ROOT'] .'/share.php';?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2"></script>
  <script type='text/javascript'>
    function showToast(elemClass) {
      $('.'+elemClass).addClass('show');
      setTimeout(function() {
        $('.'+elemClass).removeClass('show');
      }, 3000);
    }
    
     $(function() {
    $("#tabs").tabs();
  });

$('#loginLi').click(function(){
  $(this).addClass('active');
  $('#signupLi').removeClass('active');
})
$('#signupLi').click(function(){
  $(this).addClass('active');
  $('#loginLi').removeClass('active');
})

    
  </script>
</body>
</html>