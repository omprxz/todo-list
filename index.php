<?php $toolCode = 'todo-list';?>
<?php
session_start();
if (!isset($_SESSION['userid'])) {
  header("location:user.php");
}
if (isset($_POST['logout'])) {
  session_start();
  session_destroy();
  header("location:user.php");
}
?>
<?php
require('action/conn.php');
$table = 'todoTasks';
$username = $_SESSION['username'];

$fetchTasks = "select * from $table where username = '$username' order by creationtime desc";
$fetchTasks_Q = mysqli_query($conn, $fetchTasks);

$query = "SELECT name FROM todoUsers";
$result = mysqli_query($conn, $query);
$name="Buddy";
while ($row = mysqli_fetch_assoc($result)) {
    $name = $row['name'];
}

mysqli_free_result($result);

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
<link rel="icon" href="/favicon.ico" type="image/x-icon">
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
    .delTask {
      font-size: 20px;
    }
    </style>
</head>
<body>
  
  <div class="container-fluid px-2 my-1">
<h2 class="text-center my-2">Hello <?php
$nameParts = explode(' ', $name);
$firstName = $nameParts[0];
echo $firstName;
?>
</h2>
<h4 class="text-center mb-2" style="color:#420217;">To-Do List</h4>
    <div class="input-group">
      <input type="text" name="inpTask" id="inpTask" class="form-control inpTask" value="" placeholder="Add New Task" />
      <button type="button" class="btn btn-primary addTask"><i class="fa fa-plus"></i> Add</button>
    </div>
    <h5 class="text-center mt-1" style="color:#420217;">Your Tasks</h5>
    <div class="tasks d-flex flex-column my-2 px-2">

      <?php
      if (mysqli_num_rows($fetchTasks_Q) > 0) {
        while ($tasks = mysqli_fetch_assoc($fetchTasks_Q)) {
          if ($tasks['taskstatus']=='completed') {
            $taskstatus='checked';
          }
          else {
            $taskstatus='';
          }
          ?>
          <div class="taskCon_<?php echo $tasks['taskid']; ?> taskCon mb-0 px-2">
            <label><input type="checkbox" class="form-check-input me-2 comStatus_<?php echo $tasks['taskid']; ?>" <?php echo $taskstatus; ?> name="comStatus" id="comStatus_<?php echo $tasks['taskid']; ?>" onchange="taskStatus(<?php echo $tasks['taskid']; ?>);" /><?php echo $tasks['taskname']; ?></label><div class="delTask float-end clearfix  border border-danger rounded text-danger" id="taskCon_<?php echo $tasks['taskid']; ?>" onclick="delTasks(<?php echo $tasks['taskid']; ?>);">
              <i class="fa fa-times px-1"></i>
            </div>
            <hr class="my-2">
          </div>

          <?php
        }
      }
      ?>

    </div>
        <form method="post" class="my-2 px-1 d-flex justify-content-center text-center" accept-charset="utf-8">
      <button class="btn btn-outline-secondary border rounded" name="logout" type="submit"><small>Log Out <i class="fa fa-sign-out"></i></small></button>
    </form>
  </div>
  <div class="toasts">
    <div class="snackbar taskDeleted">Task Deleted</div>
    <div class="snackbar taskCompleted">Task Completed</div>
    <div class="snackbar taskIncomplete">Task Incomplete</div>
    <div class="snackbar taskAdded">
      Task Added
    </div>
  </div>
   <?php include $_SERVER['DOCUMENT_ROOT'] .'/share.php';?>
  <!--SCRIPTS-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2"></script>
  <script>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/js/main.js';
?>  
</script>
<!--<script src="https://kit.fontawesome.com/5950f04c3b.js" crossorigin="anonymous"></script>-->
  <!--/SCRIPTS-->
  <script type='text/javascript'>
    function showToast(elemClass) {
      $('.'+elemClass).addClass('show');
      setTimeout(function() {
        $('.'+elemClass).removeClass('show');
      }, 3000);
    }
  </script>
  <script>
    var taskHtml = '';
    var taskNum = 0;
    var username = '<?php echo $_SESSION["username"]; ?>';

    $('.addTask').click(function() {
      taskHtml = '';
      if ($('#inpTask').val() != '') {
        $.ajax({
          url: 'addtask.php',
          type: 'post',
          data: "username="+username+"&taskname="+$('.inpTask').val(),
          dataType: 'json',
          beforeSend: function() {
            $('.addTask').html('<i class="fa fa-plus"></i> Adding');
            $('.addTask').attr('disabled', 'disabled');
          },
          complete: function() {
            $('.addTask').html('<i class="fa fa-plus"></i> Add');
            $('.addTask').removeAttr('disabled');
          },
          success: function(addJson) {
            if (addJson['status'] == 'success') {
              //  taskNum=$('.taskCon').find("div").length+1;
              taskNum = addJson['taskid'];
              taskHtml += "<div class=\"taskCon_"+taskNum+" taskCon mb-0 px-2\"><label><input type=\"checkbox\" class=\"form-check-input me-2 comStatus_"+taskNum+"\" name=\"comStatus\" id=\"comStatus_"+taskNum+"\" onchange=\"taskStatus("+taskNum+");\" />"+$('#inpTask').val()+"</label><div class=\"delTask float-end clearfix  border border-danger rounded text-danger\" id=\"taskCon_"+taskNum+"\" onclick=\"delTasks("+taskNum+");\"><i class=\"fa fa-times px-1\"></i></div><hr class=\"my-2\"></div>";
              $('.tasks').prepend(taskHtml);
              $('.inpTask').val('');
              showToast('taskAdded');
              console.log(taskNum);
            } else {
              console.error('Task not added');
              alert('Something went wrong');
            }
          }
        })
      } else {
        alert('Empty Task');
      }
    })

    function delTasks(taskId) {
      $.ajax({
        url: 'deletetask.php',
        type: 'post',
        data: "username="+username+"&taskid="+taskId,
        dataType: 'json',
        beforeSend:function(){
          $('#taskCon_'+taskId).html('<i class="fa fa-spinner fa-spin px-1"></i>');
        },
        complete:function(){
          $('#taskCon_'+taskId).html('<i class="fa fa-times px-1"></i>');
        },
        success: function(delJson) {
          if (delJson['status'] == 'deleted') {
            $('.taskCon_'+taskId).remove();
            showToast('taskDeleted');
          } else {
            console.error('Task not deleted');
            alert('Something went wrong');
          }
        }
      })
    }
    
    function taskStatus(taskId){
      var taskstatus=$('.comStatus_'+taskId).prop('checked');
      if(taskstatus==true){
        taskstatus='completed'
      }else if(taskstatus==false){
        taskstatus='incomplete';
      }
       $.ajax({
        url: 'taskStatus.php',
        type: 'post',
        data: "username="+username+"&taskid="+taskId+"&taskstatus="+taskstatus,
        dataType: 'json',
        success: function(statusJson) {
          if(statusJson['status']=='completed'){
          console.log(statusJson['status']);
          showToast('taskCompleted');
          }
          else if(statusJson['status']=='incomplete'){
            console.log(statusJson['status']);
            showToast(taskIncomplete);
          }
          else{
            console.log(statusJson);
            alert('Something went wrong');
          }
        }
      })
    }
  </script>
</body>
</html>