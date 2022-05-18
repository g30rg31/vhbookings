 <?php

 /*********************************************************************************************************************
 ***  getbooks.php                                                                                                 ***
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
$i=0;

// lookup all hints from table if $q is different from ""
if ($q !== "") {
    $q = strtolower($q);
    $len=strlen($q);

    require '/home/web/scred.php';
// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
    if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	    } // if conn
    $result = mysqli_query($conn,"SELECT bookingname FROM bookings WHERE bookingname LIKE '$q' GROUP BY bookingname");
// build response table
// table CSS needs work
   echo "<table border='1px solid black' border-spacing='100px' padding='30px'>";
//   echo '<td><button type="button" class="btn btn-default" onclick="selbook(this.value)" id="booknew">Add new booking</button>';

 while($row = mysqli_fetch_array($result))
    {
    $i++;
    echo "<tr>";
//    echo "<td>" . $row['id'] . "</td>";
    echo '<td><button type="button" class="btn btn-default" onclick="selbook(this.value)" id="book' . $i . '" value="' . $row["bookingname"] . '">' . $row["bookingname"] . '</button>';
    echo "</td></tr>";
    }
    echo "</table>";

    mysqli_close($mysqli);
}
?> 
