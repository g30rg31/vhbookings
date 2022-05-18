
 <?php

 /*********************************************************************************************************************
 ***  getcusthint.php                                                                                                 ***
 ***  part of VH Management system                                                                                 ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***  George Thompson                                                                                              ***
 *********************************************************************************************************************/
// receive input from name field and return  table of matching entries in database

// get the q parameter from URL
$q = $_REQUEST["q"];
//add wildcard
$q .=  "%";


// lookup all hints from table if $q is different from ""
if ($q !== "") {
  $q = strtolower($q);
  $len=strlen($q);
require '/home/web/scred.php';
//    require '/var/www/html/required/connect.php';
    $mysqli = new mysqli($sqlservername,$sqlusername,$sqlpassword,$sqldbname);
//     Check connection
    if($mysqli->connect_error) {
  	exit('Could not connect');
	} 

    $result = mysqli_query($mysqli,"SELECT * FROM users WHERE name LIKE '$q'");
// build response table
// table CSS needs work
 echo "<table border='1px solid black' border-spacing='100px' padding='30px'>";
 echo "<caption>Current Users</caption>";

 while($row = mysqli_fetch_array($result))
    {
    echo "<tr>";
//    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['name'] . "</td>";
    echo "<td><a href='deluser.php?id=" . $row['id'] . "'>Delete</a></td>";
    echo "<td><a href='edituser.php?id=" . $row['id'] . "'>Edit</a></td>";
    echo "</tr>";
    }
    echo "</table>";

    mysqli_close($mysqli);



} else //if q !=""
{

// Output "no suggestion" if no hint was found or output correct values
echo  "names not found";
}
?> 
