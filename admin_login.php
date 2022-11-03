<?php
//This script will handle login
session_start();

// check if the user is already logged in
if(isset($_SESSION['username']))
{
    header("location:add_events.php");
    exit;
}

require_once "config.php";

$username = $password = "";
$err = "";

// if request method is post
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if(empty(trim($_POST['username'])) || empty(trim($_POST['password'])))
    {
        $err = "Please enter username + password";
        echo "<script>alert('$err');</script>";
    }
    else{
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }


if(empty($err))
{
    $sql = "SELECT id, username, password FROM admin WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $username;

    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) == 1)
        {
            mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
            if(mysqli_stmt_fetch($stmt))
            {
                if(password_verify($password, $hashed_password))
                {
                    session_start();
                    $_SESSION["username"] = $username;
                    $_SESSION["id"] = $id;
                    $_SESSION["adminloggedin"] = true;

                    header("location: add_events.php");
                }
            }

        }

    }
}    


}

?>
<!doctype html>
<html lang="en">
<html>
<head>
  <!--meta & css-->
  <?php include 'header_footer/meta_css.html';?> 
<title>Admin Login</title>
</head>
<body class="bg-dark">
  <!--nav bar-->
  <?php include 'header_footer/nav.html';?> 
<div class="container">
<div class="row">
  <div class="col m-5">
    <div class="card" style="width: 30rem;">  
      <div class="card-body">
          <span class="material-icons md-48 m-2 red">
            manage_accounts
          </span>             
        <h5 class="card-title">ADMIN LOGIN</h5>  
        <form action="" method="post">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="username" aria-describedby="emailHelp">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password">
          </div>
          <button type="submit" class="btn btn-danger rounded-pill" name="" value="Login">Login</button>
        </form>
        <ul class="list-group mt-3">
          <a href="student_login.php" class="list-group-item list-group-item-action text-light bg-primary">Looking for student login?</a>
        </ul>
      </div>
    </div>
  </div>
</div>
</div>

  <!--java scripts-->
  <?php include 'header_footer/scripts.html';?> 
</body>

</html>