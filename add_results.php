<?php

session_start();
$msg="Welcome Admin ";
$_SESSION["msg"] = $msg.$_SESSION['username'];

if(!isset($_SESSION['adminloggedin']) || $_SESSION['adminloggedin'] !==true)
{
    header("location: admin_login.php");
    exit;
}

require_once "config.php";
$eventname= $firstprize = $secondprize = $thirdprize= $err ="";
$_SESSION["err"] = $err;
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(empty(trim($_POST['event_name'])) || empty(trim($_POST['first'])) || empty(trim($_POST['second'])) || empty(trim($_POST['third'])))
    {
        $err = "Please fill all details";
        $_SESSION["err"] = $err;
    }
    else
    {
        $eventname= trim($_POST['event_name']);
        $firstprize = trim($_POST['first']);
        $secondprize = trim($_POST['second']);
        $thirdprize= trim($_POST['third']);
        $err="";
        $_SESSION["err"] = $err;            
    }
//check if result already exists
    if(empty($err))
    {
        $sql = "SELECT event_name FROM results WHERE event_name = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_eventname);
            $param_eventname = $eventname;
            if(mysqli_stmt_execute($stmt))
            {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) > 0)
                {
                    $err="Result for this event already exists!!";
                    $_SESSION["err"] = $err;
                }

                else{
                        $eventname= trim($_POST['event_name']);
                        $firstprize = trim($_POST['first']);
                        $secondprize = trim($_POST['second']);
                        $thirdprize= trim($_POST['third']);
                        $err="";
                        $_SESSION["err"] = $err;                        
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
//check if event exists
    if(empty($err))
    {
        $sql = "SELECT event_name FROM addevents WHERE event_name = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_eventname);
            $param_eventname = $eventname;
            if(mysqli_stmt_execute($stmt))
            {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) > 0)
                {
                    $eventname= trim($_POST['event_name']);
                    $firstprize = trim($_POST['first']);
                    $secondprize = trim($_POST['second']);
                    $thirdprize= trim($_POST['third']);
                    $err="";
                    $_SESSION["err"] = $err;   
                }

                else{                    
                    $err="Event does not exists!!";
                    $_SESSION["err"] = $err;
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
//Finally insert event into the DB    
    if(empty($err))
    {
        $sql = "INSERT INTO results (event_name, first, second, third) VALUES (?,?,?,?)"; 
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt)
        {
            mysqli_stmt_bind_param($stmt, "ssss", $param_eventname, $param_first, $param_second, $param_third);
            $param_eventname = $eventname;
            $param_first = $firstprize;
            $param_second = $secondprize;
            $param_third = $thirdprize;

            if (mysqli_stmt_execute($stmt))
            {
                $msg="Results for the event {$eventname} successfully added";
                $_SESSION["msg"] = $msg;
            }   
            else{
                $err= "Something went wrong... cannot redirect!";
                $_SESSION["err"] = $err;
            }
    
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>

<!doctype html>
<html lang="en">
  <head>
  <!--meta and css-->
  <?php include 'header_footer/meta_css.html';?> 
    <title>ADD RESULTS</title>
  </head>
  <body class="bg-dark">    
  <!--Nav-->
  <?php include 'header_footer/nav_admin.html';?> 

<div class="container-fluid text-light">

    <div class="alert alert-success mt-3" role="alert">
        <?php 
            if(empty($_SESSION['err'])){
                echo $_SESSION["msg"];}
            else{
            echo $_SESSION["err"];}?>
    </div>                       

<div class="row mx-2">
    <h4 class="text-center">ADD RESULTS</h4>
    <!--Form to add results-->    
    <div class="col-md-6 border-end border-warning">
    <form action="" method="post">
        <div class="row mb-2">    
            <div class="col-md-12">
                <label for="event_name" class="form-label">Event Name</label>
                <input type="text" class="form-control" list="eventOptions" name="event_name" id="event_name" aria-describedby="eventnamehelp" required>
                <div id="eventnamehelp" class="form-text">Please enter the event name</div>
                <datalist id="eventOptions">
                    <?php 
                      require_once "config.php";
                      $sql = "SELECT * FROM addevents"; 
                            if ($result = $conn->query($sql))
                            {
                            while ($row = $result->fetch_assoc()) 
                            {
                              
                              $eventname = $row["event_name"];
                            echo '<option value="'.$eventname.'">'.$eventname.'</option>';
                            }
                              $result->free();
                            }      
                    ?>
                </datalist>       
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12">
                <label for="first" class="form-label">1st Prize Winner (Username)</label>
                <input type="text" class="form-control" name="first" id="first" required>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12">
                <label for="second" class="form-label">2nd Prize Winner (Username)</label>
                <input type="text" class="form-control" name="second" id="second" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="third" class="form-label">3rd Prize Winner (Username)</label>
                <input type="text" class="form-control" name="third" id="third" required>
            </div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
            <button type="submit" class="btn btn-warning me-md-2" value="submit"><span class="material-icons align-top">add</span> Add</button>
        </div>
    </form>        
    </div>
    <!--Dropdown to add results-->
    <div class="col-md-6">
    <form action="" method="post">
        <div class="row mb-2">
            <div class="col-md-12">
                <label for="event_name" class="form-label">Event Name</label>  
                <select class="form-select" name="event_name" id="event_name" aria-describedby="eventnamehelp" required>
                    <option selected>Select the event name from below list</option>
                    <?php 
                      require_once "config.php";
                      $sql = "SELECT * FROM addevents"; 
                            if ($result = $conn->query($sql))
                            {
                            while ($row = $result->fetch_assoc()) 
                            {
                              
                              $eventname = $row["event_name"];
                              echo '
                              <option value="'.$eventname.'">'.$eventname.'</option>';
                            }
                              $result->free();
                            }      
                    ?>
                </select> 
                <div id="eventnamehelp" class="form-text">Please select the event name</div>                                 
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12">
                <label for="first" class="form-label">1st Prize</label>  
                <select class="form-select" name="first" id="first" required>
                    <option selected>Select the username from below list</option>
                    <?php 
                      require_once "config.php";
                      $sql = "SELECT * FROM users"; 
                            if ($result = $conn->query($sql))
                            {
                            while ($row = $result->fetch_assoc()) 
                            {
                              
                              $username = $row["username"];
                              echo '
                              <option value="'.$username.'">'.$username.'</option>';
                            }
                              $result->free();
                            }      
                    ?>
                </select>                    
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12">
                <label for="second" class="form-label">2nd Prize</label>  
                <select class="form-select" name="second" id="second" required>
                    <option selected>Select the username from below list</option>
                    <?php 
                      require_once "config.php";
                      $sql = "SELECT * FROM users"; 
                            if ($result = $conn->query($sql))
                            {
                            while ($row = $result->fetch_assoc()) 
                            {
                              
                              $username = $row["username"];
                              echo '
                              <option value="'.$username.'">'.$username.'</option>';
                            }
                              $result->free();
                            }      
                    ?>
                </select>                    
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="third" class="form-label">3rd Prize</label> 
                <select class="form-select" name="third" id="third" required>
                    <option selected>Select the username from below list</option>
                    <?php 
                      require_once "config.php";
                      $sql = "SELECT * FROM users"; 
                            if ($result = $conn->query($sql))
                            {
                            while ($row = $result->fetch_assoc()) 
                            {
                              
                              $username = $row["username"];
                              echo '
                              <option value="'.$username.'">'.$username.'</option>';
                            }
                              $result->free();
                            }      
                    ?>
                </select>                    
            </div>        
        </div>        
        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
            <button type="submit" class="btn btn-warning me-md-2" value="submit"><span class="material-icons align-top">add</span> Add</button>
        </div>
    </form>                
    </div>
</div>

</div>    
    <!--java scripts-->
    <?php include 'header_footer/scripts.html';?> 

  </body>
</html>