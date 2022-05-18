<?php
session_start();

/*********************************************************************************************************************
 ***  calendar.php                                                                                                 ***
 ***  part of VH Management system                                                                                 ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***  George Thompson                                                                                              ***
 *********************************************************************************************************************/

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>EMVH Manager</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<style>
.roomcolsh {
  font-size: small;
  color: #0033cc;
}
.roomcollh {
  font-size: small;
  color: #009933;
}
.roomcolbh {
  font-size: small;
  color: #660066;
}
.butroomcolsh {
  font-size: small;
  color: #0033cc;
}
.butroomcollh {
  font-size: small;
  color: #009933;
}
.butroomcolbh {
  font-size: small;
  color: #660066;
}
.navhd {
  font-size: medium;
  text-align: left;
}
</style>

<script>
function updatecal() {

newmonth = document.getElementById("chgmonth").value;
newyear = document.getElementById("chgyear").value;
currentroom = document.getElementById("chgroom").innerHTML;
newquery = "calendar.php?m=" + newmonth  + "&y=" + newyear +"&r=" + currentroom;
window.location.href = newquery;
return;

}
</script>

<script>

function updatehalls(room) {
newmonth = document.getElementById("chgmonth").value  ;
newyear = document.getElementById("chgyear").value;
document.getElementById("chgroom").innerHTML = room;
//if (room == "lh") document.getElementById("bookingsheader").innerHTML = "Bookings for the large hall"; 
//else if (room == "sh") document.getElementById("bookingsheader").innerHTML = "Bookings for the small hall"; 
//else document.getElementById("bookingsheader").innerHTML = "Bookings for the both halls"; 
currentroom = room;
newquery = "calendar.php?m=" + newmonth  + "&y=" + newyear + "&r=" +room;
window.location.href = newquery;
return;

}
</script>

<script>
function updatecalp() {
currentroom = document.getElementById("chgroom").innerHTML;
newmonth = document.getElementById("chgmonth").value  ;
newyear = document.getElementById("chgyear").value;
newmonth--;
if ( newmonth == 0 ){
        newyear--;
        newmonth = 12;
        }

newquery = "calendar.php?m=" + newmonth  + "&y=" + newyear + "&r=" + currentroom;
window.location.href = newquery;
return;

}
</script>

<script>
function updatecaln() {
currentroom = document.getElementById("chgroom").innerHTML;
//alert("roomflag = " + currentroom);
newmonth = document.getElementById("chgmonth").value ;
newyear = document.getElementById("chgyear").value;
newmonth ++;
if ( newmonth == 13 ){
	newyear++;
	newmonth = 1;
	}

newquery = "calendar.php?m=" + newmonth  + "&y=" + newyear + "&r=" + currentroom;
window.location.href = newquery;
return;

}
</script>

<?php

    $role = $_SESSION["role"];
    $username = $_SESSION["username"];
    $id = $_SESSION["id"];
//    require('/home/web/scred.php');
//    require('connect.php');

$url =  "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//echo $url. "<br>";
//echo $query;
parse_str(parse_url($url)['query'], $params);

$weekdays = array("Monday","Tuesday", "Wednesday", "Thursday", "Friday","Saturday", "Sunday" );
//$monthdays = array(31,28,31,30,31,30,31,31,30,31,30,31);


if( empty($params["m"])) {
        $display_mon = date("n",time());
        $display_lyear = date("Y",time());
	$disroom = "Both Halls";
	$roomsw = "bh";
} else {
        $display_mon  = $params["m"];
        $display_lyear = $params["y"];
	$roomsw = $params["r"];
}
$display_lmon = date("F", mktime(0,0,0,$display_mon,1,$display_lyear));
$day_1st = date("N", mktime(0,0,0,$display_mon,1,$display_lyear));
//$display_mon > 1 ? $display_prevmon = $monthdays[$display_mon-2] : $display_prevmon = $monthdays[11];
$display_mon > 1 ? $display_prevmon = cal_days_in_month(CAL_GREGORIAN,$display_mon-1,$display_lyear) : $display_prevmon = cal_days_in_month(CAL_GREGORIAN,11,$display_lyear-1);
$day_1st < 0 ? $day_1st = $day_1st + 7 : $day_1st = $day_1st + 0;
?>



</head>


<body>

<?php 
require("head.php"); 
?>

<p id="displace" class="col-sm-10 offset-sm-2"></p>
<div id="mainframe" >



<!-- set up grid  -->
<div class="container-fluid">
<br>
<?php

	if ($params["r"] == "sh" ) {$getrooms = ' AND (rooms = "Small Hall" OR rooms="Both Halls") '; $disroom ="Small Hall"; } 
	elseif ($params["r"] == "lh" ) {$getrooms = ' AND (rooms = "Large Hall" OR rooms="Both Halls") '; $disroom="Large Hall"; } 
	else {$getrooms = ""; $disroom="Both Halls"; }

	echo '<h3 id="bookingsheader">Bookings for ' . $disroom . '</h3>';
	echo '<p id="chgroom" hidden>' . $roomsw . '</p>'; 
?>
</div>
<div class="container-fluid">
    <div class="row">

<!-- side column -->
      <div class="col-sm-3" style="font-size:12px"> <br><br>
	<p class="navhd">Navigation</p>
	<ul>
	 <li>Navigation controls are above the days of the week.</li>
         <li>Use "previous" and "next" to move back and forth through<br> the calendar.</li>
         <li>Select the month and year to go to a specific month.</li>
         <li>Press "Reset" to get back to the current month.</li>
 	 <li>Click on the day of month to view all bookings for that day.</li>
	 <li>Click on the room name to see bookings only for that room.</li>
	</ul>
	<p class="navhd">Managing bookings</p>
	<ul>
	 <li>You need to be <a href="register.php">Registered </a> and <a href="login.php">Logged On </a> to create and manage bookings.</li>
	 <li>You can reset your password from the <a href="login.php">login</a> page.
	 <li>Click on the day of the month you wish to make a new booking.</li>
	 <li>Click on any day of the month and you will be shown all your current bookings with the options to amend or cancel.</li>
	 <li>All bookings and change requests must be approved by our bookings manager before they will be updated inthe calendar.</li>
	 <li>Booking and changes conformation will be sent by email.</li>
	</ul>
	<p class="navhd">Access to the Hall</p>
	<ul>
	 <li>The hall has an electronic access control system.</li>
	 <li>Your booking confirmation email will include the code for you to access the hall.</li>
	 <li>If you provided a mobile phone number in your profile, the code will also be sent by SMS.</li>
	 <li>The code is only valid for the duration of each of your bookings plus 15 minutes before and after.</li>
	 <li>The code is valid for the recurring bookings made at the same time.</li>
	 <li>A new code is generated for each new booking request.</li>
	</ul>
      </div>	

<!-- main content here  -->

<?php

// code to get sql here

    require("/home/web/scred.php");

// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    } // if conn

echo '<div class="col-sm-9"  style="font-size:12px">';

?>
<span style="text-align:center">
<form action="calendar.php" method="POST">
 <div class="btn-group">
  <button type="button" class="btn btn-default" onclick = "updatecalp()" id="01" name ="chmon" value="previous">previous</button>
  <button type="button" class="btn btn-default" onclick = "updatecaln()" id="02" name ="chmon" value="next">next</button>
</div>

<select name="chgmonth" id="chgmonth" onchange="updatecal()">
<?php
for($i=1; $i <13; $i++) {
        $month = date("F", mktime(0,0,0,$i,1,2019));
        if ($i==$display_mon) {
                $sel = "selected";
                }
        else {
                $sel = "";
                }
echo '<option ' . $sel .' value=' . $i . '>' . $month . '</option>';

 } ?>
</select>

<select name="chgyear" id="chgyear" onchange="updatecal()">
<?php
$i = 1;
for($j=1; $j < 20; $j++) {
        $year = 2018 + $j;
        if ($year == $display_lyear) {
                $sel = "selected";
                }
        else {
                $sel = "";
                }
echo '<option ' . $sel .' value=' . $year . '>' . $year . '</option>';
 } ?>
</select>

 <div class="btn-group">
  <input type="hidden" class="btn btn-default" name="chmon" value="Go">

  <input type="submit" class="btn btn-default" name="chmon" value="Reset to current month">
 </div>
</form>
</span>

<?php

// room colour guide
echo '<span style="text-align:center">
		<div class="row btn-group">
			<p> Text Colour denotes which hall is booked</p>
			 <button type="button" class="btn butroomcollh" onclick = "updatehalls(this.id)" id="lh"  value="Large Hall">Large Hall</button>
			 <button type="button" class="btn butroomcolsh" onclick = "updatehalls(this.id)" id="sh"  value="Small Hall">Small Hall</button>
			 <button type="button" class="btn butroomcolbh" onclick = "updatehalls(this.id)" id="bh"  value="Both Halls">Both Halls</button>
		</div></span>';

//code to generate calendar entries

// headers
	    echo '<div class="row">'; // new grid within col-9
	    for ($i=1; $i<8; $i++) {
                        $day = $weekdays[$i-1];
                        echo  '<div class="col-sm bg-dark text-white text-center">' . $day . '</div>';
			}
	    echo "</div>";  // close first row
// days
            for ($i=1; $i<43; $i++) {
                // calc the calendar layout
                $display_dom = $i - $day_1st + 1;
                $display_mons = $display_mon;
                $display_lyears = $display_lyear;

                if ($display_dom > date("t",mktime(0,0,0,$display_mon,1,$display_lyear))) {
                        $display_dom = $i - $day_1st- date("t",mktime(0,0,0,$display_mon,1,$display_lyear))+1;
                        $display_mons = $display_mon +1;

                        if ($display_mons == 13) {
                                $display_mons = 1;
                                $display_lyears = $display_lyear +1;
                        } // if display_mons
                } //if display_dom> > days in month

                if ($display_dom < 1 ){
                        $display_dom +=  $display_prevmon;
                        $display_mons = $display_mon-1;

                         if ($display_mons == 0) {
                                $display_mons = 12;
                                $display_lyears = $display_lyear -1;
                        } // if display_mons


                } // if display_dom <1


//do for each cell
                if ($i%7 == 1)  {
                        echo "<div class=row>"; // start new row
			$j=0;
                } else {
			$j++;  // count through weekdays
		}

//		echo $display_lyears ."-".$display_mons."-".$display_dom;
if (date("Y-n-j") == $display_lyears ."-".$display_mons."-".$display_dom) {

                echo '<div class="col-sm border-top border-primary bg-warning">';   //  for 1 col width for each day
	}else {
                echo '<div class="col-sm border-top border-primary">';   //  for 1 col width for each day
	}
//                echo  "<div class=item".$i.">";
//              echo "<br>";
// echo date("Y-n-d");
// echo $display_lyears ."-".$display_mon."-".$display_dom;
if (date("Y-n-j") == $display_lyears ."-".$display_mons."-".$display_dom) {
	$day_class = "col-sm bg-dark text-white text-center";
	echo "Today ";
	} else {
	$day_class = "col-sm text-dark text-center";
	echo substr($weekdays[$j],0,3) . " ";
	}
//echo $day_class;
                echo  '<a class="' . $day_class . '" href=getdate.php?d=' . $display_dom . '&m=' . $display_mons . '&y=' . $display_lyears . '&i=' . $i . '&l=' . $day_1st . '>' . $display_dom . "/" . $display_mons . '</a>';
//                echo  '<a class="col-sm text-dark text-center" href=getdate.php?d=' . $display_dom . '&m=' . $display_mons . '&y=' . $display_lyears . '&i=' . $i . '&l=' . $day_1st . '>' . $display_dom . '</a>';
                echo "<br><br>";



//do for each cell
//		if ($i%7 == 1)  {
//			echo "<div class=row>"; // start new row
//		}
//		echo '<div class="col-sm">';   //  for 1 col width for each day
//                echo  "<div class=item".$i.">";
//		echo  '<div  class="col-sm border-top border-primary" href=getdate.php?d=' . $display_dom . '&m=' . $display_mons . '&y=' . $display_lyears . '&i=' . $i . '&l=' . $day_1st . '>' . $display_dom . '</div>';
  //              echo "<br>";

                $searchdate = date("Y-m-d", mktime(0,0,0,$display_mons, $display_dom, $display_lyears)) . "%";
                $sql = 'SELECT * FROM bookings WHERE startdate LIKE "' .  $searchdate . '" AND (status="Confirmed" OR status="Requested")' . $getrooms . ' ORDER BY startdate';
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                   // output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
			if ($row["public"] != "1" ) {
				$disname="Private";
			} else {
				$row["status"] == "Requested" ? $disname="Provisional" : $disname=$row["bookingname"];
			}
                               $sttime = substr($row["startdate"], 11,5);
                               $entime = substr($row["enddate"], 11, 5);
                               $rooms = substr($row["rooms"],0,1);
					if($rooms == "L" ) $roomcol = "roomcollh";
					elseif ($rooms == "S" ) $roomcol = "roomcolsh";
					else $roomcol = "roomcolbh";

//                        if ($row["recparent"] == 0)
//                        {
                                $sqlbn='SELECT notes FROM bookingdescr WHERE bookingname LIKE "' . $row["bookingname"] . '%"';
//                        } else {
//                                $sqlbn='SELECT notes FROM bookingdescr WHERE parentid=' . $row["recparent"];
//                        }
                        $resultbn = mysqli_query($conn, $sqlbn);
                        $rowbn = mysqli_fetch_assoc($resultbn);
 

                              $entry = $sttime . '-' . $entime . ' (' .$rooms . ')<br><a href="#" data-toggle="popover" data-trigger="hover" data-content="' . $rowbn["notes"] . '"> ' . " " . $disname . '<br>';
//				$entry = $sttime . "-" . $entime . " (" .$rooms . ")<br>" . $disname . "<br>";

				if( $_SESSION["role"] == "admin" )
				{
                                echo  '<a class="' . $roomcol . '" href=editevent1.php?d=' . $display_dom . '&m=' . $display_mons . '&y=' . $display_lyears . '&id=' . $row["id"] . '>' . $entry . '</a';
				} else {
                                echo  '<a class="' . $roomcol . '" href=getdate.php?d=' . $display_dom . '&m=' . $display_mons . '&y=' . $display_lyears . '>' . $entry . '</a';
				}
				echo "</div>";
                   }  //while
		} else {
         	   echo "Available";
		} // else num rows
         	echo "</div>"; // close day entry
	        if($i%7 == 0) echo "</div>"; // close row after 7 days
} // for 42 days
// echo "</div>"; // close col-9
echo "</div>"; // close row for whole section

echo "</div>"; // close outside container
echo "</div>";

?>
<?php include("foot.html"); 
mysqli_close($conn);  
?>
</body>
</html>

