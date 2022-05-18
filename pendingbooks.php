<?php
session_start();
?>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 2px;
  text-align: center;
  vertical-align:text-top;
}
pageheading {
  font-size: 60px;
  font-weight: bold;
}
.tdcentre {
 text-align: center;
}
</style>

<?php
if (true) {
//if ($_COOKIE["Role"] == "admin" ) {

	require('/home/web/scred.php');
	$conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
        	if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                } // if conn

        $sql = 'SELECT * FROM bookings WHERE status="Requested" AND recparent=0 ORDER BY startdate';
        $result = $conn->query($sql);



	echo "<br><h4>Pending Bookings requiring confirmation</h4>";
	echo "<br><table><tr>
		<th>Name</th>
		<th>Booking</th>
		<th>Start Time</th>
		<th>End Time</th>
		<th>Room</th>
		<th>Notes</th>
		<th>Confirm</th>
		</tr>
		";

	while($row = mysqli_fetch_assoc($result)) {
		echo "<tr>";
			echo "<td>" . $row["customer"] . "</td>";
                        echo "<td>" . $row["bookingname"] . "</td>";
                        echo "<td>" . $row["startdate"] . "<br>";

			$sql =  'SELECT * FROM bookings WHERE status="Requested" AND recparent=' . $row["id"] .' ORDER BY startdate';
		        $resultc = mysqli_query($conn,$sql);

			while($rowc = mysqli_fetch_assoc($resultc)) {
				echo $rowc["startdate"] . "<br>";
			}
			echo "</td><td>" . $row["enddate"] . "</br>";

                        $sql =  'SELECT * FROM bookings WHERE status="Requested" AND recparent=' . $row["id"] .' ORDER BY startdate';
                        $resultd = mysqli_query($conn,$sql);

                        while($rowd = mysqli_fetch_assoc($resultd)) {
                                echo $rowd["enddate"] . "<br>";
			}
			$sql =  'SELECT notes FROM bookingnotes WHERE parentid=' . $row["id"];
		        $resultn = mysqli_query($conn,$sql);
			$rown =mysqli_fetch_assoc($resultn);
                        echo "</td><td>" . $row["rooms"] . "</td>";
                        echo "<td>" . $rown["notes"] . "</td>";
			echo '<td>' . 
				'<button type="button" class="btn btn-link" onclick="accept(this.value)" value="' . $row["id"] . '">Yes</button>' . ' ' . " / " .
                 		'<button type="button" class="btn btn-link" onclick="reject(this.value)" value="' . $row["id"] . '">No</button></td>';
		echo "</tr>";
                 }
	echo "</table>";
	$from = "displace";
	$to = "regform";
        echo '<br><button type="button" class="btn btn-primary" id="fromdisplace" onclick = "goback(this.id)" value="displace">Close</button>';

        mysqli_close($conn);


        } else { //if q !=""
                  echo  "No new customer registrations";
//        }

} // if cookie role

//  echo '<br><button type="button" class="btn btn-primary" onclick = "gotodiv(\"displace\", \"regform\")" value="gotocal">Calendar</button>';
?>

