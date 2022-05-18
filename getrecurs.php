<?php
// receive input from name field and return  table of matching entries in database

// get the parameters from URL


empty($_REQUEST["q"]) ? $q="3" : $q = $_REQUEST["q"];
empty($_REQUEST["f"]) ? $f="Weekly" : $f = $_REQUEST["f"];
empty($_REQUEST["d"]) ? $d="2020-12-31" : $d = $_REQUEST["d"];
empty($_REQUEST["e"]) ? $e="2010-12-31" : $e = $_REQUEST["e"];
empty($_REQUEST["t"]) ? $t="17:30" : $t = $_REQUEST["t"];
empty($_REQUEST["u"]) ? $u="18:35" : $u = $_REQUEST["u"];
empty($_REQUEST["r"]) ? $r="Large Hall" : $r = $_REQUEST["r"];
empty($_REQUEST["mt"]) ? $mt="stdweek" : $mt = $_REQUEST["mt"];
empty($_REQUEST["o"]) ? $o="third" : $o = $_REQUEST["o"];
empty($_REQUEST["w"]) ? $w="Sunday" : $w = $_REQUEST["w"];

  $busy = "";
  if ( $f == "Daily" )$intval = "1D";
  if ( $f == "Weekly" ) $intval = "7D";
  if ( $f == "Monthly" ) $intval  = "1M";
  $butid= "recbut";

  $bookingDateTimeStart = new DateTime($d . " " . $t);
  $bookingDateTimeEnd = new DateTime($e . " " . $u);
  $bookingStartOriginal =  new DateTime($d . " " . $t);
  $bookingEndOriginal =  new DateTime($e . " " . $u);
  $multi = 0;

  if (  $d != $e ) {
// multiday booking
//	$diff = date_diff( date_create($d), date_create($e) );
//	$q =  $diff->format("%a") +1;
	$intval = "1D";
	$multi = 1;
//	echo "Multi" . PHP_EOL;
// want to start on the parent date, not the recurring date
	$bookingDateTimeStart->sub(new DateInterval("P1D"));
        $bookingDateTimeEnd->sub(new DateInterval("P1D"));
  }  

// link to mysql
    require('/home/web/scred.php');
// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
// Check connection
    if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
    } // if conn
	echo '<div class="btn-group-vertical btn-default" style="width=20%">';

	for ($i=0; $i < $q; $i++) {  

                        $bookingDateTimeStart->add(new DateInterval("P".$intval));
                        $bookingDateTimeEnd->add(new DateInterval("P".$intval));


		if( $multi != 0) {
			if ($i == 0) {
				 $bookingDateTimeEnd = $bookingDateTimeStart->setTime(23, 30, 00);
//				echo "End: " . $bookingDateTimeEnd->format("Y-m-d H:i:s");
				 $bookingDateTimeStart = $bookingStartOriginal;
//				echo " Start: " . $bookingDateTimeStart->format("Y-m-d H:i:s") . PHP_EOL;
				}
			else if ($i == $q-1 ) {
				 $bookingDateTimeStart->setTime(06, 00, 00);
				 $bookingDateTimeEnd = $bookingEndOriginal;
				}
			else {
				$bookingDateTimeStart->setTime(06, 00, 00);
				$bookingDateTimeEnd->setTime(23, 30, 00);
				}
		} 

		

// get the events for the day 
		$modstrst = $o . " " . $w . " of " . $bookingDateTimeStart->format("M Y") . " " . $t;
		$modstren = $o . " " . $w . " of " . $bookingDateTimeStart->format("M Y") . " " . $u;
// echo $modstr . PHP_EOL; 
	    	if ($mt == "stdweek" && $f == "Monthly" ) {
			 $bookingDateTimeStart->modify($modstrst);
			 $bookingDateTimeEnd->modify($modstren);
			}
// echo $mt . "  " . $o . " " . $w . PHP_EOL;
//echo $bookingDateTimeStart->format("Y-m-d H:i:s") . $bookingDateTimeEnd->format("Y-m-d H:i:s");
			$sqld = 'SELECT * FROM bookings WHERE enddate > "'
							. $bookingDateTimeStart->format("Y-m-d H:i:s") 
							. '" AND startdate < "' 
							. $bookingDateTimeEnd->format("Y-m-d H:i:s") 
							. '" 
							AND (status = "Confirmed" OR status="Requested" ) ORDER BY startdate';
//  echo $sqld;
 			$resulte = mysqli_query($conn, $sqld);
//echo "number of rows returned" . mysqli_num_rows($resulte);
 //			$butid="recbut";
			while ( $rowe = mysqli_fetch_array($resulte)) {
// if there is a endtime after the starttime event and its starttime is before this events endtime then the time is not free
	        	        $entryDateTimeStart = new DateTime($rowe["startdate"]);
        	        	$entryDateTimeEnd = new DateTime($rowe["enddate"]);
//echo "booking start: " . $entryDateTimeStart . " booking end: ". $entryDateTimeEnd . "<br>";
//echo "calendar start: " . $bookingDateTimeStart . " calendar end: ". $bookingDateTimeEnd . "<br><br>";
		                if ( ($entryDateTimeEnd > $bookingDateTimeStart )
                                      && ($entryDateTimeStart < $bookingDateTimeEnd  ) )
					{
					// there is a time clash, what about rooms
					if ( ($rowe["rooms"] == $r) || ($rowe["rooms"] == "Both Halls") || ($r == "Both Halls") )
						{
						$busy = "Booking conflict, please check Calendar";
						}
					} 
			} //while
			$genid = $butid . $i;
			$genide = "recbute" . $i;
			if ( $busy == "" ) {
				$bcol = "default";
				$butdis = "disabled";
				$popupen = "";
				$funct = "";
			} else {
				$bcol = "outline-danger";
				$butdis = "";
				$popupen = '<span class="popuptext" id="myPopup' . $i . '" value = "' .  $bookingDateTimeStart->format("Y-m-d") . '">bookings</span>';
				$funct = 'onmouseout="myFunction(' . $i . ', ' . $bookingDateTimeStart->format("Ymd") . ')" onmouseover="myFunction(' . $i . ', ' . $bookingDateTimeStart->format("Ymd") . ')"';
			}
			echo '<span id="' . $genid . '">
				<div  class="popup btn-' . $bcol . 
				'"' . $funct . $butdis .
				' id="' . $genide .
				'"  value="' . $bookingDateTimeStart->format("Y-m-d H:i:s") . " - " .  $bookingDateTimeEnd->format("H:i:s") .
				'">'  
				. $bookingDateTimeStart->format("d/m/Y H:i") . " - " . $bookingDateTimeEnd->format("H:i") .
				$popupen . 
				'</div>';
			$hid = ($multi == 0 ) ? "" :"hidden";
        	        echo '        ' . '<button type="button" ' . $hid . ' class="btn btn-link" onclick="remrecur(this.value)" value="' . $genid . '">Remove</button>' . $busy . '</span>';
       	        	$busy = "";

              } // for
              echo '<p hidden id="recursi">' . $i . '</p>';
	

			 
//else {
//				$bcol = "outline-danger";
//				$butdis= "";
  //                              echo '<span id="' . $genid . '">
					
//	                                        <div  class="popup"  onmouseover= "myFunction()" onclick="myFunction()">' . $bookingDateTimeStart->format("dS M Y") . 
//						'<span class="popuptext" id="myPopup" value="' . $bookingDateTimeStart->format("Y-m-d") . '>Bookings for ' . $bookingDateTimeStart->format("dS M Y") . '</span>
//						</div></span>';
//			}
// <div class="popup" onclick="myFunction()">Click me to toggle the popup!<span class="popuptext" id="myPopup">A Simple Popup!</span>

//                        echo '        ' . '<button type="button" class="btn btn-link" onclick="remrecur(this.value)" value="' . $genid . '">Remove</button>' . $busy . '</span>';
//			$busy = "";
		
	//	} // for
//		echo '<p hidden id="recursi">' . $i . '</p>';
	echo "</div>";

    mysqli_close($conn);
?> 
