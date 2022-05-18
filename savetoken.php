<?php

    require('/home/web/scred.php');

// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
    if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
    } // if conn

	//update booking database if its a booking confirmation
	if ($_REQUEST["bookingref"]) {
		// update the parent
	        $sql ='UPDATE bookings SET deposit=' . $_REQUEST["deposit"] .', chargeable=' . $_REQUEST["chargeable"] . ' WHERE id=' . $_REQUEST["bookingref"];
	        $result = mysqli_query($conn, $sql);
		// update the children
	        $sql ='UPDATE bookings SET deposit=0, chargeable=' . $_REQUEST["chargeable"] . ' WHERE recparent=' . $_REQUEST["bookingref"];
	        $result = mysqli_query($conn, $sql);
	} else {
		if($_REQUEST["role"] =="" ) $chgrole = ""; else $chgrole='role="' . $_REQUEST["role"] . '",';
		//write the database
		        $sql ='UPDATE customers SET ' . $chgrole . 'token="' . $_REQUEST["token"] . '"  WHERE name="' . $_REQUEST["name"] . '"';
		echo $sql;
		        $result = mysqli_query($conn, $sql);
		}

	echo '{"msg":"Confirmation completed for ' . $_REQUEST["name"] . '"}';
?>
