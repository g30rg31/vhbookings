<?php
$monthAr = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
$valuesAr = ["","","","","","","","","","","",""];
// link to mysql
    require('/home/web/scred.php');
// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
// Check connection
    if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
    } // if conn

	$sql = 'SELECT year(startdate) AS year, month(startdate) AS month, sum(price) AS income  FROM bookings WHERE status = "Confirmed" OR status = "Requested" AND startdate > "2019-08-31 00:00:00" GROUP BY year, month ORDER BY year, month';
//echo $sql;
	$result = mysqli_query($conn, $sql);
$i=0;
$j=0;
$cummval = 0;
		$thisYear = date_create();
		$year = date_format($thisYear, "Y");
		$lastyear = $year-1;

	$myObj->action ="showbookingvalue1";
//	$myObj->$year = array(0,0,0,0,0,0,0,0,0,0,0,0);
//	$myObj->$lastyear = array(0,0,0,0,0,0,0,0,0,0,0,0);
	$numrows = mysqli_num_rows($result);
//echo $numrows . PHP_EOL;
	for ($i=0 ; $i < $numrows; $i++) {
		$row = mysqli_fetch_assoc($result);
		$year = $row["year"];

//		if (  $row["month"]  > 8 ) { 
//echo $row["month"];
			$chmon= $row["month"]-1 ;
			$chmonth = $monthAr[$chmon];
//			$year--;
//			$valuesAr[$chmonth] = $row["income"];
			$myObj->$year->$chmonth = $row["income"];
//			$cummval = $cummval + $row["income"];
//echo $chmonth;
//			}
/*		if (  $row["month"] < 9 ) {
			$chmon = $row["month"] + 3;
			$chmonth = $monthAr[$chmon];
//			$valuesAr[$chmonth] = $row["income"];
			$myObj->$year = $row["income"];
			$cummval = $cummval + $row["income"];
			}  */
//		$j++;
//echo $i . PHP_EOL;}
//			if ($i  != $numrows-1 ) {
//				$responseAr= $responseAr . ',';
//			}
//		}
//	$myObj->data[0]->total = $cummval;


/*
                if (  ($row["year"] == $lastyear  && $row["month"]  > 8) ) {
//echo $row["month"];
                        $chmon= $row["month"] - 9;
                        $chmonth = $monthAr[$chmon];
//                      $valuesAr[$chmonth] = $row["income"];
                        $myObj->$lastyear[$chmon] = $row["income"];
                        $cummval = $cummval + $row["income"];
//echo $chmonth;
                        }
                if ( ($row["year"] == $lastyear && $row["month"] < 9) ) {
                        $chmon = $row["month"] + 3;
                        $chmonth = $monthAr[$chmon];
//                      $valuesAr[$chmonth] = $row["income"];
                        $myObj->$lastyear[$chmon] = $row["income"];
                        $cummval = $cummval + $row["income"];
                        }
                $j++;

                } */
//        $myObj->data[0]->total = $cummval;


	}
//	$responseAr = $responseAr . '}';
//var_dump($myObj);

echo json_encode($myObj);
//	} //while
?>
