<?php
session_start();

/*********************************************************************************************************************
 ***  login.php                                                                                                 ***
 ***  part of VH Management system                                                                                 ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***  George Thompson                                                                                              ***
 *********************************************************************************************************************/
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Village Hall Management</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>


<?php

if ($_SESSION["loggedon"] =="yes" ) {
    header("Location:calendar.php");
	exit;
	} else {
		// do everything below

		// define variables and set to empty values
		$message = "";
		$retry_limit = 4;
//		echo $_SESSION["tries"];

		if ($_SESSION["tries"] > $retry_limit ) {
		//	echo "testing tries";
			// set cookie with 5 min timeout
			setcookie("timeout", $_SESSION["tries"], time()+ 5*60," /");
			$_SESSION["tries"] = 0;  
			exit;
			} // if retries

		if (!isset($_COOKIE["timeout"])) {
//			echo "cookie not set";
		
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
		//		echo "it is a post";
					if (!filter_var($_POST["inputEmail"], FILTER_VALIDATE_EMAIL)) {
					$message = "Incorrect email or password";
		//			echo " invalid email";
					//          exit();
					}  // if FILTER_VALIDATE_EMAIL
     
				if (empty($_POST["pwd"])) {
					$message = "Incorrect email or password";
		//			echo "password empty";
					//          exit();
					}

				// find user and check password
				require '/home/web/scred.php';
				$conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
					} // if conn
				$sql = 'SELECT * FROM customers WHERE email="' . $_POST["inputEmail"] . '"';
				$result = $conn->query($sql);
				// if there is one user only
				if ($result->num_rows === 1) {
					$row = $result->fetch_assoc() ;
		//			echo "found user";
					// verify password and set session variables
					if (password_verify($_POST["pwd"], $row["password"])) {
						$_SESSION["loggedon"] = "yes";
						$_SESSION["username"] = $row["name"];
						$_SESSION["role"] = $row["role"];
						$_SESSION["id"] = $row["id"];
						$_SESSION["tries"] = 0;
						if ($row["role"] == "admin" ) {
							$cooktime= 14400;
						} else {
							$cooktime = 7200;
						}
						setcookie("Loggedon", $row["name"], time() + $cooktime, "/");
						setcookie("Role", $row["role"], time() + $cooktime, "/");
						header("Location:calendar.php");
						exit();
						// if password not verified
					} else {
						$_SESSION["loggedon"] = "no";
						$message = "Incorrect email or password"; // error message
						echo $_SESSION["tries"]++;
						// echo "incorrect pwd";
					} // if verify

				} //if num_rows
			} // if post

		} 
//		else {//iscookieset
//		header("location:calendar.php");
//		}



	} // if not loggedon

?>


</head>
<body>

<?php include 'head.php'; ?>

<!-- side content here  -->

<div class="container" style="margin-top:30px">
  <div class="row">
    <div class="col-sm-3">
      <h2>Log In</h2>
        <p>The Calendar is public but you will need to Log-In to manage bookings.</p>
	<p>We have migrated to this new booking system, if you had an account on the old booking system, please reset your password to use the new system.
        <p>If you have forgotten your password, click on Password Reset to receive a link to reset your password.</p>
        <p>After 5 unsuccessful attempts you will be locked out for 5 minutes.</p>
      <hr class="d-sm-none">
    </div>

<!-- main content here  -->

    <div class="col-sm-9">
   
	<div class="container">
  	<h2>Log on here</h2>

	<?php echo $message; ?>

	    <form action="login.php" method="POST">
	    <div class="form-group">
	      <label for="usr">Email Address:</label>
	      <input type="text" class="form-control" id="usr" name="inputEmail">

		</div>
	    <div class="form-group">
	      <label for="pwd">Password:</label>
	      <input type="password" class="form-control" id="pwd" name="pwd">

	    </div>
	    <button type="submit" class="btn btn-primary">Log In</button>
	</form>
		To register as a user, click on 
	<button type="button" class="btn btn-default">  <a href="register.php">Register </a> </button><br>
		Forgotten Password? click on
	<button type="button" class="btn btn-default">  <a href="passwordreset.php">Password Reset</a> </button>
	<br><br><br>

         <p>This website is for managing bookings for the East Meon Village Hall.</p>
         <p>Unauthorised use of this website is an offence under the Computer Misuse Act.</p>
         <p>All activity is monitored and recorded.</p>

	</div>
      
  </div>
</div>

<?php include 'foot.html'; ?>

</body>
</html>
