<?php

    require('/home/web/scred.php');

// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    } // if conn

// get changes

	$sql = 'SELECT * FROM chgbookings WHERE id=' . $_REQUEST["q"];
        $result = mysqli_query($conn, $sql);
        $changerow = mysqli_fetch_assoc($result);

	$sql = 'SELECT bookingname, pincode, customer, invoiced, recparent FROM bookings WHERE id=' . $_REQUEST["q"];
//echo $sql;
	$result = mysqli_query($conn, $sql);
	$bookdetails = mysqli_fetch_assoc($result);
//var_dump($bookdetails);
	$sql = 'SELECT name, email, token, pincode FROM customers WHERE name = "' . $bookdetails["customer"] . '"';
	$result = mysqli_query($conn, $sql);
	$custdetails = mysqli_fetch_assoc($result);
//var_dump($custdetails);
// pass row to build update sql statement and send sql update

	if ( $changerow["status"] == "Cancel" ) {
		$sql = 'UPDATE bookings SET status="Cancelled" WHERE id=' . $_REQUEST["q"];
		$ret = mysqli_query($conn, $sql);
		$action = "cancelled";
	} else {

		$action = "changed";
		$sqlb = buildchgstr($changerow);
        	$ret = mysqli_query($conn, $sqlb);
	}
	$sqlc = 'UPDATE chgbookings SET status="Accepted" WHERE id=' . $_REQUEST["q"];
       	$ret = mysqli_query($conn, $sqlc);

	echo '{"pin":"' . $bookdetail["pincode"] . '","booking":"' . $bookdetails["bookingname"] .
		'","customer":"' . $bookdetails["customer"] .
		'","email":"' . $custdetails["email"] .
		'","token":"' . $custdetails["token"] .
		'","charge":"' . $custdetails["chargeable"] .
		'","invoice":"' . $bookdetails["invoiced"] .
		'","family":"' . (($bookdetails["recparent"] == NULL)  || ($bookdetails["recparent"] == 0) ? $bookdetails["id"] : $bookdetails["recparent"]) .
		'","action":"' . $action .
		'"}';



function genpinCode() {

        $pinCode = "";
        for ($i=0; $i<6; $i++) {        // 6 digit pins
                $pinChar = rand(0,9);
                $pinCode = $pinCode . $pinChar;
                }
	return($pinCode);
}

function buildchgstr($rowg) {

	$columnAr = "";
	$i = 0;
	$chgstr="UPDATE bookings SET ";
	$columnDesc = array("id"=>"",
	"bookingname"=>"Booking",
	"customer"=>"Name",
	"groupname"=>"Business",
	"startdate"=>"Start Date and Time",
	"enddate"=>"End Date and Time",
	"rooms"=>"Hall",
	"status"=>"",
	"invoiced"=>"",
	"recurring"=>"",
	"frequency"=>"",
	"recparent"=>"",
	"recsibling"=>"",
	"numrecs"=>"",
	"publiccontact"=>"Contact Details Public",
	"public"=>"Booking Public",
	"occurence"=>"",
	"people"=>"Attendees",
	"music"=>"Music License",
	"alcohol"=>"Alcohol License",
	"avequip"=>"AV Equipment",
	"cleaning"=>"Cleaning Required",
	"tandcs"=>"",
	"price"=>"",
	"deposit"=>"",
	"hours"=>"",
//	"pincode"=>"",
	"chargeable"=>"",
	"setout"=>"Set out Tables and Chairs" );

        $keysd = array_keys($rowg);

        foreach ($keysd as $bookchgentry) {
	        if (  ($columnDesc[$bookchgentry] != "")  && ($rowg[$bookchgentry] != NULL )) {
        	        $chgstr .= ($i ? "," : "") . $bookchgentry . '="' . $rowg[$bookchgentry] . '"';
                	$i++;
                }
        }
	$chgstr .= " WHERE id =" . $rowg["id"];
	return $chgstr;

}
?>
