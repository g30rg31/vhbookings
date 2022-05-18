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
if ($_COOKIE["Role"] == "admin" ) {

	require('/home/web/scred.php');
	$conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
        	if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                } // if conn

        $sql = 'SELECT * FROM customers WHERE status="Requested"';
        $result = $conn->query($sql);
//        $currow = mysqli_fetch_assoc($resultem);
	echo "<br><h4>Pending Registrations requiring confirmation</h4>";
	echo "<br><table><tr>
		<th>Name</th>
		<th>Email</th>
		<th>Phone</th>
		<th>Address</th>
		<th>Role</th>
		<th>Accept</th>
		</tr>
		";

	while($row = mysqli_fetch_assoc($result)) {
		echo "<tr>";
			echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["phone"] . "</td>";
                        echo "<td>" . $row["address1"] . "<br>";
			echo 		$row["address2"] . "</br>";
			if ($row["address3"] != "") {
			echo 		$row["address3"] . "<br>";
			}
			if ($row["address4"] != "") {
			echo 		$row["address4"] . "<br>";
			}
                        echo 		$row["postcode"] . "</td>";
                        echo "<td>" . $row["role"] . "</td>";
			echo '<td>' . 
				'<button type="button" class="btn btn-link" onclick="accept(this.value)" value="' . $row["email"] . '">Yes</button>' . ' ' . " / " .
                 		'<button type="button" class="btn btn-link" onclick="reject(this.value)" value="' . $row["email"] . '">No</button></td>';

		echo "</tr>";
                 }
	echo "</table>";
	$from = "displace";
	$to = "regform";
        echo '<br><button type="button" class="btn btn-primary" id="fromdisplace" onclick = "goback(this.id)" value="displace">Close</button>';

        mysqli_close($mysqli);


        } else { //if q !=""
                  echo  "No new customer registrations";
//        }










} // if cookie role

//  echo '<br><button type="button" class="btn btn-primary" onclick = "gotodiv(\"displace\", \"regform\")" value="gotocal">Calendar</button>';
?>

