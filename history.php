<?php 
  include_once './Backend/config/Database.php';
  include_once './Backend/model/student.php';

  $database = new Database();
  $db = $database->connect();
  $student = new Students($db);
  

  session_start();
  if(!isset($_SESSION['StudentID'])){ 
    header('location:index.php');
  }else{
    $student->id = $_SESSION['StudentID'];
    $result = $student->getPendingBooking();
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
  <title>Oasis - Booking History</title>

  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Bootstrap core CSS -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">

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
          <li>
            <a class="logout" href="logout.php">Logout</a>
          </li>
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
          <p class="centered"><a href="profile.html"><img src="./images/hussein.jpg" class="img-circle" width="80"></a></p>
          <h5 class="centered">Student</h5>
          <li class="mt">
            <a href="dashboard.php">
              <i class="fa fa-dashboard"></i>
              <span>Dashboard</span>
              </a>
          </li>
          <li class="sub-menu">
            <a href="history.php">
              <i class="fa fa-dashboard"></i>
              <span>Bookings</span>
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
    <section id="main-content">
      <section class="wrapper">
        <!-- row -->
        <div class="row mt">
          <div class="col-md-12">
            <div class="content-panel mx-auto p-3">
              <table class="table table-striped table-advance table-hover p-3 mx-auto">
                <h4> Booking Status</h4>
                <hr>
                <thead>
                  <tr>
                    <th><i class="fa fa-user" aria-hidden="true"></i> Lecturer</th>
                    <th class="hidden-phone"><i class="fa fa-calendar" aria-hidden="true"></i> Date</th>
                    <th><i class="fa fa-location-arrow" aria-hidden="true"></i> Office</th>
                    <th><i class=" fa fa-edit"></i> Status</th>
                    <th><i class=" fa fa-type"></i> Booking Type</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  while ( $row = $result->fetch(PDO::FETCH_ASSOC)){
                  ?>

                  <tr>
                    <td>
                      <a href="#"><?php echo $row["Faculty"] ?></a>
                    </td>
                    <td class="hidden-phone"><?php echo $row["BookingDate"] ?></td>
                    <td>Room 205E Engineering</td>
                    
                      
                    <td><?php echo $student->historybooking($row['BookingStatus'])?></td>   
                    <td>Office Hours </td>               
                  </tr>
                  <?php }?>
                <!--  <tr>
                    <td>
                      <a href="#">
                        Dr Afsanat
                        </a>
                    </td>
                    <td class="hidden-phone">12/04/2021r</td>
                    <td>Room 205E Engineering</td>
                    <td><span class="label label-warning label-mini">Pending...</span></td>
                  </tr>
                  <tr>
                    <td>
                      <a href="#">
                        Dr Stacy
                        </a>
                    </td>
                    <td class="hidden-phone">12/04/2021</td>
                    <td>Room 205E Engineering</td>
                    <td><span class="label label-success label-mini">Approved</span></td>
                  </tr>
                  <tr>
                    <td>
                      <a href="#">Dr Aziz</a>
                    </td>
                    <td class="hidden-phone">12/04/2021</td>
                    <td>Room 205E Engineering</td>
                    <td><span class="label label-success label-mini">Approved</span></td>
                  </tr>
                   <tr>
                    <td>
                      <a href="#">Dr Anthony</a>
                    </td>
                    <td class="hidden-phone">12/04/2021</td>
                    <td>Room 205E Engineering </td>
                    <td><span class="label label-warning label-mini">Pending...</span></td>
                  </tr> -->
                </tbody>
              </table>
            </div>
            <!-- /content-panel -->
          </div>
          <!-- /col-md-12 -->
        </div>
        <!-- /row -->
      </section>
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
