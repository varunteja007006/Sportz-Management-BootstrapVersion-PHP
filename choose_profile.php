<!doctype html>
<html lang="en">
  <head>
  <!--meta and css-->
  <?php include 'header_footer/meta_css.html';?>  

    <title>Choose profile</title>
  </head>
  <body class="bg-dark">    
  <!--Nav-->
  <?php include 'header_footer/nav.html';?>  

  <div class="container">
    <div class="row p-3">
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-12 m-2">
            <div class="card rounded-3" style="width: 22rem;">  
              <div class="card-body">
                  <span class="material-icons md-48 m-2 red">
                    manage_accounts
                  </span>             
                <h5 class="card-title">ADMIN LOGIN</h5>  
                <p class="card-text">Login here if you are an administrator</p>
                <a href="admin_login.php" class="btn btn-danger rounded-pill">login</a>
              </div>
            </div>
          </div>
          <div class="col-md-12 m-2">  
            <div class="card rounded-3" style="width: 22rem;">  
              <div class="card-body"> 
                <span class="material-icons md-48 m-2 blue">
                  person
                </span>        
                <h5 class="card-title">STUDENT LOGIN</h5>  
                <p class="card-text">Login here if you are a student</p>
                <a href="student_login.php" class="btn btn-primary rounded-pill">login</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
  </div>
  <!--footer-->
  <?php include 'header_footer/footer.html';?>  
  <!--java scripts-->
  <?php include 'header_footer/scripts.html';?> 
  </body>
</html>
