<?php
// send polite reply

require_once "/home/emvhmgr/vendor/autoload.php";
use Twilio\TwiML\MessagingResponse;

// Set the content-type to XML to send back TwiML from the PHP Helper Library
header("content-type: text/xml");

$response = new MessagingResponse();
$response->message(
		    "Thank you for your message, we will respond shortly"
		  );
echo $response;



 $_POST["From"] == "" ? $phonefrom = "+447802155857" : $phonefrom = $_POST["From"];

 require ('/home/web/scred.php');

// Create connection
	$conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } // if conn
//  get customer details from db
		$sql = 'SELECT name, email FROM customers WHERE phone = "' . $phonefrom . '"';
//                echo $sql;
                $result = mysqli_query($conn, $sql);
                $rowc = mysqli_fetch_assoc($result);

// write txt to db
                $sql = 'INSERT INTO messages (fromphone, tophone, name, email, textmsg) VALUES ("' . $_POST["From"] . '","' . $_POST["To"] . '","' . $rowc["name"] . '","' . $rowc["email"] . '","' . $_POST["Body"] . '")';
                $result = mysqli_query($conn, $sql);
                mysqli_close($conn);


?>
