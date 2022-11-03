<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
{
    header("location: student_login.php");
    exit;
}

$msg="Welcome ";
$_SESSION["msg"] = $msg.$_SESSION['username'];
require_once "config.php";
$bookevent= $eventname= $username= $err="";
$_SESSION["err"]= $err;
//check if empty value passed
if($_SERVER['REQUEST_METHOD'] =="POST")
{
  if(empty(trim($_POST['book_event'])))
  {
    $err ="please enter the event name";
    $_SESSION["err"] = $err;
  }
  else{
    $bookevent=trim($_POST['book_event']);
    $err ="";
    $_SESSION["err"] = $err;    
  }
//check if event actually exists 
  if(empty($err))
  {
    $sql="SELECT event_name FROM addevents WHERE event_name=?";
    $stmt =mysqli_prepare($conn,$sql);
    mysqli_stmt_bind_param($stmt,"s",$param_eventname);
    $param_eventname=$bookevent;
  
    if(mysqli_stmt_execute($stmt))
    {
      mysqli_stmt_store_result($stmt);
      if(mysqli_stmt_num_rows($stmt)>0)
      {
        $bookevent=trim($_POST['book_event']);
        $err ="";
        $_SESSION["err"] = $err; 
      }  
        else{
          $err="No such event exists";
          $_SESSION["err"] = $err;
        }
      } 
      mysqli_stmt_close($stmt);
  }
//check if its already booked
  if(empty($bookevent)||empty($_SESSION['username']))
  {
    $err="Some data is missing to proceed";
    $_SESSION["err"] = $err;
  }

  else{
  $username=$_SESSION['username'];
  $sql = "SELECT event_name FROM book_events WHERE username='$username'"; 
  $result = $conn->query($sql);     
        
    if ($result){
      while ($row = $result->fetch_assoc()){
        $eventname = $row["event_name"];
        if($eventname == $bookevent){
          $err="Event is already registered";
          $_SESSION["err"]=$err;  
          break;         
        }
        else{
          $err="";
          $_SESSION["err"]=$err;           
        }
      }
    $result->free(); } 
    }

//Insert if everything is fine
if(empty($err))
{
  $sql="INSERT INTO book_events (event_name,username) VALUES (?,?)";
  $stmt= mysqli_prepare($conn, $sql);
  if($stmt)
  {
    mysqli_stmt_bind_param($stmt,"ss",$param_eventname,$param_username);
          $param_eventname=$bookevent;
          $param_username=$_SESSION['username'];
        if(mysqli_stmt_execute($stmt))
        {
          $msg="The Event is booked successfully!!!";
          $_SESSION["msg"]=$msg;
        }
        else
        {
          $err= "Something went wrong... cannot book!";
          $_SESSION["err"]=$err;
        }
  }
  mysqli_stmt_close($stmt);
}

}
?>

<!doctype html>
<html lang="en">
  <head>
    <!--meta and css-->
    <?php include 'header_footer/meta_css.html';?> 
    <title>Register Events</title>
  </head>
  <body class="bg-dark">    
  <!--Nav-->
  <?php include 'header_footer/nav_student.html';?> 

<div class="container">

  <div class="alert alert-success mx-5 mt-3" role="alert">
    <?php 
        if(empty($_SESSION['err'])){
            echo $_SESSION["msg"];}
        else{
        echo $_SESSION["err"];}?>
  </div>  

  <section class="text-light mx-5 mb-5"> 
 
    <form action="" method="post" class="border-bottom border-2 border-primary">
      <label for="book_event" class="form-label">Book your event here!!</label>
      <input class="form-control form-control-lg" list="datalistOptions" name="book_event" id="book_event" placeholder="Type to search...">
      <datalist id="datalistOptions">
        <?php 
          require_once "config.php";
          $sql = "SELECT * FROM addevents"; 
                if ($result = $conn->query($sql))
                {
                while ($row = $result->fetch_assoc()) 
                {
                  
                  $eventname = $row["event_name"];
                  echo '<option value="'.$eventname.'">';
                }
                  $result->free();
                }      
        ?>
      </datalist>  
      <button type="submit" name="submit" value="submit" class="btn btn-warning mt-3 mb-3">Book</button>
    </form>
    <h4 class="mt-3 mb-3 text-center">EVENTS</h4>
    <form action="" method="post">    
      <table class="table table-primary table-hover table-striped">
        <thead>
          <tr class="">
            <th>Event Name</th>
            <th>Type</th>
            <th>Start Date</th>
            <th>Start Time</th>
            <th>End Date</th>
            <th>End Time</th>
            <th>Book</th>
          </tr>
        </thead>
        <tbody>  
          <?php 
            require_once "config.php";
            $row="";
            $sql = "SELECT * FROM addevents ORDER BY start_date"; 
                  if ($result = $conn->query($sql))
                  {
                  while ($row = $result->fetch_assoc()) 
                  {
                    //$id = $row["id"];
                    $eventname = $row["event_name"];
                    $type = $row["type"];
                    $startdate = $row["start_date"]; 
                    $enddate = $row["end_date"]; 
                    $starttime = $row["start_time"]; 
                    $endtime = $row["end_time"]; 
                    $start_date = date("d-m-Y", strtotime($startdate));
                    $end_date = date("d-m-Y", strtotime($enddate));
                    echo '<tr> 
                      <td>'.$eventname.'</td> 
                      <td>'.$type.'</td> 
                      <td>'.$start_date.'</td> 
                      <td>'.$starttime.'</td> 
                      <td>'.$end_date.'</td>
                      <td>'.$endtime.'</td> 
                      <td><button type="submit" name="book_event" value="'.$eventname.'" class="btn btn-warning btn-sm">Book</button></td> 
                      </tr>';
                  }
                    $result->free();
                  }
          ?>        
        </tbody>
      </table>
    </form>  
</section> 

</div>

    <!--java scripts-->
    <?php include 'header_footer/scripts.html';?> 

  </body>
</html>