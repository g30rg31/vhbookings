<?php
$monthAr = ["Sep","Oct","Nov","Dec","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug"];
$valuesAr = ["","","","","","","","","","","",""];
// link to mysql
    require('/home/web/scred.php');
// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
// Check connection
    if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
    } // if conn

	$sql = 'SELECT year(startdate) AS year, month(startdate) AS month, sum(price) AS income  FROM bookings WHERE status = "Confirmed" OR status = "Requested" GROUP BY year, month ORDER BY year, month';
// echo $sql;
	$result = mysqli_query($conn, $sql);
$i=0;
$j=0;
$cummval = 0;
		$thisYear = date_create();
		$year = date_format($thisYear, "Y");

	$myObj->action ="showbookingvalue";

	$numrows = mysqli_num_rows($result);
//echo $numrows . PHP_EOL;
	for ($i=0 ; $i < $numrows; $i++) {
		$row = mysqli_fetch_assoc($result);

//		echo $year;

		if (  ($row["year"] == $year  && $row["month"]  > 8) ) { 
//echo $row["month"];
			$chmon= $row["month"] - 9;
			$chmonth = $monthAr[$chmon];
//			$valuesAr[$chmonth] = $row["income"];
			$myObj->$chmonth = $row["income"];
			$cummval = $cummval + $row["income"];
//echo $chmonth;
			}
		if ( ($row["year"] == $year+1 && $row["month"] < 9) ) {
			$chmon = $row["month"] + 3;
			$chmonth = $monthAr[$chmon];
//			$valuesAr[$chmonth] = $row["income"];
			$myObj->$chmonth = $row["income"];
			$cummval = $cummval + $row["income"];
			} 
		$j++;
//echo $i . PHP_EOL;}
//			if ($i  != $numrows-1 ) {
//				$responseAr= $responseAr . ',';
//			}
		}
	$myObj->total = $cummval;
//	}
//	$responseAr = $responseAr . '}';
// var_dump($myObj);

echo json_encode($myObj);
//	} //while
?>
