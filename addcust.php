<?php
session_start();

/*********************************************************************************************************************
 ***  adduser.php                                                                                                 ***
 ***  part of VH Management system                                                                                 ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***  George Thompson                                                                                              ***
 ********************************************************************************************************************/

// link to mysql
            require('/home/web/scred.php');
// Create connection
            $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
// Check connection
            if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
            } // if conn

//$url =  "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//echo $url. "<br>";
//echo $query;
//parse_str(parse_url($url)['query'], $params);

empty($_REQUEST["q"]) ? $custname = "Herby" : $custname =$_REQUEST["q"];
$role = "user";
//update customers table

$sql =   'INSERT INTO customers ( name, role ) values
		("'	
			. $custname              . '", "' 
			. $role                  . '")';
mysqli_query($conn, $sql);
echo $custname;
?>
