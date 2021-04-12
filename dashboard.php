 <?php 
  include_once './Backend/config/Database.php';
  include_once './Backend/model/student.php';

  $database = new Database();
  $db = $database->connect();
  $student = new Students($db);
  

  session_start();
  if(!isset($_SESSION['StudentID'])){ 
    header('location:index.php');
  }
  else{
    $student->id = $_SESSION['StudentID'];
    $result = $student->getCourses();
    
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
          <h5 class="centered">Student</h5>
          <li class="mt">
            <a href="#">
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
    <section id="main-content" class="container">
      <section class="wrapper site-min-height">
        <div class="row mt" >
          <div class="col-lg-12 col-md-12 col-sm-12">
            <?php 
              while ( $row = $result->fetch(PDO::FETCH_ASSOC)){
            ?>
            <div class="row content-panel">
              <div class="col-md-4 col-sm-12 col-xs-12 profile-text mt mb centered">
                <div class="right-divider"> <!-- hidden-sm hidden-xs // used to hide on small screens-->
                  <h4>OFFICE HOURS</h4> <br>
                  <h6><?php  
                 echo $student->formatTime($row['TimeGMT1'],$row['Day1'])
                  ?></h6>
                  <h6><?php  
                  echo $student->formatTime($row['TimeGMT2'],$row['Day2'])
                  ?></h6>
                  <h4>OFFICE LOCATION</h4> <br>
                  <h6>Room 205E Engineering</h6>
                  <h4>EMAIL</h4> <br>
                  <h6><?php  
                  echo $row['AshesiEmail']
                  ?></h6>
                </div>
              </div>
              <!-- /col-md-4 -->
              <div class="col-md-4 col-sm-12 col-xs-12 profile-text centered">
                <h3><?php  
                echo  $row['Faculty']
                  ?></h3> <br>
                <h6>HOD Oasis University CS Department</h6>
                <p>Drop by for a chat</p>
                <br>
                <p><button onclick='getId(event)' id = <?php echo $row["Id"] ?> class="btn btn-theme" type="button" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"><i class="fa fa-envelope"></i> Book Appointment </button></p>
              </div>
              <!-- /col-md-4 -->
              <div class="col-md-4 col-sm-12 col-xs-12 centered">
                <div class="profile-pic">
                  <p><img src="images/hussein.jpg" class="img-circle"></p>
                </div>
              </div>
              <!-- /col-md-4 -->
            </div>
           <br/>
            <?php } ?>
            <!-- /row -->
          </div>
        </div>

          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">New Booking for Dr Hussein</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form method="POST" id = "formInput">
                    <div class="form-group">
                      <label for="recipient-name" class="col-form-label">Name:</label>
                      <input type="text" class="form-control" id="sender-name">
                    </div>
                    <div class="form-group">
                      <label for="recipient-name" class="col-form-label">Select Time</label>
                     <select class="form-control" name="time">
                       <option value="8:00am" >Tue - 8:00am</option>
                       <option value="9:00am" >Thurs - 9:00am</option>
                       
                     </select>
                    </div>
                    
                    <div class="form-group">
                      <label for="message-text" class="col-form-label">Purpose of booking:</label>
                      <textarea class="form-control" id="message-text" name="text"></textarea>
                    </div>
                    <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-theme"><i class="fa fa-envelope" name = "book" ></i> Send message</button>
                </div>
                  </form>
                </div>
               
              </div>
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
    var message = form['text'].value;
    var data = {
      studentId:60652022,
      lectureId: id,
      msg: message
    }
    var serverCall = new XMLHttpRequest();
				serverCall.open('POST', './Backend/api/booking.php', true);

				serverCall.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						if (this.response === 0) {
						alert("Problem Occured")
						} else {
              alert("The message have been sent");
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
  form.addEventListener('submit',Submit)
  function getId(e) {
    localStorage.setItem("BookingId",e.target.id);
    // console.log(e.target.id);
  }



  

</script>

