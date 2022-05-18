<?php
session_start();

/******************************************************************************************************************
 ***  chgpasswd.php                                                                                                 ***
 ***  part of emgs scoring system                                                                               ***
 ***                                                                                                            ***
 ***  George Thompson                                                                                              ***
 *********************************************************************************************************************/
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>EMGS Scoring</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<?php

if (!isset($_SESSION["loggedon"])) {
    header("Location:login.php");
	exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$message = "";

//		if (strlen($_POST["pass1"])<8 ) {
//			$message= "Password must be a minimum of 8 characters";
//		}
		if ( $_POST['pass1'] != $_POST['pass2']  ) {
			$errmessage = "Passwords do not match";
//			echo "<br>cmp result=" .  strcmp($_POST["pass1"], $POST["pass2"] );
		}
        	$pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,15}$/';
	        if(!preg_match($pattern, $_POST["pass1"])){
                	$errmessage = "Password is not strong enough";
	        }

	if ( $errmessage == "" ) {
		require("/home/web/scred.php");
		$conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
// Check connection
		if ($conn->connect_error) {
//			echo "sql connnect errro";
			die("Connection failed: " . $conn->connect_error);
		} // if conn

		$hash= password_hash($pass1, PASSWORD_DEFAULT);
		$sql = 'UPDATE customers SET password = "' . $hash . '" WHERE name = "' . $_SESSION["name"] . '"';
		$ret = mysqli_query($conn, $sql);


                echo '<script>';
                echo 'alert("Password successfully updated.");';
                echo 'location.href="calendar.php";';
                echo '</script>';

	} // if message set

//	echo $message;
} /// if post
?>
</head>
<body>
<?php include 'head.php'; ?>

    <div class="container" style="margin-top:30px">
    <div class="col-sm">
    <div class="container">
  	<p>Passwords must be at least 8 characters long and contain at least one uppercase letter, one lower case letter and one number.</p>
	<?php echo $message; ?>
	    <form action="chgpasswd.php" method="POST">
	    <div class="form-group">
	      <label for="usr">Enter New Password:</label>
	      <input type="password" class="form-control" id="pass1" name="pass1">

            </div>
	    <div class="form-group">
	      <label for="pwd">Confirm New Password:</label>
	      <input type="password" class="form-control" id="pass2" name="pass2">
	    </div>
	    <button type="submit" class="btn btn-primary">Change Password</button>
            </form>

    </div>
    </div>
    </div>
</body>
</html>
