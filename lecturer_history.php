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
    $result = $faculty->getPendingBooking();
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
  <title>Oasis - Admin</title>

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
      <a href="#" class="logo"><b>DASH<span>IO</span></b></a>
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
    <section id="main-content">
      <section class="wrapper">
        <!-- row -->
        <div class="row mt">
          <div class="col-md-12">
            <div class="content-panel">
              <table class="table table-striped table-advance table-hover">
                <h4> Booking Status</h4>
                <hr>
                <thead>
                  <tr>
                    <th><i class="fa fa-user" aria-hidden="true"></i> Student Name</th>
                    <th class="hidden-phone"><i class="fa fa-calendar" aria-hidden="true"></i> Date</th>
                    <th><i class="fa fa-edit" aria-hidden="true"></i> Status</th>
                    <th><i class=" fa fa-location-arrow"></i> Action</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                  while ( $row = $result->fetch(PDO::FETCH_ASSOC)){
                  ?>

                  <tr >
                    <td>
                      <a href="#"><?php echo $row['Student']?></a>
                    </td>
                    <td class="hidden-phone"><?php  
                 echo $faculty->formatTime($row['Booked_time'],$row['Booked_day'])
                  ?></td>
                    <td><?php echo $faculty->historybooking($row['BookingStatus'])?></td>  
                    <td>
                      <button class="btn btn-success btn-s" id="<?php echo $row['BookingID']?>" onclick="Approve(event)"><i class="fa fa-check" id="<?php echo $row['BookingID']?>"></i></button>
                      <!--<button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>-->
                      <button onclick="Delete(event)" class="btn btn-danger btn-s" id="<?php echo $row['BookingID']?>"><i id="<?php echo $row['BookingID']?>" class="fa fa-trash-o "></i></button>
                    </td>                  
                  </tr>
                  <?php }?>
                  <!-- <tr> 
                    <td>
                      <a href="#">
                        Dr Afsanat
                        </a>
                    </td>
                    <td class="hidden-phone">12/04/2021r</td>
                    <td><span class="label label-warning label-mini">Pending...</span></td>
                    <td>
                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a href="#">
                        Dr Stacy
                        </a>
                    </td>
                    <td class="hidden-phone">12/04/2021</td>
                    <td><span class="label label-success label-mini">Approved</span></td>
                    <td>
                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a href="#">Dr Aziz</a>
                    </td>
                    <td class="hidden-phone">12/04/2021</td>
                    <td><span class="label label-success label-mini">Approved</span></td>
                    <td>
                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a href="#">Dr Anthony</a>
                    </td>
                    <td class="hidden-phone">12/04/2021</td>
                    <td><span class="label label-warning label-mini">Pending...</span></td>
                    <td>
                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                    </td>
                  </tr>-->
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

<script>

function Approve(e) {
    // localStorage.setItem("BookingId",e.target.id);
    var k = e.target.id;
    var data = {
      lectureId:k,
      status:'T'
    }
    var serverCall = new XMLHttpRequest();
				serverCall.open('POST', './Backend/api/actionBooking.php', true);

				serverCall.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						if (this.response === 0) {
						alert("Problem Occured")
						} else {
              
							location.reload();
							// var rData = JSON.parse(this.responseText);

							// console.log(this.response);
							console.log(this.responseText);
						}
					}
				};
        serverCall.send(JSON.stringify(data));
    console.log('Hussein I am trying to submit');

          
  }
  function Delete(e) {
    // localStorage.setItem("BookingId",e.target.id);
    var k = e.target.id;
    var data = {
      lectureId:k,
     
    }
    var serverCall = new XMLHttpRequest();
				serverCall.open('POST', './Backend/api/Delete.php', true);

				serverCall.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						if (this.response === 0) {
						alert("Problem Occured")
						} else {
              
							location.reload();
							// var rData = JSON.parse(this.responseText);

							// console.log(this.response);
							console.log(this.responseText);
						}
					}
				};
        serverCall.send(JSON.stringify(data));
    console.log('Hussein I am trying to submit');

          
  }
</script>