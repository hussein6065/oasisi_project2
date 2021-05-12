<?php 
  include_once './Backend/config/Database.php';
  include_once './Backend/model/faculty.php';

  $database = new Database();
  $db = $database->connect();
  $faculty = new Faculty($db);
  

  session_start();
  if(!isset($_SESSION['FacultyID'])){ 
    header('location:index.php');
  }else{
    $faculty->id = $_SESSION['FacultyID'];
    $result = $faculty->getCourses();
  }
  
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="Dashboard">
  <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
  <title>Oasis</title>

  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
 
  <!-- Bootstrap core CSS -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">

</head>

<body>
  <section id="container">
    <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
    <!--header start-->
    <header class="header black-bg">
      <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
      </div>
      <!--logo start-->
      <a href="index.html" class="logo"><b>OAS<span>IS</span></b></a>
      <!--logo end-->
      <div class="top-menu">
        <ul class="nav pull-right top-menu">
          <li><a class="logout" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </header>
    <!--header end-->
    <!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    <aside>
      <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
          <p class="centered"><a href="profile.html"><img src="images/hussein.jpg" class="img-circle" width="80"></a></p>
          <h5 class="centered">Faculty</h5>
          <li class="mt">
            <a href="lecturer_dashboard.php">
              <i class="fa fa-dashboard"></i>
              <span>Dashboard</span>
              </a>
          </li>
          <li class="sub-menu">
            <a href="lecturer_history.php">
              <i class="fa fa-dashboard"></i>
              <span>Bookings</span>
              </a>
              
          </li>
          <li class="sub-menu">
            <a href="edit.php">
              <i class="fa fa-dashboard"></i>
              <span>Edit Office Hours</span>
              </a>
              
          </li>
        </ul>
        <!-- sidebar menu end-->
      </div>
    </aside>
    <!--sidebar end-->
    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content" class="container">
      <section class="wrapper site-min-height">
        <div class="row mt">
          <div class="col-lg-12 col-md-12 col-sm-12">
          <?php 
              while ( $row = $result->fetch(PDO::FETCH_ASSOC)){
          ?>
            <div class="row content-panel" >
              <div class="col-md-4 col-sm-12 col-xs-12 profile-text mt mb centered">
                <div class="right-divider"> <!-- hidden-sm hidden-xs // used to hide on small screens-->
                  <h4>OFFICE HOURS</h4> <br>
                  <div><span>From: <?php 
                  $dates = $faculty->getCourseAvailability($row['CourseID']);
                  $data = $dates->fetch(PDO::FETCH_ASSOC);
                  // echo $data['TimeStart'];
                  ?></span> <span>To <?php echo 1 //$data['TimeEnd'];?></span></div>
                  <h4>OFFICE LOCATION</h4> <br>
                  <h6>Room 205E Engineering</h6>
                  
                  <h3><?php echo $row['CourseName']?></h3>
                  
                  <h3><?php echo $faculty->getNumBookingPerDay($row['CourseID'])?>/4</h3>
                </div>
              </div>
              <!-- /col-md-4 -->
              <div class="col-md-4 col-sm-12 col-xs-12 profile-text centered">
                <div class="right-divider">
                <h3><?php echo $row['CourseName']?></h3> <br>
                <div> <?php  echo $faculty->getAvailability() ?></div>
                <br>
                <p><a href='edit.php' class="btn btn-theme">View Bookings</a></p>
              
                </div>
              </div>
              <!-- /col-md-4 -->
              <!-- <div class="col-md-4 col-sm-12 col-xs-12 centered">
                <div class="profile-pic">
                  <p><img src="images/hussein.jpg" class="img-circle"></p>
                 
                </div>
              </div> -->
              <!-- /col-md-4 -->
            </div>
            <hr>
           
            <!-- /row -->
            <?php } ?>
          </div>
         
        </div>

        

        <!-- /container -->
      </section>
      <!-- /wrapper -->
    </section>

    <!-- /MAIN CONTENT -->
    <!--main content end-->

  </section>

  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="lib/jquery.scrollTo.min.js"></script>
  <script src="lib/jquery.nicescroll.js" type="text/javascript"></script>
  <!--common script for all pages-->
  <script src="lib/common-scripts.js"></script>
  <!--script for this page-->
</body>

</html>
<script>
  var form = document.getElementById("formInput");
  
  function Submit(event){
    event.preventDefault();
    var id = localStorage.getItem("BookingId");
    var time = form['T'].value;
    var day = form['day'].value;
    var data = {
      time:time,
      day:day,
      lectureId: id
    }
    console.log(data)
    var serverCall = new XMLHttpRequest();
				serverCall.open('POST', './Backend/api/change.php', true);

				serverCall.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						if (this.response === 0) {
						alert("Problem Occured")
						} else {
              location.reload();
							
							// var rData = JSON.parse(this.responseText);

							// console.log(this.response);
							// console.log(this.responseText);
						}
					}
				};
        serverCall.send(JSON.stringify(data));
    console.log('Hussein I am trying to submit');

  }            
  form.addEventListener('submit',Submit)
  function getId(e) {
    localStorage.setItem("BookingId",e.target.id);
    console.log(e.target.id);
  }



  

</script>


