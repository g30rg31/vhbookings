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
	$sql = 'SELECT name, pincode, token, role FROM customers WHERE email="' . $conf_event . '"';
	$result = mysqli_query($conn, $sql);
	$detail = mysqli_fetch_assoc($result);

//write the database
        $sql ='UPDATE customers SET status ="Confirmed", pincode="" WHERE email="' . $conf_event . '"';
//echo $sql;
        $result = mysqli_query($conn, $sql);

	echo '{"pin":"' . $pincode . '","role":"' . $detail["role"] . '","customer":"' . $detail["name"] . '","token":"' . $detail["token"] . '"}';
	mysqli_close($conn);
?>
