<?php

include_once './Backend/config/Database.php';
$database = new Database();
$conn = $database->connect();





// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "OfficeHours";

// $conn = new mysqli($servername, $username , $password,$dbname);

// if ($conn->connect_error){
// 	die("connection fail: " . $conn->connect_error);
// }
// $error="";
if(isset($_POST['Login']))
{
	$error ="";
	$user = $_POST['username'];
	$pass = $_POST['password'];
	$faculty = isset($_POST["faculty"]);

	if($faculty == '1'){
	
		// echo 'Login in '.$data['FacultyID'];
		
		// echo '<script language="javascript">
		// alert("You have entered '.$faculty. ' I am here")
		// </script>';
		$query = "select * from Faculty where AshesiEmail = :user AND PasswordC = :pass";
		// $result = $conn->query("select * from Faculty where AshesiEmail = '$user' AND PasswordC = '$pass'");
		$stmt = $conn->prepare($query);
        $stmt->bindParam(':user', $user);
		$stmt->bindParam(':pass', $pass);
       	$stmt->execute();
		if($stmt->rowCount() > 0)
		{
			session_start();
			$data = $stmt->fetch(PDO::FETCH_ASSOC);
			$_SESSION['FacultyID'] = $data['FacultyID'];
			// echo 'Login in '.$data['FacultyID'];
			header('location:lecturer_dashboard.php');
		}
	}
	elseif($faculty == "0"){
		$query = "select * from Students where AshesiEmail = :user AND PasswordC = :pass";
		$stmt = $conn->prepare($query);
        $stmt->bindParam(':user', $user);
		$stmt->bindParam(':pass', $pass);
		$stmt->execute();
		
		
		// $result = $conn->query("select * from Students where AshesiEmail = '$user' AND PasswordC = '$pass'");
		if($stmt->rowCount() > 0)
		{
			session_start();
			$data = $stmt->fetch(PDO::FETCH_ASSOC);
			$_SESSION['StudentID'] = $data['StudentID'];
			// echo 'Login in '.$data['StudentID'];
			header('location:dashboard.php');
		}
	}
	
   else{
		echo '<script language="javascript">
		alert("You have entered the wrong credentials. Please try again")
		</script>';
   }
}





?>






<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link
			href="https://fonts.googleapis.com/css2?family=Roboto&display=swap"
			rel="stylesheet"
		/>
		<link
			rel="stylesheet"
			href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
		/>
		<link rel="stylesheet" href="css/styles.css" />
		<title>Login - Homepage</title>
	</head>
	<body>
		<div class="bkg-opaque"></div>
		<header>
			<div class="logo-container">
				<img src="./images/my-logo.png" alt="my app logo" />
				<h3>Team Oasis</h3>
			</div>
			<ul>
				<li><a href="arrival.html">Staff Arrival</a></li>
			</ul>
		</header>

		<div class="login-container">
			<p class="login-welcome">
				Welcome to Oasis Office Hour Reservation
				<!--I lost the paper where I wrote the name (lol)-->
			</p>

			<form method="POST" class="login-form">
				<div class="login-input username-in">
					<input type="text" name="username" placeholder="Username" />
				</div>
				<div class="login-input password-in">
					<input type="password" name="password" placeholder="Password" />
				</div>
				<div class="additional-act">
					<label class="container-ckbx">
						<input type="checkbox" checked name="faculty"/>
						<span class="checkmark"></span>
						Are you a Faculty?
					</label>

					
				</div>
				<button type = "submit" name="Login" class="submit-btn btn btn-theme">Login</button>

				<!-- <div class="additional-act">
					<label class="container-ckbx">
						<input type="checkbox" checked />
						<span class="checkmark"></span>
						Are you a Faculty?
					</label>

					<p>Forgot password?</p>
				</div> -->

				<div class="additional-act new-actions">
					<p>Create account</p>
					<p>Need help?</p>
				</div>
			</form>
		</div>
	</body>
</html>
