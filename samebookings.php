
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

require '/home/web/scred.php';
    $mysqli = new mysqli($sqlservername,$sqlusername,$sqlpassword,$sqldbname);
//     Check connection
  if($mysqli->connect_error) {
  	exit('Could not connect');
	} 
    $sql = 'SELECT * FROM bookings WHERE id = ' . $_REQUEST["id"] ;
    //echo $sql;
    $resultl = mysqli_query($mysqli,  $sql );
    $rowl=mysqli_fetch_array($resultl);
    // build response table
// table CSS needs work

$sql = 'SELECT * FROM bookings WHERE id = ' . $rowl["recparent"] . ' OR recparent = ' .  $rowl["recparent"] . ' ORDER BY startdate' ;
$result = mysqli_query($mysqli, $sql );
// echo $sql;
echo "<table border='1px solid black' border-spacing='100px' padding='30px'>";
 //echo "<caption>Events part of this booking</caption>";

 while($row = mysqli_fetch_array($result))
    {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['bookingname'] . "</td>";
    echo "<td>" . $row['startdate'] . "</td>";
    echo "<td>" . $row['enddate'] . "</td>";
    echo "<td>" . $row['rooms'] . "</td>";
    echo "<td>" . $row['status'] . "</td>";
    echo "<td><a href='delevent.php?id=" . $row['id'] . "'>Cancel</a></td>";
    echo "<td><a href='editevent1.php?id=" . $row['id'] . "'>Edit</a></td>";
    echo "</tr>";
    }
    echo "</table>";

    mysqli_close($mysqli);




?> 
