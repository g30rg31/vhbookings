<?php
session_start();

/*********************************************************************************************************************
 ***  editevent1.php                                                                                                 ***
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
<title>Village Hall Management</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<style>
.close {
  position: relative;
  right: 32px;
  top: 32px;
  width: 32px;
  height: 32px;
  opacity: 0.4;
}
.close:hover {
  opacity: 1;
}
.close:before, .close:after {
  position: absolute;
  left: 15px;
  content: ' ';
  height: 20px;
  width: 3px;
  background-color: #333;
  color: red;
}
.close:before {
  transform: rotate(45deg);
}
.close:after {
  transform: rotate(-45deg);
}
</style>

<style>
/* Popup container - can be anything you want */
.popup {
  position: relative;
  display: inline-block;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* The actual popup */
.popup .popuptext {
  visibility: hidden;
  width: 170px;
  background-color: #555;
  color: #fff;
  font-size: 14px;
  text-align: justified;
  border-radius: 6px;
  padding: 8px 0;
  position: absolute;
  z-index: 1;
  bottom: 125%;
  left: 50%;
  margin-left: -80px;
}

/* Popup arrow */
.popup .popuptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

/* Toggle this class - hide and show the popup */
.popup .show {
  visibility: visible;
  -webkit-animation: fadeIn 1s;
  animation: fadeIn 1s; 
}

/* Add animation (fade in the popup) */
 @-webkit-keyframes fadeIn {
  from {opacity: 0;} 
  to {opacity: 1;}
}

@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity:1 ;}
} 
</style>

<script>
	function showbookings() {

	var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("bookHint").innerHTML = this.responseText;
		document.getElementById("num_fbooks").hidden = true;
      }
    };
    
    xmlhttp.open("GET", "samebookings.php?id=" + document.getElementById("eventid").value, true);
    xmlhttp.send();

	}

function showevents() {

	var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("custHint").innerHTML = this.responseText;
		document.getElementById("num_books").hidden = true;
      }
    };
    
    xmlhttp.open("GET", "samecustbooks.php?id=" + document.getElementById("custsel").value, true);
    xmlhttp.send();

}

</script>
<script>
function cancelchild(chid) {


    var xmlhttp = new XMLHttpRequest();
/*    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("custHint").innerHTML = this.responseText;
      }
    };
*/
    
    xmlhttp.open("GET", "delevent.php?id=" + chid.substring(2), true);
    xmlhttp.send();

    document.getElementById("cl" + chid.substring(2)).hidden = true;


}
</script>

<script>
function updatestatus(newStatus) {
if (confirm("Do you want to change the status to " + newStatus + "?")) {

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        alert(this.responseText);
      }
    };
    xmlhttp.open("GET", "chgstatus.php?q=" + newStatus + "&id=" + document.getElementById("eventid").value, true);
    xmlhttp.send();
} else {

   alert("Status not changed");
  }
}
</script>

<script>
function getcusthint(str) {
  if (str.length == 0) {
    document.getElementById("custHint").innerHTML = "";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("custHint").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", "getcusts.php?q=" + str, true);
    xmlhttp.send();
  }
}
</script>

<script>
function selname(choice) {
	if ( choice == "" )  {
		 var xmlhttp = new XMLHttpRequest();
		 xmlhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
		        document.getElementById("custsel").innerHTML = this.responseText;
     			 }
   		 };
		 newcust =document.getElementById("custsel").value;
	    	 xmlhttp.open("GET", "addcust.php?q=" + newcust, true);
   		 xmlhttp.send();
  	} else {
	document.getElementById("custsel").value = choice;
	 window.location.href="editevent1.php?name=" + choice;
		}
	document.getElementById("custHint").innerHTML = "";
}
</script> 

<script>
function selbook(bchoice) {
   if ( bchoice.length == 0 ) {
	document.getElementById("bookHint").innerHTML = "";
	return;
} else {
	document.getElementById("booksel").value = bchoice;
	document.getElementById("bookHint").innerHTML = "";
	}
}
</script>

<script>
function getbookhint(str) {
  if (str.length == 0) {
    document.getElementById("bookHint").innerHTML = "";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest()
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          document.getElementById("bookHint").innerHTML = this.responseText;
                 }
          };
                 xmlhttp.open("GET", "getbooks.php?q=" + str, true);
                 xmlhttp.send();
    }
}
</script>


<script>
function updatebt(elemid) {
	var monthNames = [
	    "January", "February", "March",
	    "April", "May", "June", "July",
	    "August", "September", "October",
	    "November", "December"
	  ];
//        document.getElementById("rec").disabled=false;
//        document.getElementById("recchk").disabled = false;

	nemonth= document.getElementById('month').value ;
	nemonthn = monthNames[nemonth-1];

	if (elemid == "month" ) {document.getElementById("emonth").value=nemonth;}
	if (elemid == "year" )  {document.getElementById("eyear").value=document.getElementById("year").value;}
	if (elemid == "day" )   {document.getElementById("eday").value=document.getElementById("day").value;}
	if (elemid == "hour" )  {document.getElementById("ehour").value=document.getElementById("hour").value;}

	sdate = document.getElementById("year").value + "-" + document.getElementById("month").value + "-" + document.getElementById("day").value;
        edate = document.getElementById("eyear").value + "-" + document.getElementById("emonth").value + "-" + document.getElementById("eday").value;
	var jsStartDate= new Date();
	jsStartDate.setFullYear( document.getElementById("year").value, document.getElementById("month").value -1 , document.getElementById("day").value);

	stime = document.getElementById("hour").value + ":" + document.getElementById("mins").value + ":00";
	etime = document.getElementById("ehour").value + ":" + document.getElementById("emins").value + ":00";

	stdate = new Date (document.getElementById("year").value, document.getElementById("month").value, document.getElementById("day").value, document.getElementById("hour").value, document.getElementById("mins").value, 0);
        endate = new Date (document.getElementById("eyear").value, document.getElementById("emonth").value, document.getElementById("eday").value, document.getElementById("ehour").value, document.getElementById("emins").value, 0);

	brooms = document.getElementById("brooms").value;

	var d = new Date();
	var tdate = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();

	document.getElementById("parenterr").innerHTML = "";
	document.getElementById("parentwarn").innerHTML = "";

	if ( endate  < stdate  ) { document.getElementById("parenterr").innerHTML = "Booking ends before the start"; return; }
	if ( jsStartDate  < d ) { document.getElementById("parenterr").innerHTML = "Bookings in the past"; return; }
	if ( stime == etime ) { document.getElementById("parenterr").innerHTML = "End time is same as start time"; return; }

	if ( endate.getDate()  > stdate.getDate() ) { 
		document.getElementById("parentwarn").innerHTML = "Multi-day booking"; 
//		document.getElementById("rec").disabled=true;
//		document.getElementById("recchk").disabled = true;
		}


       var xmlhttp = new XMLHttpRequest()
        xmlhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
             document.getElementById("parentdates").innerHTML = this.responseText;
          }
        };
    querystr = "parentcheck.php?d=" + sdate + "&t=" + stime + "&e=" + edate + "&u=" + etime + "&r=" + brooms  + "&id=" + document.getElementById("eventid").value;

    xmlhttp.open("GET", querystr, true);
    xmlhttp.send();

//    monfreq();
}
</script>

<script>

function remrecur(dateid) {

// if ( document.getElementById("recchk").value == 0 && document.getElementById("recursi") >0 )
	

document.getElementById(arguments[0]).hidden = true;
reccurs = document.getElementById("recchk").value;
document.getElementById("recchk").value = (reccurs - 1);
//console.log(document.getElementById("recursi").innerHTML);

}
</script>

<script>
function monfreq(freq) {
	var weekdayar = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
	var ordinalar = ["first", "second", "third", "fourth", "last"];
	document.getElementById("monthrec").value = "";

	if (document.getElementById("rec").value != "Monthly") {
		document.getElementById("monthrec").hidden = true;
	}
	else {
		var stdate = new Date();
		stdate.setFullYear(document.getElementById("year").value, (document.getElementById("month").value - 1), document.getElementById("day").value);
		var wkday = stdate.getDay();
		if (wkday < 1 ) wkday = wkday +7; 
		document.getElementById("wday").value  = weekdayar[wkday-1];
		document.getElementById("monthrec").hidden = false;

		var wkord = parseInt(document.getElementById("day").value / 7 );

		document.getElementById("ord").selectedIndex= wkord;
	}	
       recurclick(document.getElementById("recchk").value);
}
</script>

<script>
function myFunction(popid, popdate) {

	var datestr = arguments[1];
	var popupig = "myPopup" + arguments[0];
	document.getElementById(popupig).innerHTML = "Days Bookings";
       var xmlhttp = new XMLHttpRequest()
        xmlhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
	        document.getElementById(popupig).innerHTML = this.responseText;
	
          }
        };
    querystr = "daysbooks.php?d=" + datestr;
    xmlhttp.open("GET", querystr, true);
    xmlhttp.send();



  var popup = document.getElementById(popupig);
  popup.classList.toggle("show");
}
</script>

<script> 

function savebooking(stype) {

booksel= document.getElementById("booksel").value;
booksel = booksel.replace('&', '%26');
custsel= document.getElementById("custsel").value;
custsel = custsel.replace('&', '%26');
bookDesc= document.getElementById("bookDesc").value;
bookDesc = bookDesc.replace('&', '%26');
booknotes= document.getElementById("booknotes").value;
booknotes = booknotes.replace('&', '%26');

var url = "writechgbook.php";

	        sdate = document.getElementById("year").value + "-" + document.getElementById("month").value + "-" + document.getElementById("day").value;
        edate = document.getElementById("eyear").value + "-" + document.getElementById("emonth").value + "-" + document.getElementById("eday").value;
        stime = document.getElementById("hour").value + ":" + document.getElementById("mins").value + ":00";
        etime = document.getElementById("ehour").value + ":" + document.getElementById("emins").value + ":00";

        startdate = sdate + " " + stime;
        enddate = edate + " " + etime;
	

    	var params =  'id=' + document.getElementById("eventid").value
//         	+ '&bt=parent'
           	+ '&ns=' + startdate
           	+ '&ne=' + enddate
           	+ '&bs=' + booksel
           	+ '&cs=' + custsel
           	+ '&ro=' + document.getElementById("brooms").value
           	+ '&pe=' + document.getElementById("peeps").value
           	+ '&mu=' + document.getElementById("music").value
           	+ '&al=' + document.getElementById("alcohol").value
           	+ '&cl=' + document.getElementById("clnradio").checked
           	+ '&av=' + document.getElementById("AVequip").checked
           	+ '&bp=' + document.getElementById("bookpriv").checked
           	+ '&cp=' + document.getElementById("contpriv").checked
                + '&se=' + document.getElementById("booksec").checked
           	+ '&st=' + document.getElementById("bstat").value 
//		+ '&rc=' + document.getElementById("recchk").value
//           	+ '&tc=' + document.getElementById("TCs").checked
           	+ '&bn=' + booknotes
           	+ '&bd=' + bookDesc
           	+ '&de=0&pr=0'  ;

   var http = new XMLHttpRequest()
        http.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
                document.getElementById("savemsg").innerHTML = this.responseText;

          }
        };

    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.send(params);
}

</script>

<?php
        if (!($_SESSION["loggedon"] == "yes")  || $_SESSION["username"] == "" ) {

                echo '<script>';
                echo 'alert("You need to be logged on to edit bookings! You can also Register on the login page.");';
                echo 'location.href="login.php"';
                echo '</script>';
                exit();
                }

// link to mysql
	    require('/home/web/scred.php');
// Create connection
	    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
// Check connection
	    if ($conn->connect_error) {
        	    die("Connection failed: " . $conn->connect_error);
	    } // if conn

	   $statar = array( "Requested", "Confirmed", "Cancelled" );
	   $roomar = array("Large Hall", "Small Hall", "Both Halls", "Other" );
	   $freqar = array("Weekly", "Monthly", "Daily" );
	   $peepar = array("less than 20", "between 20 and 50", "between 50 and 100", "more than 100");
	   $alcoar = array("No alcohol at this event", "Serving free alcoholic drinks", "Attendees bring their own alcoholic drinks", "Selling alcoholic drinks") ;
	   $cleanar= array("I will clean the hall and leave it as found", "I required the Cleaning Service");
	   $musicar= array("No music at this event", "I have my own Music License (please email a copy to info@eastmeonvillagehall.co.uk)", "I need a music license for the public performance of music (including streamed content)");
	   $ordinalar = array("first", "second", "third", "fourth", "last");
	   $weekdayar = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

	if ( empty($_REQUEST["id"]) && !empty($_REQUEST["name"]) ) {

		$sql = "SELECT MAX(id) AS maxid FROM bookings";
 		$maxreturn = mysqli_query($conn, $sql);
 		$rowm = mysqli_fetch_array($maxreturn);
		//echo "rows " . mysqli_num_rows($maxreturn) . PHP_EOL;
		$edit_event = $rowm["maxid"]+1;
		$startday = $_REQUEST["y"] . '-' . $_REQUEST["m"]  .  '-' . $_REQUEST["d"] . ' 08:30:00';
		$endday   = $_REQUEST["y"] . '-' . $_REQUEST["m"]  .  '-' . $_REQUEST["d"] . ' 08:30:00';

		$sql = 'INSERT INTO bookings (id, bookingname, customer, status, startdate, enddate) VALUES (' . $edit_event . ', "New Booking", "' . $_COOKIE["Loggedon"] . '",  "Initialised", "' . $startday . '","' . $endday . '")';
//echo $sql;
		mysqli_query($conn, $sql);

		$stmon    = $_REQUEST["m"];
        	$styear   = $_REQUEST["y"];
 	        $stday    = $_REQUEST["d"];
		$endmon   = $_REQUEST["m"];
                $endyear  = $_REQUEST["y"];
                $endday   = $_REQUEST["d"];
	} else {
		$edit_event = $_REQUEST["id"];
// back to original
	}
	   	$sql ="SELECT * FROM bookings WHERE id='$edit_event'";
   		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		if ($row["recparent"] > 0) {
			$sql ='SELECT * FROM bookings WHERE id=' . $row["recparent"] . ' OR recparent = ' . $row["recparent"] . ' ORDER BY startdate';
//echo $sql;
			$resultbn = mysqli_query($conn, $sql);
			// $row = mysqli_fetch_assoc($resultbn);
			$num_events = mysqli_num_rows($resultbn);

		} else {
			$num_events=1;
		}
	$sql = 'SELECT * FROM bookings WHERE customer = "' . $row["customer"] . '" AND  startdate > "' .  date("Y-m-d H:i:s")  . '" ORDER BY startdate';
echo $sql;
	$resultch = mysqli_query($conn, $sql);
	// $resultchar = mysqli_fetch_array($resultch);
	$num_forcust = mysqli_num_rows($resultch);
//echo $num_forcust;


	if ( ($row["customer"] != $_COOKIE["Loggedon"]) && ($_COOKIE["Role"] != "admin") ) {
		echo '<script>';
//                echo 'alert("You cannot edit other users bookings");';
		echo 'location.href="getdate.php?d=' . $_REQUEST["d"] . '&m=' . $_REQUEST["m"] . '&y=' . $_REQUEST["y"] . '"';
                echo '</script>';
		exit;
	}
	$stmon   = substr($row["startdate"], 5, 2);
	$styear  = substr($row["startdate"], 0, 4);
	$stday   = substr($row["startdate"], 8, 2);
        $sthour  = substr($row["startdate"], 11, 2);
        $stmins  = substr($row["startdate"], 14, 2);
        $endhour = substr($row["enddate"], 11, 2);
        $endmins = substr($row["enddate"], 14, 2);
        $endday  = substr($row["enddate"], 8, 2);
	$endmon  = substr($row["enddate"], 5, 2);
        $endyear = substr($row["enddate"], 0, 4);
//	}

/*   if($_SERVER[REQUEST_METHOD] == "POST") {
	$errmessage = "";

        if($_POST["savechg"] == "Cancel") {
                $backto ="Location: calendar.php";
                header($backto);
		exit;
                }

	else {

	        if (!($_SESSION["loggedon"] == "yes")  || $_SESSION["username"] == "" ) {
	                echo '<script>';
        	        echo 'alert("Login timed out. Please login again.");';
                	echo 'location.href="login.php"';
	                echo '</script>';
        	        exit();
                }

		$ostartdate = $row["startdate"];
		$oenddate = $row["enddate"];
		$nstartdate = date("Y-m-d H:i:s", mktime($_POST["hour"], $_POST["minutes"], 0, $_POST["month"], $_POST["day"], $_POST["year"]));
		$nenddate   = date("Y-m-d H:i:s", mktime($_POST["ehour"], $_POST["eminutes"], 0, $_POST["emonth"], $_POST["eday"], $_POST["eyear"]));
//		if ($rooms == "Both") $rooms = "Large Hall  Small Hall";
	
		if ($nstartdate < date("Y-m-d H:i:s")) $errmessage = "Booking starts in the past";
		if ($nstartdate > $nenddate) $errmessage = "Booking ends before it starts";

		if ($errmessage == "") {
			$sql = 'UPDATE bookings SET 

				customer      ="' . $_POST["custName"] . '", 
				bookingname   ="' . $_POST["bookName"]    . '", 
				status        ="' . $_POST["bstat"]       . '", 
				startdate     ="' . $nstartdate           . '",
				enddate       ="' . $nenddate 		  . '",
				rooms         ="' . $_POST["brooms"] 	  . '",
				recurring     ="' . $_POST["recur"] 	  . '",
				frequency     ="' . $_POST["recfreq"] 	  . '", 
                                recparent     ="' . $_POST["brooms"] 	  . '",
                                recsibling    ="' . $_POST["recur"] 	  . '",
                                numrecs       ="' . $_POST["numrec"] 	  . '",
                                publiccontact ="' . $_POST["contpriv"] 	  . '",
                                public        ="' . $_POST["bookpriv"]    . '",
                                occurence     ="' . $_POST["samedate"]     . '",
                                people        ="' . $_POST["numpeeps"]    . '",
                                music         ="' . $_POST["music"]       . '",
                                alcohol       ="' . $_POST["alcoh"]       . '",
                                avequip       ="' . $_POST["AVequip"]     . '",
                                cleaning      ="' . $_POST["clnradio"]    . '",
                                price         ="' . $_POST["recfreq"]     . '",
				deposit       ="' . $_POST["recfreq"]     . '"

				WHERE ID      ="' . $_POST["eventid"]     . '"';
echo $sql;
			$result = mysqli_query($conn,  $sql);

		$returnto = 'Location: getdate.php?d=' . $stday . '&m= ' . $stmon . '&y=' . $styear;	
	//	header($returnto);
		}  // if errmsg is blank
	} // else not cancel
} // if post
*/
?>

</head>


<body>
<?php require("head.php"); ?>
<p id="displace" class="col-sm-9 offset-sm-3"></p>

<div id="mainframe">
<form action="">

<div class="container">
        <h4 class="border-bottom pb-3 mb-4">Edit Booking</h4>
        <form class="needs-validation" action="editevent1.php?id=" <?php $eventid ?> " method="POST" novalidate>
        <input type="hidden" name="cid" value= <?php echo $row["id"]; ?> >

	<div class="form-group row">
                <div class="col-sm-9 offset-sm-3" >
                        <?php echo '<h4 class="text-danger">' .  $errmessage . '</h4>'; ?>
		</div>
        </div>

	
		<div class="form-group row">
                <label class="col-sm-3 col-form-label" for="custName">Customer:</label>
                <div class="col-sm-9">
                        <input type="text" id="custsel" class="form-control" name="custName" onfocus="this.value = ''" onkeyup="getcusthint(this.value)"
 				<?php   echo empty($row["customer"]) ?  'placeholder="Customer Name"' : 'value="' . $row["customer"] . '"';
					echo $_SESSION["role"] == "admin" ?  "" : "disabled" ; 
					?>  required>
				<div id="num_fbooks">This customer has <?php echo $num_forcust; ?> future bookings. <input class="btn btn-primary"  value="Show all bookings" onclick="showevents()"></div>
				<span id="custHint" style="text-align:center"></span>

                </div>
            </div>
	
		<div class="form-group row">
		<label class="col-sm-3 col-form-label" for="bookName">Booking Name:</label>
                <div class="col-sm-9">
			<input type="text" id="booksel" class="form-control" name="bookName" onfocus="this.value = ''" onkeyup="getbookhint(this.value)"
				<?php    echo empty($row["bookingname"]) ?  'placeholder="Booking Name"' : 'value="' . $row["bookingname"] . '"';  ?> required >
		<span id="bookHint" style="text-align:centre"></span>
		<div id="num_books">There are <?php echo $num_events; ?> events in this booking. <input class="btn btn-primary"  value="Show booking events" onclick="showbookings()"></div>
                </div>
            </div>

        <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="bookDesc">Booking Description:</label>
                <div class="col-sm-9">
                        <textarea class="form-control" rows="4" id="bookDesc" name="bookDesc" <?php  echo empty($names[0]) ?  'placeholder="Description of the event"' : 'value="' . $names[0] . '"';  ?>></textarea>
                </div>
            </div>

        

	 <div class="form-group row">
		<label class="col-sm-3 col-form-label" for="sel1">Rooms:</label>
		<div class="col-sm-9">
			<select class="form-control" id="brooms" name="brooms" id="sel1" onchange="updatebt(this.id)">
			    <?php  foreach ($roomar as $croom) {
			        $croom == $row["rooms"] ?  $sel = "selected" : $sel = "";
			        echo '<option value="' .  $croom . '"' . $sel . '>' . $croom . '</option>';
        			} ?>
			</select>
		</div> 
	    </div>

	<div class="form-group row">
		<label class="col-sm-3 col-form-label" for="startdt">Start Date and Time:</label>
		<div class="col-sm-9">

			Month
			<select id='month' name='month' onchange="updatebt(this.id)">
<?php
				for ($i=1; $i<13; $i++) {
				$i == $stmon ?  $sel = "selected" : $sel = "";
				$mname = date("F",mktime(0,0,0,$i,1,2019));
				echo '<option value="' . sprintf("%02d",$i) . '" ' .$sel . '>' . $mname . '</option>';
				} ?>
			</select>
			Year
			<select id='year' name='year' onchange="updatebt(this.id)">
<?php
			for ($i=1; $i<20; $i++) {
				$i+2018 == $styear ?  $sel = "selected" : $sel = "";
				$yname = date("Y",mktime(0,0,0,1,1,2018+$i));
				echo '<option value="' . $yname . '" ' .$sel . '>' . $yname . '</option>';
				} ?>
			</select>
			Day
			<select id='day' name='day' onchange="updatebt(this.id)">
<?php
			for ($i=1; $i<32; $i++) {
				$i == $stday ? $sel = "selected" : $sel = "";
				echo '<option value="' . sprintf("%02d",$i) . '" ' .$sel . '>' . sprintf("%02d",$i) . '</option>';
				} ?>
			</select>
			Time : Hour
			<select id='hour' name='hour' onchange="updatebt(this.id)">
<?php
			for ($i=6; $i<24; $i++) {
				$i == $sthour ?  $sel = "selected" : $sel = "";
				$mname = date("F",mktime(0,0,0,$i,1,2019));
				echo '<option value="' . sprintf("%02d",$i) . '" ' . $sel . '>' . sprintf("%02d",$i) . '</option>';
			  	} ?>
			</select>
			Minutes
			<select id='mins' name='minutes' onchange="updatebt(this.id)">
<?php
			for ($i=0; $i<60; $i+=5) {
			  	$i == $stmins ?  $sel = "selected" : $sel = "";
			  	$yname = date("Y",mktime(0,0,0,1,1,2018+$i));
			  	echo '<option value="' . sprintf("%02d",$i) . '" ' . $sel . '>' . sprintf("%02d",$i) . '</option>';
			  	} ?>
			</select>

		</div>
	</div>

	<div class="form-group row">
                <label class="col-sm-3 col-form-label" for="enddt">End Date and Time:</label>
                <div class="col-sm-9">
                Month

		<select id='emonth' name='emonth' onchange="updatebt(this.id)">
<?php
//		$endmon = substr($row["enddate"], 5, 2);
		for ($i=1; $i<13; $i++) {
			$i == $endmon ?  $sel = "selected" : $sel = "";
			$mname = date("F",mktime(0,0,0,$i,1,2019));
			echo '<option value="' . sprintf("%02d",$i) . '" ' .$sel . '>' . $mname . '</option>';
	  		} ?>
		</select>
		Year
		<select id='eyear' name='eyear' onchange="updatebt(this.id)">
<?php
		for ($i=1; $i<20; $i++) {
		  	$i+2018 == $endyear ?  $sel = "selected" : $sel = "";
			$yname = date("Y",mktime(0,0,0,1,1,$i+2018));
			echo '<option value="' . $yname . '" ' .$sel . '>' . $yname . '</option>';
		  	} ?>
		</select>
		Day
		<select id='eday' name='eday' onchange="updatebt(this.id)">
<?php
		for ($i=1; $i<32; $i++) {
		  	$i == $endday ?  $sel = "selected" : $sel = "";
			echo '<option value="' . sprintf("%02d",$i) . '" ' . $sel . '>' . sprintf("%02d",$i) . '</option>';
  			} ?>
		</select>
		Time : 	Hour
		<select id='ehour' name='ehour' onchange="updatebt(this.id)">

<?php		 for ($i=6; $i<24; $i++) {
			  $i == $endhour ?  $sel = "selected" : $sel = "";
			  echo '<option value="' . sprintf("%02d",$i) . '" ' . $sel . '>' . sprintf("%02d",$i) . '</option>';
			  } ?>
		</select>
		Minutes
		<select id='emins' name='eminutes' onchange="updatebt(this.id)">
<?php
		for ($i=0; $i<60; $i+=5) {
			 $i == $endmins ?  $sel = "selected" : $sel = "";
			echo '<option value="' . sprintf("%02d",$i) . '" ' . $sel . '>' . sprintf("%02d",$i) . '</option>';
			} ?>
		</select>
		<span><div id="parenterr" style="color:red"></div><div id="parentwarn" style="color:blue"></div></span>
		<p id="parentdates" style="color:red"></p>
 	    </div>
	</div>	

         <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="peeps">Number of people attending:</label>
                <div class="col-sm-9">
                        <select class="form-control" name="numpeeps" id="peeps">
                            <?php  
				$j=0;
				foreach ($peepar as $cpeep) {
                                $cpeep == $row["people"] ?  $sel = "selected" : $sel = "";
                                echo '<option value="' .  $j . '"' . $sel . '>' . $cpeep . '</option>';
				$j++;
                                } ?>
                        </select>
                </div>
            </div>
	 <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="music">Music License:</label>
                <div class="col-sm-9">
                        <select class="form-control" name="music" id="music">
                            <?php  
				$j=0;
				foreach ($musicar as $cmusic) {
                                $cmusic == $row["music"] ?  $sel = "selected" : $sel = "";
                                echo '<option value="' .  $j . '"' . $sel . '>' . $cmusic . '</option>';
				$j++;
                                } ?>
                        </select>
	        </div>
 	 </div>
	 <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="alcohol">Alcohol License:</label>
                <div class="col-sm-9 form-inline form-group">
                        <select class="form-control" name="alcoh" id="alcohol">
                            <?php  
				$j=0;
				foreach ($alcoar as $calco) {
                                $calco == $row["alcohol"] ?  $sel = "selected" : $sel = "";
                                echo '<option value="' .  $j . '"' . $sel . '>' . $calco . '</option>';
				$j++;
                                } ?>
                         </select>
			 <div class="checkbox form-inline">
				<div class="text-info">Please see the <a href="https://www.eastmeonvillagehall.co.uk/booking/terms-and-conditions/" target="_blank">Terms and Conditions for consumption of alcohol in the village hall.</a></div>
                         </div>
		</div>
	  </div>

         <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="clean">Cleaning after the event:</label>
                <div class="col-sm-9">
			<p class="text-info">A deposit is required for using the hall which will be refunded after cleaning costs.</p>
			<label class="radio-inline" >
			    <input type="radio" name="clnradio"  <?php  echo $row["cleaning"] == 0 ? "checked" : "";  ?> >I will clean the hall after the event.
			</label>
			<label class="radio-inline">
			    <input type="radio" id="clnradio" name="clnradio" <?php  echo $row["cleaning"] == 1 ? "checked" : "";  ?> >I wish to use the Hall Cleaning Service.
			</label>
		</div>
	</div>

         <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="AVequip">Audio Visual Equipment:</label>
                <div class="col-sm-9" >
                        <p class="text-info">The village Hall has a large screen, HD projector and sound systems.</p>
                        <label class="radio-inline">
                            <input type="radio" id="AVequip" name="AVequip" value = "1" <?php  echo $row["avequip"] == 1 ? "checked" : "";  ?> >AV equipment hire.
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="AVequip" value = "0" <?php  echo $row["avequip"] == 0 ? "checked" : "";  ?>  >AV equipment not required.
                        </label>
                </div>
        </div>
        
	<div class="form-group row">
        	<label class="col-sm-3 col-form-label" for="booksec">Security:</label>
                <div class="col-sm-9">
                        <div class="checkbox form-inline">
				<input type="checkbox" id="booksec" name="booksec"  class="form-control-lg" <?php  echo $row["sec"] == 1 ? "checked" : "";  ?> >Keep the door locked during my event.
			</div>
			<div class="text-info">By default, the door is unlocked for the duration of your booking. When checked, the door will be locked during your event.</div>
						 
                </div>
            </div>

	<div class="form-group row">
        	<label class="col-sm-3 col-form-label" for="bookpriv">Privacy:</label>
                <div class="col-sm-9">
                        <div class="checkbox form-inline">
				<input type="checkbox" id="bookpriv" name="bookpriv"  class="form-control-lg" <?php  echo $row["public"] == 1 ? "checked" : "";  ?> >Make the Booking Name public in the Calendar.
			</div>
			<div class="text-info">When checked, this will display the Booking Name when users view the booking information.</div>
						 
			<div class="checkbox form-inline">
                                <input type="checkbox" id="contpriv" name="contpriv"  class="form-control-lg" <?php  echo $row["publiccontact"] == 1 ? "checked" : "";  ?>> Make your contact details visible.
			</div>
			<p class="text-info">When checked, this will display your contact details when users view the booking information.</p>

                </div>
            </div>


<?php
	if ($_SESSION["role"] == "admin") {
		echo '<div class="form-group row">';
                echo '<label class="col-sm-3 col-form-label" for="status">Booking Status:</label>';
	        	echo '<div class="col-sm-9 form-inline form-group">';
                        echo '<select class="form-control" name="bstat" id="bstat" onchange="updatestatus(this.value)">';
                            	foreach ($statar as $cstat) {
                                $cstat == $row["status"] ?  $sel = "selected" : $sel = "";
                                echo '<option value="' .  $cstat . '"' . $sel . '>' . $cstat . '</option>';
                                } 
                        echo '</select>';
	                echo '</div>';
        	echo '</div>';
	} else {
		echo '<p id="bstat" value= "Requested" hidden></p>';
	}
?>

	 <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="booknotes">Special Requests or notes for the Booking Manager:</label>
                <div class="col-sm-9">
                        <textarea class="form-control" rows="4" id="booknotes" name="booknotes" <?php  echo empty($names[0]) ?  'placeholder="Special Requests or notes"' : 'value="' . $names[0] . '"';  ?>></textarea>
                </div>
            </div>



	<div class="form-group row">
		<label class="col-sm-3 col-form-label" for "conftcs">Confirm:</label>
                <div class="col-sm-9">
                    <label class="checkbox-inline">
			<div class="text-info">I understand my responsibilities as the licencee of this event.</div>
			<p class="text-info">I have completed a risk assessment and understand I am responsible for safeguarding at this event and that an additional deposit maybe required.</p>
			<input type="checkbox" class="mr-1" id="TCs" name ="TCs" value="agree" required > I have read and agree to the <a href="https://www.eastmeonvillagehall.co.uk/booking/terms-and-conditions/" target="_blank">Terms and Conditions.</a>
                    </label>
                </div>
            </div>


        <div class="form-group row">
            <div class="col-sm-9 offset-sm-3">
		<input class="btn btn-primary"  value="Save this event" id= "savebook" onclick="savebooking(this.id)">
		<input class="btn btn-primary"  value="Save all events" id = "saveall" onclick="savebooking(this.id)">
		<input class="btn btn-secondary" value="Cancel" onclick="canceledit()">
		<p id="savemsg"></p>	
		   </div>
	</div>
</div>
<?php

echo '<input type="hidden" id="eventid" name="eventid"  value="' . $edit_event .  '"><br>';
echo '<input type="hidden" name="dis_day"  value="' . $params["d"] . '"><br>';
echo '<input type="hidden" name="dis_mon"  value="' . $params["m"] . '"><br>';
echo '<input type="hidden" name="dis_year" value="' . $params["y"] . '"><br>';
?>
</div>
</form>
</body


