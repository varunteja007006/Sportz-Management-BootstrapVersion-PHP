<!doctype html>
<html lang="en">
  <head>
  <!--meta and css-->
  <?php include 'header_footer/meta_css.html';?> 
    <title>Home</title>
  </head>
  <body class="bg-dark">    
  <!--Nav-->
  <?php include 'header_footer/nav.html';?> 

  <!--Carousel-->
  <div id="carousel_homepage" class="carousel slide carousel-fade container" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="media/home_1.jpg" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="media/home_2.jpg" class="d-block w-100" alt="...">
      </div>        
      <div class="carousel-item">
        <img src="media/home_3.jpg" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="media/home_4.jpg" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="media/home_5.jpg" class="d-block w-100" alt="...">
      </div>    
    </div>
  </div> 
 
  <div class="card bg-dark text-white mt-3 border border-3 border-warning rounded-3 position-absolute" style="z-index: 1;  top: 60%; left: 50%; transform: translate(-50%, -50%);opacity: 0.9;">
    <div class="card-body position-relative ">
      <h4 class="card-title">About Sportz Management System</h4>
      <h5 class="card-title">This website is to manage sports events.</h5>
      <p class="card-text"> 
        <div class="card bg-warning text-black mb-5 p-3" id='showsport' ></div>
      </p> 
      <input class="btn position-absolute bottom-0 start-0 bg-warning m-3 rounded-pill" type="button" onclick="previous()" value="<<">
      <input class="btn position-absolute bottom-0 end-0 bg-warning m-3 rounded-pill" type="button" onclick="next()" value=">>">
    </div>
  </div>      

<!--footer-->
<?php include 'header_footer/footer.html';?> 
  <script>
    var i = 0;
    var x;
    displaysport(i);
    
    function displaysport(i) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          myFunction(this, i);
        }
      };
      xmlhttp.open("GET", "xmlfile.xml", true);
      xmlhttp.send();
    }
    
    function myFunction(xml, i) {
      var xmlDoc = xml.responseXML; 
      x = xmlDoc.getElementsByTagName("SPORT");
      document.getElementById("showsport").innerHTML = "<strong><h6>Sports Wiki - Snippets of information about different sports played around the world. Use the navigation buttons to go through them.</h6></strong><strong>" +
      x[i].getElementsByTagName("NAME")[0].childNodes[0].nodeValue + "</strong> - " + 
      x[i].getElementsByTagName("DESCRIPTION")[0].childNodes[0].nodeValue ;
    }
    
    function next() {
    if (i < x.length-1) {
      i++;
      displaysport(i);
      }
    }
    
    function previous() {
    if (i > 0) {
      i--;
      displaysport(i);
      }
    }
  </script>
  <!--java scripts-->
  <?php include 'header_footer/scripts.html';?> 

  </body>
</html>
