<?php
// receive input from name field and return  table of matching entries in database

// get the parameters from URL


empty($_REQUEST["q"]) ? $q="4" : $q = $_REQUEST["q"];
empty($_REQUEST["f"]) ? $f="Weekly" : $f = $_REQUEST["f"];
empty($_REQUEST["d"]) ? $d="2020-01-22" : $d = $_REQUEST["d"];
empty($_REQUEST["t"]) ? $t="12:30" : $t = $_REQUEST["t"];
empty($_REQUEST["u"]) ? $u="20:30" : $u = $_REQUEST["u"];
empty($_REQUEST["r"]) ? $r="Small Hall" : $r = $_REQUEST["r"];

$busy = "";

$bookingDateTimeStart = new DateTime($d . " " . $t);
$bookingDateTimeEnd = new DateTime($d . " " . $u);

// link to mysql
    require('/home/web/scred.php');
// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
// Check connection
    if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
    } // if conn

                        $sqld = 'SELECT * FROM bookings WHERE startdate LIKE "' . $bookingDateTimeStart->format("Y-m-d%") . '%" AND status <> "Rejected" AND status <> "Cancelled" AND NOT id = ' . $_REQUEST["id"] . ' ORDER BY startdate';
                        $resulte = mysqli_query($conn, $sqld);

//                      $butid="recbut";
                        while ( $rowe = mysqli_fetch_array($resulte)) {
// want to know that there isn't a clash
                                $entryDateTimeStart = new DateTime($rowe["startdate"]);
                                $entryDateTimeEnd = new DateTime($rowe["enddate"]);
                                if ( ($entryDateTimeEnd > $bookingDateTimeStart )
                                      && ($entryDateTimeStart < $bookingDateTimeEnd  ) )
	                                {
					if ( $rowe["rooms"] == $_REQUEST["r"] || $rowe["rooms"] == "Both Halls" || $_REQUEST["r"] == "Both Halls" )
						{
				                $busy = "Booking conflict, please check Calendar";
                	        	        }
				}
			}
			echo $busy;
	mysqli_close($conn);

?>
