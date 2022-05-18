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
//if (true) {
if ($_COOKIE["Role"] == "admin" ) {

	require('/home/web/scred.php');
	$conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
        	if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                } // if conn

        $sql = 'SELECT * FROM chgbookings WHERE status="Requested" OR status="Cancel" ORDER BY tandcs';
        $resultd = $conn->query($sql);
//	$rowd = mysqli_fetch_assoc($resultd);
//var_dump($rowd);
$columnAr = "";

$columnDesc = array("id"=>"",
"bookingname"=>"Booking",
"customer"=>"Name",
"groupname"=>"Business",
"startdate"=>"Start Date and Time",
"enddate"=>"End Date and Time",
"rooms"=>"Hall",
"status"=>"",
"invoiced"=>"",
"recurring"=>"",
"frequency"=>"",
"recparent"=>"",
"recsibling"=>"",
"numrecs"=>"",
"publiccontact"=>"Contact Details Public",
"public"=>"Booking Public",
"occurence"=>"",
"people"=>"Attendees",
"music"=>"Music License",
"alcohol"=>"Alcohol License",
"avequip"=>"AV Equipment",
"cleaning"=>"Cleaning Required",
"tandcs"=>"",
"price"=>"",
"deposit"=>"",
"hours"=>"",
"pincode"=>"",
"chargeable"=>"",
"setout"=>"Set out Tables and Chairs",
"sec"=>"Door Locking" );

//var_dump($keysd);
	echo "<br><h4>Pending Changes to Bookings requiring confirmation</h4>";
	echo '<br><table><tr>
		<tr><td colspan="7">Confirmed Booking Details</td></tr>
		<th>Name</th>
		<th>Booking</th>
		<th>Date</th>
		<th>Start Time</th>
		<th>End Time</th>
		<th>Room</th>
		<th>Invoiced</th>
		<th>Changes Requested</th>
		<th>Confirm</th>
		</tr>
		';
//echo mysqli_num_rows($resultd);
	while($rowd = mysqli_fetch_assoc($resultd)) {
		$keysd = array_keys($rowd);

//var_dump($keysd);
		echo "<tr>";


	        $sql = 'SELECT * FROM bookings WHERE id = ' . $rowd["id"];
//echo $sql;

        	$resultf = $conn->query($sql);
	        $rowf = mysqli_fetch_assoc($resultf);

		foreach ($keysd as $bookchgentry) {
			$columnAr = $columnAr . '","' .$bookchgentry . '<br>'; 
		}
//			if (($rowd[$bookchgentry] != $rowf[$bookchgentry]) && ($rowd[$bookchgentry] != NULL )) {
//				echo "change " . $bookchgentry . " from " . $rowf[$bookchgentry] . " to " . $rowd[$bookchgentry] . "<br>";
//			}


			echo "<td>" . $rowf["customer"] . "</td>";
                        echo "<td>" . $rowf["bookingname"] . "</td>";
			echo "<td>" . substr($rowf["startdate"],8,2) . "/" . substr($rowf["startdate"],5,2) . "/" . substr($rowf["startdate"],0,4);
                        echo "<td>" . substr($rowf["startdate"],11,5) . "</td>";
			echo "<td>" . substr($rowf["enddate"],11,5) . "</td>";
                        echo "<td>" . $rowf["rooms"] . "</td>";
			if ( $rowf["invoiced"] != NULL ) echo "<td>Y</td> "; else echo "<td>N</td><td>";
//                        echo "<td>" . $rowf["invoiced"] . "</td><td>";

			if ($rowd["status"] == "Cancel" ) {
				echo "Cancel Booking";
			} else {

			foreach ($keysd as $bookchgentry) {
				if (($rowd[$bookchgentry] != $rowf[$bookchgentry]) && ($rowd[$bookchgentry] != NULL )) {

					if ( $columnDesc[$bookchgentry] != "" ) {
//echo $columnDesc[$bookchgentry] . PHP_EOL;
//echo $rowd[$bookchgentry] . PHP_EOL;
						$outputDesc=$columnDesc[$bookchgentry];
						$chgoutput = $rowd[$bookchgentry];
						if ($bookchgentry == "startdate" || $bookchgentry == "enddate" ) {
						    if ($bookchgentry == "startdate" ) {
							if (substr($rowd["startdate"],0,10) != substr($rowf["startdate"],0,10)) {
								$outputDesc = "Start Date";
								$chgoutput = substr($rowd["startdate"],8,2) . "/" . substr($rowd["startdate"],5,2) . "/" . substr($rowd["startdate"],0,4);
								echo $outputDesc  . ": " . $chgoutput . "<br>";
							}

                                                        if (substr($rowd["startdate"],11,5) != substr($rowf["startdate"],11,5)) {
                                                                $outputDesc = "Start Time";
                                                                $chgoutput = substr($rowd[$bookchgentry],11,5);
								echo  $outputDesc  . ": " . $chgoutput . "<br>";
                                                        }

						    }
						    if ($bookchgentry == "enddate" ) {
                                                        if (substr($rowd["enddate"],0,10) != substr($rowf["enddate"],0,10)) {
                                                                $outputDesc = "End Date";
                                                                $chgoutput = substr($rowd["enddate"],8,2) . "/" . substr($rowd["enddate"],5,2) . "/" . substr($rowd["enddate"],0,4);
								echo  $outputDesc  . ": " . $chgoutput . "<br>";
                                                        }
                                                        if (substr($rowd["enddate"],11,5) != substr($rowf["enddate"],11,5)) {
                                                                $outputDesc = "End Time";
                                                                $chgoutput = substr($rowd[$bookchgentry],11,5);
								echo  $outputDesc  . ": " . $chgoutput . "<br>";
                                                        }
						    }
						} else {	
							if ( $chgoutput == "1" ) $chgoutput = "Yes";
							if ( $chgoutput == "0" ) $chgoutput = "No";

							if ( ($bookchgentry == "alcohol") && ($rowd[$bookchgentry] == "3") ) $chgoutput = "Yes";
							if ( $bookchgentry == "music" && $rowd[$bookchgentry] == "2" ) $chgoutput = "Yes";
							if ( $bookchgentry == "people" && ($rowd[$bookchgentry]  > $rowf[$bookchgentry]) ) $chgoutput ="More"; 
							if ( $bookchgentry == "people" && ($rowd[$bookchgentry]  < $rowf[$bookchgentry]) ) $chgoutput = "Less";

							echo $outputDesc  . ": " . $chgoutput . "<br>";
						}
//						echo $columnDesc[ $bookchgentry]  . ": " . $rowd[$bookchgentry] . "<br>";
					}
				}
			}
		}
//                        echo "<td>" . $row["role"] . "</td>";
			echo '</td><td>' . 
				'<button type="button" class="btn btn-link" onclick="acceptchg(this.value)" value="' . $rowf["id"] . '">Yes</button>' . ' ' . " / " .
                 		'<button type="button" class="btn btn-link" onclick="rejectchg(this.value)" value="' . $rowf["id"] . '">No</button></td>';
		echo "</tr>"; 
                 }
	echo "</table>";
	$from = "displace";
	$to = "regform";
        echo '<br><button type="button" class="btn btn-primary" id="fromdisplace" onclick = "goback(this.id)" value="displace">Close</button>';

        mysqli_close($conn);
//echo $columnAr;

//        } else { //if q !=""
  //                echo  "No new customer change requests";
//        }

} // if cookie role

//  echo '<br><button type="button" class="btn btn-primary" onclick = "gotodiv(\"displace\", \"regform\")" value="gotocal">Calendar</button>';
?>

