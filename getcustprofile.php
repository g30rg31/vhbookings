 <?php
session_start();
 /*********************************************************************************************************************
 ***  getcustprofile.php                                                                                                 ***
 ***  part of VH Management system                                                                                 ***
 ***                                                                                                               ***
 ***  George Thompson                                                                                              ***
 *********************************************************************************************************************/
// only provide hints if loggedon
if (isset($_COOKIE["Loggedon"])) {

// receive input from name field and return  table of matching entries in database

// get the q parameter from URL
	$q = $_REQUEST["name"];

	if (filter_var($q, FILTER_VALIDATE_EMAIL)) {
		$get = "email";
	} else {
		$get = "name";
	}

	require("/home/web/scred.php");
	$mysqli = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

	if($mysqli->connect_error) {
  		exit('Could not connect');
	} 
	$sql = 'SELECT * FROM customers WHERE ' . $get . ' = "' . $q . '"';

	$result = mysqli_query($mysqli,$sql);
	$resultAr = mysqli_fetch_assoc($result);
	unset($resultAr["password"]);
	echo  json_encode($resultAr);
	mysqli_close($mysqli);
} else {
	echo "";
}
?> 
