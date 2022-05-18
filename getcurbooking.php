 <?php

 /*********************************************************************************************************************
 ***  getbooks.php                                                                                                 ***
 ***  part of VH Management system                                                                                 ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***  George Thompson                                                                                              ***
 *********************************************************************************************************************/
// receive input from name field and return  table of matching entries in database

$today = date("Y:m:d"); 
$time = date("H:i:s");
    require '/home/web/scred.php';
// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
    if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	    } // if conn

    $sql =  'SELECT bookingname, customer, startdate, enddate FROM bookings WHERE enddate > "' . $today . ' ' . $time . '" AND status = "Confirmed" ORDER BY startdate LIMIT 2';
//echo $sql;
    $result = mysqli_query($conn, $sql);
    $numrows = mysqli_num_rows($result);
$i=0;
	$response ='{"data":[';

	while ($row = mysqli_fetch_assoc($result)) {
		
		$response = $response . json_encode($row) ;
//		if ($row["enddate"] > $today . " " . $time)  {
//			$response = $response . '[curbook: "' . $row["bookingname"] . '",curcust: "' . $row["customer"] . '",  from: "' . $row["startdate"] .'", to: "' . $row["enddate"] . '"]'; 
//			echo $response;
//		}
		if ($i == 0) $response = $response . ',' ;
		$i++;
	}
//	$response = $response . ']}' ;
	echo $response . ']}';
exit();

// build response table
// table CSS needs work
   echo "<table border='1px solid black' border-spacing='100px' padding='30px'>";
//   echo '<td><button type="button" class="btn btn-default" onclick="selbook(this.value)" id="booknew">Add new booking</button>';

 while($row = mysqli_fetch_array($result))
    {
    $i++;
    echo "<tr>";
//    echo "<td>" . $row['id'] . "</td>";
    echo '<td><button type="button" class="btn btn-default" onclick="selbook(this.value)" id="book' . $i . '" value="' . $row["bookingname"] . '">' . $row["bookingname"] . '</button>';
    echo "</td></tr>";
    }
    echo "</table>";

    mysqli_close($mysqli);

?> 
