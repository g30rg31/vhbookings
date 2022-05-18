<?php

    require('/home/web/scred.php');

// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
    if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
    } // if conn


//	        $sql ='INSERT INTO logs (type, logentry) VALUES ("' . $_REQUEST["t"] . '","Command from website ' . $_REQUEST["q"] . ' by user ' . $_COOKIE["Loggedon"] . '")';
// echo $sql;
//	        $result = mysqli_query($conn, $sql);



	mysqli_close($conn);


$daysmonth=date("t");
//echo $daysmonth;
// defaults



?>
<script>
function initialise() {
searchtype="user";
var today=new Date();
var daysinmonth=new Date(today.getFullYear(), today.getMonth()+1,0).getDate();
document.getElementById("styr" + today.getFullYear()).selected=true;
document.getElementById("stmn" + (today.getMonth()+1)).selected=true;
document.getElementById("stdy" + today.getDate()).selected=true;

document.getElementById("enyr" + today.getFullYear()).selected=true;
document.getElementById("enmn" + (today.getMonth()+1)).selected=true;
document.getElementById("endy" + today.getDate()).selected=true;

}
function chgtype(type) {
searchtype=type;
}
function chgstyr(year) {
searchyr=year;
}
function chgstmn(month) {
searchmn=month;
}
function chgstmn(day) {
searchdyn=day;
}


</script>
<html lang="en">
<head>
<title>"Village Hall Management"</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body onload="initialise()";

<h2> Search activity logs </h2>

<form>

<label for="type">Select user activty or system records: </label>
<select id="type" name="type" size="1" onchange="chgtype(this.value)">
	<option value="user" default>User</option>
	<option value="system">System</option>

</select>
<br><br>
<label for="styr">Search From - Year:</label>
<select id="styr" name="styr" size="1" onchange="chgstyr(this.value)">
	<?php
	echo date("Y") . '<br>';
	for($i=date("Y"); $i>date("Y") - 5; $i--) {
		echo '<option id=styr' . $i .  '>' . $i . '</option>';
	}
	?>
</select>
<label for="stmn">Month:</label>
<select id="stmn" name="stmn" size="1" onchange="chgstmn(this.value)">
	<?php
//	echo date("Y") . '<br>';
	for($i=1; $i<13; $i++) {
//		if(date("n") == $i) $def = "selected"; else $def="";
		echo '<option id="stmn' . $i . '">' . $i . '</option>';
	}
	?>

</select>
<label for="stdy">Day:</label>
<select id="stdy" name="stdy" size="1" onchange="chgstdy(this.value)">
	<?php
//	echo date("t") . '<br>';
//	echo $daysmonth;
	for($i=1; $i<$daysmonth; $i++) {
		echo '<option id="stdy' . $i . '">' . $i . '</option>';
	}
	?>

</select>

<br>
<label for="enyr">Search To - Year:</label>
<select id="enyr" name="enyr" size="1" onchange="chgenyr(this.value)">
	<?php
	echo date("Y") . '<br>';
	for($i=date("Y"); $i>date("Y") - 5; $i--) {
		echo '<option id=enyr' . $i .  '>' . $i . '</option>';
	}
	?>
</select>
<label for="enmn">Month:</label>
<select id="enmn" name="enmn" size="1" onchange="chgenmn(this.value)">
	<?php
//	echo date("Y") . '<br>';
	for($i=1; $i<13; $i++) {
		echo '<option id="enmn' . $i .  '">' . $i . '</option>';
	}
	?>

</select>
<label for="endy">Day:</label>
<select id="endy" name="endy" size="1" onchange="chgendy(this.value)">
	<?php
//	echo date("Y") . '<br>';
	for($i=1; $i<$daysmonth; $i++) {
		echo '<option id="endy' . $i . '">' . $i . '</option>';
	}
	?>

</select>





</select>
<br>
<select id=type name="type" size="1" onchange="chgtype(this.value)">
	<option value="user" default>User</option>
	<option value="system">System</option>
</select>
<br>
<select id=type name="type" size="1" onchange="chgtype(this.value)">
	<option value="user" default>User</option>
	<option value="system">System</option>
</select>













</body>
</html>

