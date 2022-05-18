<?php


// link to mysql
    require('/home/web/scred.php');
// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
// Check connection
    if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
    } // if conn

	$sql = 'SELECT bookingname, customer, startdate, enddate, rooms, price, hours, chargeable FROM bookings';

//INTO OUTFILE '/home/pi/sqlcsv/customers.csv' FIELDS TERMINATED by ',' LINES TERMINATED BY '\\n'";


//SELECT year(startdate) AS year, month(startdate) AS month, sum(price) AS income  FROM bookings WHERE status != "Cancelled" GROUP BY year, month ORDER BY year, month';
 echo $sql;
	$result = mysqli_query($conn, $sql);
var_dump($result);

	$fd = fopen("/home/pi/sqlcsv/bookingfile.csv","w");
	
	$line = 'bookingname, customer, startdate, enddate, rooms, price, hours, chargeable' . PHP_EOL;

	fwrite($fd,$line);

	while( $row = mysqli_fetch_assoc($result) ) {

		$outline = $row["bookingname"]  . ',' .
			$row["customer"]    . ',' .
			$row["startdate"]    . ',' .
			$row["enddate"] . ',' .
			$row["rooms"] . ',' .
			$row["price"] . ',' .
			$row["hours"] . ',' .
			$row["chargeable"] . PHP_EOL;
			
		fwrite($fd,$outline);

	}
	fclose($fd);
	mysqli_close($conn);
?>
