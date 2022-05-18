<?php

 /*********************************************************************************************************************
 ***  writebooking.php                                                                                                 ***
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
		$sqlq = 'SELECT status FROM bookings WHERE id=' . $_POST["id"];
		$staresult = mysqli_query($conn, $sqlq);
		$status = mysqli_fetch_assoc($staresult);

		if ($status["status"] == "Confirmed" ) {
			$table="chgbookings";
			$sqln = 'INSERT INTO ' .  $table . ' (id, bookingname, customer, startdate,enddate,rooms) VALUES ("'
				. $_POST["id"]        . '", "'
                                . $_POST["bs"]        . '", "'
                                . $_POST["cs"]        . '", "'
                                . 'chgRequest'        . '", "'
                                . $_POST["ns"]        . '", "'
                                . $_POST["ro"]        . '")';

//	echo $sqln;
			$resultn = mysqli_query($conn,$sqln);
		} else {
			$table = "bookings";
		}





//echo $duration;



if ($_POST["bt"] == "parent" ) {
	$sql = 'UPDATE ' . $table . ' bookings SET

                                customer      ="' . $_POST["cs"]	. '",
                                bookingname   ="' . $_POST["bs"]    	. '",
                                status        ="' . $_POST["st"]       	. '",
                                startdate     ="' . $_POST["ns"]        . '",
                                enddate       ="' . $_POST["ne"]        . '",
                                rooms         ="' . $_POST["ro"]      	. '",
                                recurring     ="' . $_POST["rc"]       	. '",
                                frequency     ="' . $_POST["re"]     	. '",
                                recparent     ="' . $_POST["rp"]      	. '",
                                recsibling    ="' . $_POST["rc"]       	. '",
                                numrecs       ="' . $_POST["rc"]      	. '",
                                publiccontact ="' . $cpu	    	. '",
                                public        ="' . $bpu	    	. '",
                                occurence     ="' . $_POST["samedate"]  . '",
                                people        ="' . $_POST["pe"]    	. '",
                                music         ="' . $_POST["mu"]       	. '",
                                alcohol       ="' . $_POST["al"]       	. '",
                                sec           ="' . $sec	       	. '",
                                avequip       ="' . $ave	     	. '",
                                cleaning      ="' . $cle	    	. '",
				hours	      ="' . $duration		. '",
                                price         ="' . $_POST["pr"]     	. '",
                                deposit       ="' . $_POST["de"]     	. '"

                                WHERE ID      ="' . $_POST["id"]     	. '"';

} else {

			 $sql = 'INSERT INTO ' . $table . ' ( id, customer, bookingname, status, startdate, enddate, rooms, frequency, recparent,
				numrecs, publiccontact, public, people, hours, alcohol, sec, music, avequip, cleaning, price, deposit )

			VALUES ("'
				. $_POST["id"]        . '", "'
                                . $_POST["cs"]        . '", "'
                                . $_POST["bs"]        . '", "'
                                . $_POST["st"]        . '", "'
                                . $_POST["ns"]        . '", "'
                                . $_POST["ne"]        . '", "'
                                . $_POST["ro"]        . '", "'
                                . $_POST["re"]        . '", "'
                                . $_POST["rp"]        . '", "'
                                . $_POST["rc"]        . '", "'
                                . $cpu        		. '", "'
                                . $bpu       		 . '", "'
                                . $_POST["pe"]        . '", "'
				. $duration	      . '", "'
                                . $_POST["al"]        . '", "'
                                . $sec		      . '", "'
                                . $_POST["mu"]        . '", "'
                                . $ave 		        . '", "'
                                . $cle		        . '", "'
                                . $_POST["pr"]        . '", "'
                                . $_POST["de"]        . '")';
	}  // else

//	echo $sql;
        $result = mysqli_query($conn,  $sql);
	if ($result) echo $_POST["bs"] . " event successfuly saved with id = " . $id;

// now write booking description and booking notes

	if ($_POST["bt"] == "parent" ) {

		require('confreqemail.php');

		if($_POST["bd"] != "")
		{
			// does booking descrition exists
			$sql = 'SELECT notes FROM bookingdescr where bookingname = "' . $_POST["bs"] . '"';
			$result=mysqli_query($conn,$sql);
			if ( mysqli_num_rows($result))
			{
				$sql = 'UPDATE bookingdescr SET notes="' . $_POST["bn"] .  '" WHERE bookingname = "' . $_POST["bs"] . '"';
			} else {
				$sql = 'INSERT INTO bookingdescr VALUES (' . $_POST["id"] . ', "' . $_POST["bs"] . '","' . $_POST["bd"] . '")';
			}
			$result = mysqli_query($conn,  $sql);
//			echo $sql;
		}
		if($_POST["bn"] != "")
		{
                        $sql = 'INSERT INTO bookingnotes VALUES (' . $_POST["id"] . ',"' . $_POST["bn"] . '")';
			$result = mysqli_query($conn,  $sql);
//                      echo $sql;
                }
	}






mysqli_close($conn);
//}  // id not blank
?> 
