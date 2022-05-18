<?php
session_start();

/*	if ($_SESSION["role"] != "admin") {
		exit("Not authorised");
	} else {*/
		require('/home/web/scred.php');
		require('/home/web/scredt.php');
// Create connection
    		$conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} // if conn
// set up TWILIO
		require_once "/home/emvhmgr/vendor/autoload.php";
		use Twilio\Rest\Client;

// Step 2: get customer details form db

		$dowAr = array("Saturday","Sunday","Monday","Tuesday","Wednesday","Thursday","Friday");


		$sql = 'SELECT customer, bookingname, DAYOFWEEK(startdate) AS dow, DATE_FORMAT(startdate,"%d %M %Y") AS std FROM bookings WHERE pincode = "' . $_REQUEST["pin"] . '"';
//		echo $sql . "<br>";
		$result = mysqli_query($conn, $sql);
		$rowb = mysqli_fetch_assoc($result);


		$sql = 'SELECT phone, email FROM customers WHERE name="' . $rowb["customer"] . '"';
//		echo $sql . "<br>";
		$result = mysqli_query($conn, $sql);
		$rowc = mysqli_fetch_assoc($result);

		if(substr($rowc["phone"], 0,2) != "07" )
                {
                        echo "No mobile phone in customer profile";
                        exit;
                }


		$msgtext = "Dear " . $rowb["customer"] . ', your booking, ' . $rowb["bookingname"] . ', starting on '. $dowAr[$rowb["dow"]] . ' ' . $rowb["std"] . ' has been confirmed. The pincode to access the East Meon Village Hall for this booking is ' . $_REQUEST["pin"] . '#.';

		$tonumber = "+44" . substr($rowc["phone"],1);
//  instantiate a new Twilio Rest Client
		$client = new Client($sid, $token);

		$sms = $client->account->messages->create(

				$tonumber,
				array(
			                'from' => "+447492884633",
			                'body' => $msgtext
            			)
        		);

        // Display a confirmation message on the screen
		echo "Sent message to " .  $rowb["customer"];

		$sql = 'INSERT INTO messages (fromphone, tophone, name, email, textmsg) VALUES ("+447492884633", "' . $rowc["phone"] . '","' . $rowb["customer"] . '","' . $rowc["email"] . '","' . $msgtext . '")';
		$result = mysqli_query($conn, $sql);

		mysqli_close($conn);

//	} // else
?>
