<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
{
  header("location: student_login.php");
}

require_once "config.php";
$msg="Welcome ";
$_SESSION["msg"] = $msg.$_SESSION['username'];
$deleteevent= $eventname= $username= $err="";
$_SESSION["err"]= $err;
//check if empty value passed
if($_SERVER['REQUEST_METHOD'] =="POST")
{
  if(empty(trim($_POST['delete_event'])))
  {
    $err ="Something wrong...please report it to admin and try again later.";
    $_SESSION["err"] = $err;
  }
  else{
    $deleteevent=trim($_POST['delete_event']);
    $err ="";
    $_SESSION["err"] = $err;    
  }
//check if its booked
  if(empty($deleteevent)||empty($_SESSION['username']))
  {
    $err="Some data is missing to proceed. Please try again later.";
    $_SESSION["err"] = $err;
  }

  else{
  $username=$_SESSION['username'];
  $sql = "SELECT event_name FROM book_events WHERE username='$username'"; 
  $result = $conn->query($sql);     
        
    if ($result){
      while ($row = $result->fetch_assoc()){
        $eventname = $row["event_name"];
        if($eventname == $deleteevent)
        { //Delete if everything is fine
          $sql_delete="DELETE FROM book_events WHERE event_name= ? AND username= ?";
          $stmt_delete= mysqli_prepare($conn, $sql_delete);
          if($stmt_delete)
          {
            mysqli_stmt_bind_param($stmt_delete,"ss",$param_deleteevent,$param_username);
                  $param_deleteevent=$deleteevent;
                  $param_username=$_SESSION['username'];
                if(mysqli_stmt_execute($stmt_delete))
                {
                  $msg="The Event is deleted successfully!!!";
                  $_SESSION["msg"]=$msg;
                }
                else
                {
                  $err= "Something went wrong... cannot delete!";
                  $_SESSION["err"]=$err;
                }
          }
          mysqli_stmt_close($stmt_delete); 
        }
      }
    $result->free(); } 
    }
}

?>  

<!doctype html>
<html lang="en">
  <head>
    <!--meta and css-->
    <?php include 'header_footer/meta_css.html';?> 
    <title>Events Registered</title>
  </head>
  <body class="bg-dark">    
  <!--Nav-->
  <?php include 'header_footer/nav_student.html';?> 

<div class="container-fluid">
                    
  <div class="alert alert-success mx-2 mt-3" role="alert">
    <?php 
        if(empty($_SESSION['err'])){
            echo $_SESSION["msg"];}
        else{
        echo $_SESSION["err"];}?>
  </div> 

  <section class="text-light mx-2 mb-5">
    <h4 class="text-center mt-3 mb-3">EVENTS REGISTERED</h4> 
    <form action="" method="post">  
      <table class="table table-primary table-hover table-striped">
        <thead>
          <tr>
            <th>Event Name</th>
            <th>Type</th>
            <th>Description</th>
            <th>Start Date</th>
            <th>Start Time</th>
            <th>End Date</th>
            <th>End Time</th>
            <th>Date of booking</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>  
        <?php 
            require_once "config.php";
            $row="";
            $username=$_SESSION['username'];
            $sql = "SELECT * from addevents WHERE event_name IN (SELECT event_name FROM book_events WHERE username='$username') ORDER BY start_date; "; 
            $result = $conn->query($sql);     
          
            if ($result)
            {
            while ($row = $result->fetch_assoc()) 
            {
              $eventname = $row["event_name"];
              $type = $row["type"];
              $description = $row["description"];
              $startdate = $row["start_date"]; 
              $enddate = $row["end_date"]; 
              $starttime = $row["start_time"]; 
              $endtime = $row["end_time"]; 
              $createdat = $row["created_at"];
              $start_date = date("d-m-Y", strtotime($startdate));
              $end_date = date("d-m-Y", strtotime($enddate));
              $createdat_date = date("d-m-Y", strtotime($createdat));

              echo '<tr> 
                <td>'.$eventname.'</td>
                <td>'.$type.'</td> 
                <td class="text-truncate" style="max-width: 150px;">'.$description.'</td> 
                <td>'.$start_date.'</td> 
                <td>'.$starttime.'</td> 
                <td>'.$end_date.'</td> 
                <td>'.$endtime.'</td>
                <td>'.$createdat_date.'</td>
                <td><button type="submit" name="delete_event" value="'.$eventname.'" class="btn btn-primary btn-sm"><span class="material-icons align-bottom">delete_forever</span></button></td>                     
                </tr>';
                }
                  $result->free();
                } 
                mysqli_close($conn);
          ?>
        </tbody>         
    </table>
  </form>
</section>
</div>

    </script>
    <!--java scripts-->
    <?php include 'header_footer/scripts.html';?> 

  </body>
</html>