<?php

    require('/home/web/scred.php');

// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    } // if conn

// set status to confirmed
        $conf_event = $_REQUEST["q"];
	$sql = 'SELECT bookingname, customer, hours, rooms FROM bookings WHERE id=' . $conf_event;
	$result = mysqli_query($conn, $sql);
	$detail = mysqli_fetch_assoc($result);

	$sql = 'SELECT name, email, token FROM customers WHERE name = "' . $detail["customer"] . '"';
	$result = mysqli_query($conn, $sql);
	$hastoken = mysqli_fetch_assoc($result);

// generate pincode for booking
	$pinCode = ""; 		//make sure pincode is empty

	do {
	$pincode = genpinCode();
	$sql = 'SELECT pincode FROM bookings WHERE pincode ="' . $pincode . '"';
	$result = mysqli_query($conn,$sql);
	$notdone = mysqli_num_rows($result);	//keeping generating pincodes until a unique one is found
	} while ($notdone); //do 

//write the database

	if ($detail["rooms"] == "Small Hall" ) $cost = 6.00;
	if ($detail["rooms"] == "Large Hall" ) $cost = 12.00;
	if ($detail["rooms"] == "Both Halls" ) $cost = 18.00;

        $sql ='UPDATE bookings SET status ="Confirmed", pincode="' . $pincode . '", price=' . $cost * $detail["hours"] . '  WHERE id=' . $conf_event . ' OR recparent=' . $conf_event;
        $result = mysqli_query($conn, $sql);

//        $sqlc ='UPDATE customers SET status ="Confirmed", pincode="' . $pincode . '" WHERE name="' . $detail["customer"] . '"';
//        $result = mysqli_query($conn, $sqlc);
/*
	if ($result)
	{
		require('confbookemail.php');
//		echo "sending email";
	}
*/
	echo '{"pin":"' . $pincode . '","booking":"' . $detail["bookingname"] . '","customer":"' . $detail["customer"] . '","email":"' . $hastoken["email"] . '","token":"' . $hastoken["token"] . '"}';

function genpinCode() {

        $pinCode = "";
        for ($i=0; $i<6; $i++) {        // 6 digit pins
                $pinChar = rand(0,9);
                $pinCode = $pinCode . $pinChar;
                }
	return($pinCode);
}
?>
