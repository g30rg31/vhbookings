<?php
session_start();
 /*********************************************************************************************************************
 ***  getemailhint.php                                                                                                 ***
 ***  part of VH Management system                                                                                 ***
 ***                                                                                                               ***
 ***  George Thompson                                                                                              ***
 *********************************************************************************************************************/
// receive input from name field and return  table of matching entries in database
	if ($_COOKIE["Role"] == "admin") {
// get the q parameter from URL
		$q = $_REQUEST["q"];
//add wildcard
		$q .=  "%";

// lookup all hints from table if $q is different from ""
		if ($q !== "") {
		  $q = strtolower($q);
		  $len=strlen($q);
	          require("/home/web/scred.php");
        	  $mysqli = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

	          if($mysqli->connect_error) {
               	    exit('Could not connect');
		  }
		  $sql = 'SELECT id, email FROM customers WHERE email LIKE "' . $q . '"';
		  $result = mysqli_query($mysqli, $sql);

		  while($row = mysqli_fetch_array($result)) {
		        echo '<button type="button" class="btn" onclick="seluser(this.value)" value="' . $row["email"] . '">' . $row["email"] . '</button><br>';
		  }
		  mysqli_close($mysqli);


		} else { //if q !=""
			echo  "names not found";
		}
	} else {
		echo "";
	}
?> 
