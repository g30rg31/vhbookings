<?php
// receive input from name field and return  table of matching entries in database

// get the parameters from URL


empty($_REQUEST["q"]) ? $q="4" : $q = $_REQUEST["q"];
empty($_REQUEST["f"]) ? $f="Weekly" : $f = $_REQUEST["f"];
empty($_REQUEST["d"]) ? $d="2020-02-23" : $d = $_REQUEST["d"];
empty($_REQUEST["t"]) ? $t="12:30" : $t = $_REQUEST["t"];
empty($_REQUEST["u"]) ? $u="14:30" : $u = $_REQUEST["u"];
empty($_REQUEST["r"]) ? $r="Large Hall" : $r = $_REQUEST["r"];

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
                        $sqld = 'SELECT * FROM bookings WHERE startdate LIKE "' . $bookingDateTimeStart->format("Y-m-d%") . '%" AND status <> "Cancelled" ORDER BY startdate';

                        $resulte = mysqli_query($conn, $sqld);

//                      $butid="recbut";
                        while ( $rowe = mysqli_fetch_array($resulte)) {
				echo substr($rowe["startdate"],11,5) . "-" . substr($rowe["enddate"],11,5) . " " . $rowe["rooms"] . '<br>';
			}
    mysqli_close($conn);
?>
