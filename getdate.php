<?php
session_start();

/*********************************************************************************************************************
 ***  getdate.php                                                                                                 ***
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
<title>EMVH Access Manager</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>


<?php

    $role = $_SESSION["role"];
    $username = $_SESSION["username"];
    $id = $_SESSION["id"]; 
    require('/home/web/scred.php');
// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);


$url =  "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//echo $url. "<br>";
//echo $query;
parse_str(parse_url($url)['query'], $params);

                        $disday  = $params["d"];
                        $dismon  = $params["m"];
                        $disyear = $params["y"];
                        $disid   = $params["id"];  

        $day_event = date("Y-m-d", mktime(0,0,0,$params['m'],$params['d'],$params['y'])) . "%";
	$day_dis = date("d-m-y", mktime(0,0,0,$params['m'],$params['d'],$params['y']));
//      echo $day_event;
        $sql ='SELECT * FROM bookings WHERE startdate LIKE "' . $day_event . '" AND (status="Confirmed" OR status="Requested") ORDER BY startdate';
//      echo $sql . "<br>";
        $result = mysqli_query($conn, $sql);
?>

</head>
<body>

<?php
    require("head.php");

?>
<br>
<p id="displace" class="col-sm-9 offset-sm-3"></p>

<div id="mainframe" class="container-fluid">
<div class="row">
<div class="col-sm-3">
  
	 <h5>Use this page to view and edit bookings.</h5>
	 <p>If you are not logged in, you can view the bookings on the day selected from the calendar.</p>
         <p>If you are logged in:</p>
  	 <ul>	
	  <li>you will see all the booking in your name and have the ability to request changes and cancellations which are subject to approval by the Management Committee.</li>
          <li>you are able to change whether your contact details are publically available</li>
	</ul>

</div>

<div class="col-sm-9" style="font-size:16px">


<?php 
if ($_SESSION["loggedon"] == "yes" ){
echo '<h2>Bookings for ' . $_SESSION["username"] . '</h2>';
}else{
echo "<h2>Bookings for " .  $params['d'] .  "/" . $params['m'] . "/" . $params['y'] . "</h2>";
}


echo '<form action="addevent.php" method="GET">';
echo '<input type="hidden" name="id"  value="' . $disid . '">';
echo '<input type="hidden" name="d"  value="' . $disday  . '">';
echo '<input type="hidden" name="m"  value="' . $dismon  . '">';
echo '<input type="hidden" name="y" value="' . $disyear  . '">';
echo '<input type="submit" name="addevent" value="Add New Event">';
?>
</form>

<br>
  <div class="row">
	<div class="col-sm-2 bg-info">Booking</div>
        <div class="col-sm-1 bg-info">Date</div>
        <div class="col-sm-1 bg-info">Start Time</div>
        <div class="col-sm-1 bg-info">End Time</div>
        <div class="col-sm bg-info">Hall</div>
<?php
	if ($_SESSION["loggedon"] ==  "yes" ) {
          echo '<div class="col bg-info">Status</div>';
          echo '<div class="col bg-info">Recurring</div>';
          echo '<div class="col bg-info">Public</div>';
          echo '<div class="col bg-info">Action</div>';
//          echo '<div class="col bg-info">Cancel</div>';
          $sql ='SELECT * FROM bookings WHERE customer = "' . $_SESSION["username"] . '" AND startdate >="' . date("Y-m-d H:i:s") . '" AND (status = "Confirmed" OR status ="Requested" )ORDER BY startdate';
	  }
	else {
	        echo '<div class="col-sm bg-info">Contact</div>';
	        echo '<div class="col-sm bg-info">Phone</div>';
        	echo '<div class="col-sm bg-info">Email</div>';

		$day_event = date("Y-m-d", mktime(0,0,0,$params['m'],$params['d'],$params['y'])) . "%";
//      	echo $day_event . "%";
	        $sql ='SELECT * FROM bookings WHERE startdate LIKE "' . $day_event . '" AND (status="Confirmed" OR status="Requested") ORDER BY startdate';
//      	echo $sql . "<br>";
	}
        $result = mysqli_query($conn, $sql);

?>




  </div>

<br>


<?php
//echo mysqli_num_rows($result);


//echo "here<br>";
 if (mysqli_num_rows($result) > 0) {
                   // output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
			if ($row["recurring"] == 0) {
				$recurring = "N";
			} else {
				$recurring = "Y";
			}
			if ($row["public"] || $row["customer"] == $_SESSION["username"]) {
			        $booking = $row["bookingname"];
			} else {
			        $booking = "Private";
			}
			echo '<div class="row">';

//			if ($row["recparent"] == 0)
//			{
				$sqlbn='SELECT notes FROM bookingdescr WHERE bookingname="' . $row["bookingname"] . '"';
//			} else {
//				$sqlbn='SELECT notes FROM bookingdescr WHERE parentid=' . $row["recparent"];
//			}
			$resultbn = mysqli_query($conn, $sqlbn);
			$rowbn = mysqli_fetch_assoc($resultbn);

			$eventDay=date_create($row["startdate"]);
			$disDate=date_format($eventDay, "d/m/y");
			echo '<div class="col-sm-2"> <a href="#" title="' . $booking . '" data-toggle="popover" data-trigger="hover" data-content="' . $rowbn["notes"] . '"> ' . $booking . ' </a></div>';
			echo '<div class="col-sm-1">' . $disDate . '</div>';
			echo '<div class="col-sm-1">' . substr($row["startdate"],11,5) . '</div>';
			echo '<div class="col-sm-1">' . substr($row["enddate"],11,5) . '</div>';
			echo '<div class="col-sm">' . substr($row["rooms"],0,5) . " " . substr($row["rooms"],12,5) . '</div>';
			
			if ($_SESSION["loggedon"] == "yes" ) {

   			   echo '<div class="col-sm">' . $row["status"] . '</div>';
			   echo '<div class="col-sm">' . $row["recurring"] . '</div>';
			   echo '<div class="col-sm">' . $row["public"] . '</div>';
//			   echo '<div class="col-sm"> <a href=editevent1.php?id=' . $row["id"] . '&d=' . $params["d"] . '&m='. $params["m"] . '&y='  . $params["y"] . '>Edit</a> </div>';
			   echo '<div class="col-sm"> <a href=editevent1.php?id=' . $row["id"] . '&d=' . $params["d"] . '&m='. $params["m"] . '&y='  . $params["y"] . '>Edit</a>  /  <a href=delevent.php?id=' . $row["id"] . '&d=' . $params["d"] . '&m='. $params["m"] . '&y='  . $params["y"] . '>Cancel</a> </div>';
//                           echo '<div class="col-sm"> <a href=delevent.php?id=' . $row["id"] . '&d=' . $params["d"] . '&m='. $params["m"] . '&y='  . $params["y"] . '>Cancel</a> </div>';
			} else {
				if ($row["publiccontact"])
				{
//					echo '<div class="col-sm">' . $row["publiccontact"] . '</div>';
					$sql = 'SELECT phone, email, name FROM customers WHERE name = "' . $row["customer"] . '"';
					$resultc = mysqli_query($conn, $sql);
					$rowc = mysqli_fetch_assoc($resultc);
//					echo  '<div class="col-sm"> <a href="#" title="' . $rowc["name"] . '" data-toggle="popover" data-trigger="hover" data-content="' . $rowc["phone"] . ' ' . $rowc["email"] . '"> ' . $row["customer"] . ' </a></div>';
					echo  '<div class="col-sm">' . $rowc["name"] . '</div>';
					echo  '<div class="col-sm">' . $rowc["phone"] . '</div>';
					echo  '<div class="col-sm">' . $rowc["email"] . '</div>';
				} else  {
                                        echo  '<div class="col-sm">Withheld</div>';
                                        echo  '<div class="col-sm">  </div>';
                                        echo  '<div class="col-sm">  </div>';
				}
			}
                        echo "</div>";       
                        }
                    }
mysqli_close($conn);
?>


</div>
</div>
</div>
</body>
</html>

