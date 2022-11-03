<?php
session_start();
$msg="Welcome Admin ";
$user_logged = $_SESSION["username"];
$_SESSION["msg"] = $msg.$user_logged;

if(!isset($_SESSION['adminloggedin']) || $_SESSION['adminloggedin'] !==true)
{
    header("location: admin_login.php");
    exit;
}

require_once "config.php";
$eventname= $type = $description = $startdate= $enddate= $starttime= $endtime= $err="";

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(empty(trim($_POST['event_name'])) || empty(trim($_POST['type'])) || empty(trim($_POST['description'])) || empty(trim($_POST['start_date'])) || empty(trim($_POST['end_date'])) || empty(trim($_POST['start_time'])) || empty(trim($_POST['end_time'])))
    {
      $err = "Please enter all details";
      $_SESSION["err"] = $err;
    }

    else
    {
        $eventname= trim($_POST['event_name']);
        $type = trim($_POST['type']);
        $description = trim($_POST['description']);
        $startdate= trim($_POST['start_date']);
        $enddate= trim($_POST['end_date']);
        $starttime= trim($_POST['start_time']);
        $endtime= trim($_POST['end_time']);
    }

    if(empty($err))
    {
      $sql = "SELECT event_name FROM addevents WHERE event_name = ?";
      $stmt = mysqli_prepare($conn, $sql);
      if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_eventname);
            $param_eventname = $eventname; // Set the value of param username
              
            if(mysqli_stmt_execute($stmt)) // Try to execute this statement
            {
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) > 0)
                {
                  $err= "This Event already exists";
                  $_SESSION["err"] = $err;
                }

                else
                {
                  $eventname= trim($_POST['event_name']);
                  $type = trim($_POST['type']);
                  $description = trim($_POST['description']);
                  $startdate= trim($_POST['start_date']);
                  $enddate= trim($_POST['end_date']);
                  $starttime= trim($_POST['start_time']);
                  $endtime= trim($_POST['end_time']);
                  $err ="";
                }
            }
        }
      mysqli_stmt_close($stmt);  
    }



  if(empty($err))
  {
    $sql = "INSERT INTO addevents (event_name, type, description, start_date, end_date, start_time, end_time) VALUES (?,?,?,?,?,?,?)";    
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt)
      {
          mysqli_stmt_bind_param($stmt, "sssssss", $param_eventname, $param_type, $param_description, $param_startdate, $param_enddate, $param_starttime, $param_endtime);
          $param_eventname = $eventname; // Set these parameters
          $param_type = $type;
          $param_description = $description;
          $param_startdate = $startdate;
          $param_enddate = $enddate;
          $param_starttime = $starttime;
          $param_endtime = $endtime;

          if (mysqli_stmt_execute($stmt)) // Try to execute the query
          {
              $msg="Event successfully added";
              $_SESSION["msg"] = $msg;
          }

          else {
            $err="Something went wrong... cannot redirect!";
            $_SESSION["err"] = $err;
          }
        mysqli_stmt_close($stmt);
        }
  }
  mysqli_close($conn);
}

?>

<!doctype html>
<html lang="en">
  <head>
  <!--meta and css-->
  <?php include 'header_footer/meta_css.html';?> 
    <title>ADD EVENTS</title>
  </head>
  <body class="bg-dark">    
  <!--Nav-->
  <?php include 'header_footer/nav_admin.html';?> 

<div class="container text-light">
  
  <div class="alert alert-success mx-5 mt-3" role="alert">
      <?php 
          if(empty($_SESSION['err'])){
              echo $_SESSION["msg"];}
          else{
          echo $_SESSION["err"];}?>
  </div>  

  <form action="" method="post" class="mx-5 mb-5">
    
    <h4> ADD EVENTS </h4>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label for="event_name" class="form-label">Event Name</label>
        <input type="text" class="form-control" name="event_name" id="event_name" aria-describedby="emailHelp" required>
        <div id="emailHelp" class="form-text">Please enter the event name</div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-9 mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" name="description" id="description" aria-label="With textarea" required></textarea>
        <div id="emailHelp" class="form-text">Please enter the event description</div>
      </div>
      <div class="col-md-3">
        <label for="description" class="form-label">Type</label>        
        <select class="form-select mb-1" name="type" id="type" aria-label="Default select example" style="width: 10rem;" required>
        <option selected>Type of sport</option>
        <option value="indoor">Indoor</option>
        <option value="outdoor">Oudoor</option>
      </select>
      <div class="form-text mb-3">Please select if the sports is indoor or outdoor</div>        
      </div>
    </div>

    <div class="row justify-content-evenly">
      <div class="col-md-2">
        <label for="start_date" class="form-label">Start Date:</label>
        <input type="date" class="form-control" name="start_date" id="start_date" aria-describedby="emailHelp" required>
        <div id="emailHelp" class="form-text mb-3">Please enter the date when the event starts</div>
      </div>
      <div class="col-md-2">
        <label for="start_time" class="form-label">Event Start Time:</label>
        <input type="time" class="form-control" name="start_time" id="start_time" aria-describedby="emailHelp" required>
        <div id="emailHelp" class="form-text mb-3">Please enter the time when the event starts</div>
      </div>
      <div class="col-md-2">
        <label for="end_date" class="form-label">End Date:</label>
        <input type="date" class="form-control" name="end_date" id="end_date" aria-describedby="emailHelp" required>
        <div id="emailHelp" class="form-text mb-3">Please enter when the event ends</div>
      </div>
      <div class="col-md-2">
        <label for="end_time" class="form-label">Event End Time:</label>
        <input type="time" class="form-control timepicker" name="end_time" id="end_time" aria-describedby="emailHelp" required>
        <div id="emailHelp" class="form-text mb-3">Please enter the time when the event ends</div>
      </div>    
    </div>
    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
      <button type="submit" class="btn btn-warning me-md-2" value="submit"><span class="material-icons align-top">add</span> Add</button>
    </div>
  </form>

</div>

  <!--java scripts-->
  <?php include 'header_footer/scripts.html';?> 
  </body>
</html>
