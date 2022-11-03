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
$deleteevent= $eventname= $username= $err= $deleteresult="";
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
  if(empty($deleteevent))
  {
    $err="Some data is missing to proceed. Please try again later.";
    $_SESSION["err"] = $err;
  }

  else{
  $sql = "SELECT event_name FROM addevents WHERE event_name='$deleteevent'"; 
  $result = $conn->query($sql);     
        
    if ($result){
      while ($row = $result->fetch_assoc()){
        $eventname = $row["event_name"];
        if($eventname == $deleteevent)
        { //Delete if everything is fine
          $sql_delete="DELETE FROM addevents WHERE event_name= ?";
          $stmt_delete= mysqli_prepare($conn, $sql_delete);
          if($stmt_delete)
          {
            mysqli_stmt_bind_param($stmt_delete,"s",$param_deleteevent);
                  $param_deleteevent=$deleteevent;
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
        //need else no matching statement
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
    <title>Dashboard</title>
  </head>
  <body>    
  <!--Nav-->
  <?php include 'header_footer/nav_admin.html';?> 

<div class="container-fluid">
  
  <div class="alert alert-success mt-3" role="alert">
    <?php echo $_SESSION["msg"]?> 
  </div>

<div class="accordion" id="accordionPanelsStayOpenExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-allevents" aria-expanded="true" aria-controls="panelsStayOpen-allevents">
      <span class="material-icons mx-2">groups</span> EVENTS
      </button>
    </h2>
    <div id="panelsStayOpen-allevents" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
      <div class="accordion-body bg-danger">
        <form action="" method="post"> 
        <div class="row">
        <?php 
          require_once "config.php";
          $row="";
          $sql = "SELECT * FROM addevents ORDER BY start_date"; 
                if ($result = $conn->query($sql))
                {
                while ($row = $result->fetch_assoc()) 
                {
                  $eventname = $row["event_name"];
                  $eventdescription = $row["description"];
                  $type = $row["type"];
                  $startdate = $row["start_date"]; 
                  $enddate = $row["end_date"]; 
                  $starttime = $row["start_time"]; 
                  $endtime = $row["end_time"]; 
                  $createdat = $row["created_at"]; 
                  $start_date = date("d-m-Y", strtotime($startdate));
                  $end_date = date("d-m-Y", strtotime($enddate));

 
                  echo '
                  <div class="col mb-3">
                    <div class="card border-warning" style="width: 18rem;">
                      <div class="card-body">
                        <h5 class="card-title font-monospace">'.$eventname.'</h5>
                        <p class="card-text">'.$eventdescription.'</p>
                      </div>
                      <ul class="list-group list-group-flush">
                        <li class="list-group-item"><p>Type of Sport: <b class="font-monospace">'.$type.'</b>.</p>
                        <p>Starts on <b class="font-monospace">'.$start_date.'</b> at <b class="font-monospace">'.$starttime.'</b> hours.</p>
                        <p>Ends on <b class="font-monospace">'.$end_date.'</b> at <b class="font-monospace">'.$endtime.'</b> hours.</p>
                        </li>
                      </ul>
                      <div class="card-footer fs-6 fw-light"> Created at: <b class="font-monospace">
                        '.$createdat.' </b>
                      <button type="submit" name="delete_event" value="'.$eventname.'" class="btn btn-primary btn-sm ms-4 me-0"><span class="material-icons align-bottom ">delete_forever</span></button>                     
                      </div>  
                    </div>
                  </div>';
                }
                  $result->free();
                }      
        ?>
        </div>
      </form>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-allresults" aria-expanded="false" aria-controls="panelsStayOpen-allresults">
      <span class="material-icons mx-2">emoji_events</span> RESULTS 
      </button>
    </h2>
    <div id="panelsStayOpen-allresults" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
      <div class="accordion-body bg-warning">
        <div class="row">
        <?php 
          require_once "config.php";
          $row="";
          $sql = "SELECT * FROM results ORDER BY created_at"; 
                if ($result = $conn->query($sql))
                {
                while ($row = $result->fetch_assoc()) 
                {
                  $eventname = $row["event_name"];
                  $first = $row["first"];
                  $second = $row["second"]; 
                  $third = $row["third"]; 
                  $createdat = $row["created_at"]; 
 
                  echo '
                  <div class="col mb-3">
                    <div class="card border-danger" style="width: 18rem;">
                      <div class="card-body">
                        <h5 class="card-title font-monospace">'.$eventname.'</h5>
                      </div>
                      <ul class="list-group list-group-flush">
                        <li class="list-group-item"><p>First prize: <b class="font-monospace">'.$first.'</b></p>
                        </li>
                        <li class="list-group-item"><p>Second prize: <b class="font-monospace">'.$second.'</b></p>
                        </li>
                        <li class="list-group-item"><p>Thrid prize: <b class="font-monospace">'.$third.'</b></p>
                        </li>                                                
                      </ul>
                      <div class="card-footer fs-6 fw-light"> Created at: <b class="font-monospace">
                        '.$createdat.' </b>
                      <button type="submit" name="delete_result" value="'.$eventname.'" class="btn btn-primary btn-sm ms-4 me-0"><span class="material-icons align-bottom ">delete_forever</span></button>                         
                      </div>  
                    </div>
                  </div>';
                }
                  $result->free();
                }      
        ?>
        </div>
      </div>
    </div>
  </div>

</div>

</div>


  <!--java scripts-->
  <?php include 'header_footer/scripts.html';?> 

  </body>
</html>
