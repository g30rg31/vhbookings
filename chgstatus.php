<?php
session_start();

/******************************************************************************************************************
 ***  chgpasswd.php                                                                                                 ***
 ***  part of emgs scoring system                                                                               ***
 ***                                                                                                            ***
 ***  George Thompson                                                                                              ***
 *********************************************************************************************************************/
?>

<?php

if (!isset($_SESSION["loggedon"])) {
    header("Location:login.php");
	exit;
}
if ($_SERVER["REQUEST_METHOD"] == "GET") {

		require("/home/web/scred.php");
		$conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
// Check connection
		if ($conn->connect_error) {
//			echo "sql connnect errro";
			die("Connection failed: " . $conn->connect_error);
		} // if conn

//		$hash= password_hash($pass1, PASSWORD_DEFAULT);
		$sql = 'UPDATE bookings SET status = "' . $_REQUEST["q"] . '" WHERE id = "' . $_REQUEST["id"] . '"';
		$ret = mysqli_query($conn, $sql);


                echo "Booking has been " . $_REQUEST["q"];

	} // if message set

//	echo $message;
?>
