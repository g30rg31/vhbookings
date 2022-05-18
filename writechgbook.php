 <?php

 /*********************************************************************************************************************
 ***  writechgbook.php                                                                                                 ***
 ***  part of VH Management system                                                                                 ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***

 ***  George Thompson                                                                                              ***
 *********************************************************************************************************************/
// receive input from name field and return  table of matching entries in database

// get the q parameter from URL
$id = $_POST["id"];

//$i=0;

// lookup all hints from table if $q is different from ""
//if ($id !== "") {

    require '/home/web/scred.php';
// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
    if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	    } // if conn

$ave =  $_POST["av"] == "true" ? 1 : 0;
$cle =  $_POST["cl"] == "true" ? 1 : 0;
$cpu =  $_POST["cp"] == "true" ? 1 : 0;
$bpu =  $_POST["bp"] == "true" ? 1 : 0;
$sec =  $_POST["se"] == "true" ? 1 : 0;

// echo $_POST["ns"] . " " . $_POST["ne"];



                $evStart = date_create_from_format("Y-m-d H:i:s", $_POST["ns"]);
                $evEnd = date_create_from_format("Y-m-d H:i:s", $_POST["ne"]);
                $hours = date_diff($evEnd, $evStart);
                $duration = $hours->format("%h") + $hours->format("%i")/60;

// if its a child we need to get parent status

/*		if ($_POST["bt"] == "child" ) {
			$sqlc = 'SELECT id,status FROM bookings WHERE recparent="' . $_POST["id"];
			$resultc = mysqli_query($conn,$sqlc);
			$rowc = mysqli_fetch_assoc($resultc);
*/			

//get parent status
		$sqlc = 'SELECT id FROM chgbookings WHERE id=' . $_POST["id"] . ' AND status="Requested"';
		$chgresult = mysqli_query($conn, $sqlc);
		$chgstatus = mysqli_num_rows($chgresult);

		if ($chgstatus) {
			echo "Pending change already in progress. Please contact the booking adminstrator";
			exit; 
		}

		$sqlq = 'SELECT status FROM bookings WHERE id=' . $_POST["id"];
		$staresult = mysqli_query($conn, $sqlq);
		$status = mysqli_fetch_assoc($staresult);
// if its confimred, log it for admin decision
		if ($status["status"] == "Confirmed" ) {
			$table="chgbookings";
			$sqln = 'INSERT INTO ' .  $table . ' (id, bookingname, customer, startdate,enddate,rooms) VALUES ("'
				. $_POST["id"]        . '", "'
                                . $_POST["bs"]        . '", "'
                                . $_POST["cs"]        . '", "'
                                . $_POST["ns"]        . '", "'
                                . $_POST["ne"]        . '", "'
                                . $_POST["ro"]        . '")';
//	echo $sqln;
			$resultn = mysqli_query($conn,$sqln);
		} else {
			$table = "bookings";
		}

		$sql = 'UPDATE ' . $table . ' bookings SET

                                bookingname   ="' . $_POST["bs"]    	. '",
                                startdate     ="' . $_POST["ns"]        . '",
                                enddate       ="' . $_POST["ne"]        . '",
                                rooms         ="' . $_POST["ro"]      	. '",
				status        ="' . $_POST["st"]        . '",
                                publiccontact ="' . $cpu	    	. '",
                                public        ="' . $bpu	    	. '",
                                people        ="' . $_POST["pe"]    	. '",
                                music         ="' . $_POST["mu"]       	. '",
                                alcohol       ="' . $_POST["al"]       	. '",
                                avequip       ="' . $ave	     	. '",
                                cleaning      ="' . $cle	    	. '",
                                sec	      ="' . $sec	    	. '",
				hours	      ="' . $duration		. '",
                                price         ="' . $_POST["pr"]     	. '",
                                deposit       ="' . $_POST["de"]     	. '"

                                WHERE ID      ="' . $_POST["id"]     	. '"';
//echo $sql;
		$result = mysqli_query($conn, $sql);

		if ($result) 
		{
			echo "Change to " . $_POST["bs"] . " successfuly saved with id = " . $_POST["id"];
			require("chgreqemail.php");
		}
	mysqli_close($conn);
//}  // id not blank
?> 
