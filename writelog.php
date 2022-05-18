<?php

    require('/home/web/scred.php');

// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
    if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
    } // if conn


	        $sql ='INSERT INTO logs (type, logentry) VALUES ("' . $_REQUEST["t"] . '","Command from website ' . $_REQUEST["q"] . ' by user ' . $_COOKIE["Loggedon"] . '")';
// echo $sql;
	        $result = mysqli_query($conn, $sql);



	mysqli_close($conn);
?>
