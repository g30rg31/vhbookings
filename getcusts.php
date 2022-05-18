
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
empty( $_REQUEST["q"] ) ? $q="n" : $q = $_REQUEST["q"];
//add wildcard
$q .=  "%";
$i=0;

// lookup all hints from table if $q is different from ""
if ($q !== "") {
  $q = strtolower($q);
  $len=strlen($q);
// link to mysql
            require('/home/web/scred.php');
// Create connection
            $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

//     Check connection
    if( $conn->connect_error) {
	exit('Could not connect');
	} 
	$sql = 'SELECT name FROM customers WHERE name LIKE "' . $q . '" GROUP BY name';
    $result = mysqli_query($conn, $sql);
// build response table
 echo "<table border='1px solid black' border-spacing='100px' padding='30px'>";

 echo '<td><button type="button" class="btn btn-default" onclick="selname(this.value)" id="custnew">Add new customer</button>';
 while($row = mysqli_fetch_assoc($result))
    {
	    $i++;
	    echo "<tr>";
	    echo '<td><button type="button" class="btn btn-default" onclick="selname(this.value)" id="cust' . $i . '" value="' . $row["name"] . '">' . $row["name"] . '</button>';
	    echo "</td></tr>";
    }
 echo "</table>";

 mysqli_close($conn);
}
?> 
