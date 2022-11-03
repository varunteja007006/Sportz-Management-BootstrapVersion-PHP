<?php
session_start();
$msg = "Welcome ";
$_SESSION['msg']=$msg.$_SESSION['username'];

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
{
    header("location: student_login.php");
}

require_once "config.php";
$eventname=$firstplace=$secondplace=$thirdplace= $err="";

//if ($_SERVER['REQUEST_METHOD'] == "POST")
//{
//    if(empty(trim($_POST['ename'])))
//    {
//        $err = "Please enter event name";
//        echo "<script>alert('$err');</script>";
//    }
//    else
//    {         
//        $eventname= trim($_POST['ename']);
//    }
//    //unable to check whether the event already exists in bellow lines
//    if(empty($err))
//    {
//      $sql = "SELECT ename,first,second,third FROM results WHERE ename = ?";
//      $stmt = mysqli_prepare($conn, $sql);
//
//      mysqli_stmt_bind_param($stmt, "s", $param_ename);
//      $param_ename = $eventname;
//      if(mysqli_stmt_execute($stmt))
//      {
//        mysqli_stmt_store_result($stmt);
//        if(mysqli_stmt_num_rows($stmt)>0)
//        {
//          mysqli_stmt_bind_result($stmt,$ename,$first,$second,$third);
//          if(mysqli_stmt_fetch($stmt))
//          {
//            $eventname=$ename;
//            $firstplace=$first;
//            $secondplace=$second;
//            $thirdplace=$third;
//          }
//        }
//      }
//        mysqli_stmt_close($stmt); 
//      }       
//} 
//mysqli_close($conn);
?>

<!doctype html>
<html lang="en">
  <head>
    <!--meta and css-->
    <?php include 'header_footer/meta_css.html';?> 
    <title>Results</title>
  </head>
  <body class="bg-dark">    
  <!--Nav-->
  <?php include 'header_footer/nav_student.html';?> 

<div class="container">
  
    <div class="alert alert-success mx-5 mt-3" role="alert">
        <?php echo $_SESSION['msg']?>
    </div>

<section class="mx-5 mt-3 text-light">
  <h4 class="mt-3 mb-3 text-center">RESULTS</h4> 
  <form action="" method="post">  
    <table class="table table-primary table-striped">
      <thead>
        <tr>
          <th>Event Name</th>
          <th>First Prize</th>
          <th>Second Prize</th>
          <th>Third Prize</th>
        </tr>
      </thead>
      <tbody>  
        <?php 
          require_once "config.php";
          $row="";
          $username=$_SESSION['username'];
          $sql = "SELECT * from results WHERE event_name IN (SELECT event_name FROM book_events WHERE username='$username' ORDER BY created_at); "; 
          $result = $conn->query($sql);     
          
                if ($result)
                {
                while ($row = $result->fetch_assoc()) 
                {
                  $eventname = $row["event_name"];
                  $first = $row["first"];
                  $second = $row["second"];
                  $third = $row["third"];

                  echo '<tr> 
                    <td>'.$eventname.'</td>
                    <td>'.$first.'</td> 
                    <td>'.$second.'</td> 
                    <td>'.$third.'</td> <!--
                    <td><button type="submit" name="delete_result" value="" class="btn btn-primary btn-sm"><span class="material-icons align-bottom">delete_forever</span></button></td>  -->                   
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

  <!--java scripts-->
  <?php include 'header_footer/scripts.html';?> 

  </body>
</html>
