
<?php
session_start();

/*********************************************************************************************************************
 ***  dashboard.php                                                                                                 ***
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
.btn {
  min-width: 80px;
}
</style>

<script>
function setup() {

	getcurbooking();
	getstatus();
	 var myVar = setInterval(getcurbooking, 15*60*1000);
	 var myVar = setInterval(getstatus, 1 * 1000);
}
</script>

<script>
lockControl = 	0; //lock pin
lightControl =	7; // lightpin
alarmControl = 	2; // alarmpin
auxControl = 	3; // auxpin
defaults = 	0x01;

function getstatus() {

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
//	document.getElementById("displace").innerHTML = this.responseText;
        statusAr = JSON.parse(this.responseText);

	// changes bits where off is high
	reportStatus = statusAr["status"] ^ 0b01111001;

	for (i=0; i<8; i++) {
		if ( reportStatus & (1<<i) )  disstatus = "ON"; else disstatus = "OFF";
		document.getElementById("cts" + i).innerHTML = disstatus;

		if (statusAr["stachg"+i] != 0) {
			date = new Date(statusAr["stachg"+i]);
			printdate = timeConverter(date);
		} else{
                        printdate = "Never";
                }
		document.getElementById("lcs" + i).innerHTML = printdate;

		if (statusAr["sensors"] & (1<<i)) {

			dissensor = "Open"; 
		} else {
			dissensor = "Closed";
		}
		document.getElementById("sns" + i).innerHTML = dissensor;

		if (statusAr["senint"+i] != 0) {
			date = new Date(statusAr["senint"+i]);
			printdate = timeConverter(date);
		} else{
			printdate = "Never";
		}
		document.getElementById("lcc" + i).innerHTML = printdate;
	}
	if (statusAr.status & (1<<lightControl)) {
		document.getElementById("bg41").disabled = true;
                document.getElementById("bg40").disabled = false;
	}else{
                document.getElementById("bg40").disabled = true;
                document.getElementById("bg41").disabled = false;
	}
	if (statusAr.status & (1<<lockControl)) {
		document.getElementById("bg61").disabled = true;
                document.getElementById("bg60").disabled = false;
	}else{
                document.getElementById("bg60").disabled = true;
                document.getElementById("bg61").disabled = false;
	}
	if (statusAr.status & (1<<auxControl)) {
		document.getElementById("bg71").disabled = true;
                document.getElementById("bg70").disabled = false;
	}else{
                document.getElementById("bg70").disabled = true;
                document.getElementById("bg71").disabled = false;
	}
	if (statusAr.status & (1<<alarmControl)) {
		document.getElementById("bg01").disabled = true;
                document.getElementById("bg00").disabled = false;
	}else{
                document.getElementById("bg00").disabled = true;
                document.getElementById("bg01").disabled = false;
	}

      }
    };
    xmlhttp.open("GET", "switch.php?q=2:0", true);
    xmlhttp.send();

}
function timeConverter(UNIX_timestamp){
  var a = new Date(UNIX_timestamp * 1000);
  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  var year = a.getFullYear();
  var month = months[a.getMonth()];
  var date = a.getDate();
  var hour = a.getHours();
  var min = "0" + a.getMinutes();
  var sec = "0" + a.getSeconds();
  var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min.substr(-2)  + ':' + sec.substr(-2);
  return time;
}
</script>

<script>
function getcurbooking() {


    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
	bookingAr = JSON.parse(this.responseText);

	sqlDate=new Date(bookingAr.data[0].startdate);
	dayofev=sqlDate.getDate() + "/" + ( parseInt(sqlDate.getMonth())+1);
	document.getElementById("curbook").value = bookingAr.data[0].bookingname + " - " + bookingAr.data[0].customer + " Starts at " + bookingAr.data[0].startdate.substr(11,5) + " ends at " + bookingAr.data[0].enddate.substr(11,5) + " on " + dayofev;
	sqlDate=new Date(bookingAr.data[1].startdate);
	dayofev=sqlDate.getDate() + "/" + ( parseInt(sqlDate.getMonth())+1);
	document.getElementById("nextbook").value = bookingAr.data[1].bookingname + " - " + bookingAr.data[1].customer + " Starts at " + bookingAr.data[1].startdate.substr(11,5) + " ends at " + bookingAr.data[1].enddate.substr(11,5) + " on " + dayofev;
      }
    };
    xmlhttp.open("GET", "getcurbooking.php", true);
    xmlhttp.send();
}
</script>

<script>
function processbutton(thebutton) {

	command = thebutton.substr(2,1);
	message = thebutton.substr(3);
// process button press
	var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("errmessage").innerHTML = this.responseText;
          }
        };
        xmlhttp.open("GET", "switch.php?q=" + command + ":" + message, true);
        xmlhttp.send();
// create log entry
	var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("errmessage").innerHTML = this.responseText;
          }
        };
        xmlhttp.open("GET", "writelog.php?q=" + command + ":" + message + "&t=user", true);
        xmlhttp.send();
}
</script>

<?php
$lockControl = 0;
$lightControl = 7;
$controlDesc = array("Front Door Lock","External Strobe","External Alarm", "Aux", "Exit Button", "Beeper", "KeyPad LED","External Lights");
$sensorDesc = array("Front Door","Small Hall Patio Doors","Kitchen External Doors", "Main Hall Patio Doors", "Rear Fire Escape", "Lobby PIR", "Main Hall PIR","Alarm Tamper");

        if (!($_SESSION["loggedon"] == "yes")  || $_SESSION["username"] == "" || $_SESSION["role"] != "admin") {

                echo '<script>';
                echo 'alert("You are not authorised to use this page.");';
                echo 'location.href="login.php"';
                echo '</script>';
                exit();
                }
?>
</head>


<body onload="setup()">
<?php
include 'head.php';
?>
<p id="displace" class="col-sm-9 offset-sm-3"></p>

<div id="mainframe">
<form action="">

<div class="container">
        <h4 class="border-bottom pb-3 mb-4">Status</h4>
        <form action="">

	<div class="form-group row">
                <div class="col-sm-9 offset-sm-3" >
                        <?php echo '<h4 class="text-danger" id="errmessage"></h4>'; ?>
		</div>
        </div>

	<div class="form-group row">
		<label class="col-sm-3 col-form-label" for="curbook">Active Booking:</label>
                <div class="col-sm-9">
			<input type="text" id="curbook" class="form-control" name="curbook"  disabled>
                </div>
            </div>

        <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="nextbook">Next Booking:</label>
                <div class="col-sm-9">
                        <input type="text" class="form-control" id="nextbook" name="nextbook" disabled>
                </div>
            </div>
</div>

<div class="container">
    <div class="row bg-dark text-white text-left" >
	<div class="col-sm-2">Devices</div>
	<div class="col-sm-1">Status</div>
	<div class="col-sm-2">Last Change</div>
	<div class="col-sm-1 bg-white"></div>
	<div class="col-sm-2">Sensors</div>
	<div class="col-sm-1">Status</div>
	<div class="col-sm-2">Last Change</div>
    </div>
<?php
//	echo '<div class="row">';
	for ($i=0; $i<8; $i++) {
	echo '<div class="row">';
		echo '<div class="col-sm-2">' . $controlDesc[$i] . '</div>';
		echo '<div class="col-sm-1" id="cts' . $i . '"></div>';
		echo '<div class="col-sm-2" id="lcs' . $i . '"></div>';
		echo '<div class="col-sm-1"></div>';
		echo '<div class="col-sm-2">' .  $sensorDesc[$i] . '</div>';
		echo '<div class="col-sm-1" id="sns' . $i . '"></div>';
		echo '<div class="col-sm-2" id="lcc' . $i . '"></div>';

	echo '</div>';
	}
echo '</div>';

?>
<br>
<div class="container">
<div class="form-group row">
  <label class="col-sm-3 col-form-label" for="bgon">Door:</label>
  <div class="btn-group" role="group" aria-label="Locks">
    <button type="button" class="btn btn-success" name = "bgon" id="bg60" onclick="processbutton(this.id)">Lock</button>
    <button type="button" class="btn btn-danger" name = "bgoff" id="bg61" onclick="processbutton(this.id)">Unlock</button>
  </div>
</div>
<div class="form-group row">
  <label class="col-sm-3 for="bhon">Outside Lights:</label>
  <div class="btn-group" role="group" aria-label="Lights">
    <button type="button" class="btn btn-success" name = "bhon" id="bg41" onclick="processbutton(this.id)">On</button>
    <button type="button" class="btn btn-danger" name = "bhoff" id="bg40" onclick="processbutton(this.id)">Off</button>
  </div>
</div>
<div class="form-group row">
  <label class="col-sm-3 for="bion">External Alarm:</label>
  <div class="btn-group" role="group" aria-label="Alarm">
    <button type="button" class="btn btn-danger" name = "bion" id="bg01" onclick="processbutton(this.id)">On</button>
    <button type="button" class="btn btn-success" name = "bioff" id="bg00" onclick="processbutton(this.id)">Off</button>
  </div>
</div>
<div class="form-group row">
  <label class="col-sm-3 for="bjon">Aux:</label>
  <div class="btn-group" role="group" aria-label="Aux">
    <button type="button" class="btn btn-success" name = "bjon" id="bg70" onclick="processbutton(this.id)">On</button>
    <button type="button" class="btn btn-danger" name = "bjoff" id="bg71" onclick="processbutton(this.id)">Off</button>
  </div>
</div>


</div>
</form>
</body


