<?php

// invoice number from paypal create invoice
$ppurl = "https://api.paypal.com";
require("/home/web/scred.php");
require('/home/web/scredp.php');
// need connection statements
$mysql = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
                                if ($mysql->connect_error) {
                                        die("Connection failed: " . $mysql->connect_error);
                                        } // if conn
// test harnes
if ($_REQUEST["customerEmail"] == "" ) {
        $email = "jayne.r.thompson@outlook.com";
        $bookingid = 3647;
} else {
        $email = $_REQUEST["customerEmail"];
        $bookingid = $_REQUEST["bookingParentId"];
}



$sqlc = 'SELECT name, address1,address2,address3,address4,postcode, email, phone from customers where email = "' . $email . '"';
//echo $sqlc . "<br>";
$result = mysqli_query($mysql,$sqlc);

if (mysqli_num_rows($result) != 1) {
	echo 'Error; unique customer not found for ' . $email;
	exit;
	}

$rowc = mysqli_fetch_assoc($result);
$names = explode(" ",$rowc["name"]);

$invoice_number = " "	;
$addnl_notes =" ";

// customer details
$firstname = $names[0];
$lastname =  $names[1];
$bus_name = $rowc["groupname"];
$addr_1 =    $rowc["address1"];
$addr_2 =    $rowc["address2"];
$addr_3 =    $rowc["address3"];
$addr_4 =    $rowc["address4"];
$postcode =  $rowc["postcode"];
$email_addr =$rowc["email"];
$countrycode = "044"	;
$phnumber =  substr($rowc["phone"],1)	;

// additional info value
$addn_info_value = ""	;

$sqlb = 'SELECT * from bookings where status="Confirmed" AND id = ' . $bookingid;
$resultb = mysqli_query($mysql,$sqlb);

if (mysqli_num_rows($resultb) != 1) {
        echo 'Error; No confirmed booking found for ' . $bookingid;
        exit;
        }
$rowb = mysqli_fetch_assoc($resultb);
$bookingstdates = explode(" ", $rowb["startdate"]);
$bookingendates = explode(" ", $rowb["enddate"]);

//get Paypal access Token
$accessstr = $paypal_sid . ":" . $paypal_token;
$accessToken = getppAccess($ppurl, $accessstr);

// prepare invoice components
$detail = '{"detail": {	"invoice_number": "' . getNextInvoiceNumber($ppurl, $accessToken) . '",
		"reference": "' . $rowb["bookingname"] . '",
		"invoice_date": "' .  date("Y-m-d") . '",
		"currency_code": "GBP",
		"term": "Hire and use are subject to the East Meon Village Hall Terms and Conditions",
		"payment_term": { "term_type": "DUE_ON_RECEIPT",
		"due_date":  "' . date("Y-m-d") . '"}}';

$invoicer = '"invoicer": {
   		 "name": {
		 "given_name": "Bookings",
		 "surname": "Manager"},
		 "address": {
		      "address_line_1": "East Meon Village Hall",
		      "address_line_2": "Workhouse Lane",
		      "admin_area_2":  "East Meon",
		      "admin_area_1": "Hampshire",
		      "postal_code": "GU32 1PF",
		      "country_code": "UK"    },
		  "phones": [
		      {
			"country_code": "044",
		        "national_number": "7956783061",
		        "phone_type": "MOBILE"
		      }
		    ],
		"website": "www.eastmeonvillagehall.co.uk",
		"logo_url": "https://www.eastmeonvillagehall.co.uk/wp-content/uploads/2015/07/IMG_4509.jpg"
	  }';

$recipient =  '"primary_recipients": [
		    {
		      "billing_info": {
			"business_name": "' . $bus_name . '",
		        "name": {
		          "given_name": "' . $firstname . '",
		          "surname": "' . $lastname . '"
		        },
		        "address": {
		          "address_line_1": "' . $addr_1 . '",
		          "address_line_2": "' . $addr_2 . '",
		          "admin_area_2": "' . $addr_3 . '",
		          "admin_area_1": "' . $addr_4 . '",
		          "postal_code": "' . $postcode . '",
		          "country_code": "UK"
		        },
		        "email_address": "' . $email_addr . '",
		        "phones": [
		          {
		            "country_code": "' . $countrycode . '",
		            "national_number": "' . $phnumber . '",
		            "phone_type": "MOBILE"
		          }
		        ],
		        "additional_info_value": "' . $addn_info_value .'"
		      }
		    }
		  ]';

$bottomstuff = '"configuration": {
    "partial_payment": {
      "allow_partial_payment": true,
      "minimum_amount_due": {
        "currency_code": "GBP",
        "value": "00.00"
      }
    },
    "allow_tip": true
  }
}';

$sqlr = 'SELECT * from bookings where recparent = "' . $bookingid . '" AND status="Confirmed"';
$resultr = mysqli_query($mysql,$sqlr);
$numrecs = mysqli_num_rows($resultr);

if ($rowb["rooms"] == "Large Hall" ) $event_price=12.00;
if ($rowb["rooms"] == "Small Hall" ) $event_price=6.00;
if ($rowb["rooms"] == "Both Halls" ) $event_price=18.00;

$itemhead = '"items":[ {"name": "Hire Charge", "description": "' . $rowb["rooms"] . ' on ' . $bookingstdates[0] . ' at ' . $bookingstdates[1] . ' to ' . $bookingendates[1] . '","quantity": "' . $rowb["hours"] . '","unit_amount": { "currency_code": "GBP","value": "' . $event_price . '"},"unit_of measure": "QUANTITY"}';
if ($numrecs !=0 ) $itemhead = $itemhead . ",";

// line items
// loop through bookings and populate
$j=0;
while ($rowr = mysqli_fetch_assoc($resultr)) {

	$bookingstdates = explode(" ", $rowr["startdate"]);
	$bookingendates = explode(" ", $rowr["enddate"]);

	$itemhead = $itemhead . '{"name": "Hire Charge", "description": "Recurring booking on ' . $bookingstdates[0] .  '","quantity": ' . $rowr["hours"] . ',"unit_amount": { "currency_code": "GBP","value": ' . $event_price . '},"unit_of_measure": "QUANTITY" }';
	$j++;
	if ($j != $numrecs) {
		$itemhead = $itemhead . ",";
		}
	}

$setoutAr=array(10,25,35,50);
$audioVisualAr=array(30,30,30,30);
$cleaningAr=array(50,50,75,100);
$alcoholAr=array(5,30,30,30);
$musicAr=array(5,5,5,5);
$people=$rowb["people"];
$breakageDepAr=array(100,100,100,100);


if ($rowb["music"] * 1 == 2) {
	$itemhead = $itemhead . ',{"name": "Music License", "description": "Public broadcasting of recorded sound","quantity": "' . (1+$numrecs) . '","unit_amount": { "currency_code": "GBP","value": "' . $musicAr[$people] . '"},"unit_of_measure": "QUANTITY" }';
	}
if ($rowb["cleaning"] * 1 == 1) {
	$itemhead = $itemhead . ',{"name": "Cleaning", "description": "Cleaning the hall after your event, includes floors, toilets and kitchen","quantity": "' . (1+$numrecs) . '","unit_amount": { "currency_code": "GBP","value": "' . $cleaningAr[$rowb["people"]] . '"},"unit_of_measure": "QUANTITY" }';
	}
if ($rowb["alcohol"] * 1 == 3) {
	$itemhead = $itemhead . ',{"name": "Alcohol Licence", "description": "License for sale of Alcohol on premises","quantity": "' . ($numrecs+1) . '","unit_amount": { "currency_code": "GBP","value": "' . $alcoholAr[$people] . '"},"unit_of_measure": "QUANTITY" }';
	}
if ($rowb["avequip"] * 1 == 1) {
	$itemhead = $itemhead . ',{"name": "AV Equipment", "description": "Set up and training of AV equipment","quantity": "1","unit_amount": { "currency_code": "GBP","value": "' . $audioVisualAr[$people] . '"},"unit_of_measure": "QUANTITY" }';
	}
if ($rowb["setout"] * 1 == 1) {
	$itemhead = $itemhead . ',{"name": "Setting Up", "description": "Costs of setting out table and chairs to your requirements","quantity": "' . (1+$numrecs) . '","unit_amount": { "currency_code": "GBP","value": "' . $setoutAr[$peopleIndex] . '"},"unit_of_measure": "QUANTITY" }';
	}
if ( (float)$rowb["deposit"] > 0 ) {
	$itemhead = $itemhead . ',{"name": "Deposit", "description": "Breakages and cleaning deposit (refundable subject to T&Cs","quantity": "1","unit_amount": { "currency_code": "GBP","value": "' . $rowb["deposit"] . '"},"unit_of_measure": "QUANTITY" }';
	}
 


$draftInvoice = $detail . "," . $invoicer . "," . $recipient . "," . $itemhead . "]," . $bottomstuff;
// echo "<br>" . $draftInvoice . "<br>";
$linktoinvoice = setdraftInvoice($ppurl, $accessToken, $draftInvoice);

$sql = 'update bookings set invoiced="' . substr($linktoinvoice, -24) . '" WHERE id=' . $bookingid . ' OR recparent=' . $bookingid;

//"https://api.sandbox.paypal.com/v2/invoicing/invoices/INV2-NSNY-ER6P-M35N-XUVW"
// echo $sql;
$result = mysqli_query($mysql, $sql);

// echo substr($linktoinvoice,-24);

exit;


function getNextInvoiceNumber($ppurl, $accessToken) {

$authHeader = 'Authorization: Bearer ' . $accessToken;
$optparams = array("Content-Type: application/json", $authHeader);

// create invoice number
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $ppurl . "/v2/invoicing/generate-next-invoice-number" );
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $optparams);

    $nextInvoiceNumber = json_decode(curl_exec ($curl),true);
    $invoice_number= $nextInvoiceNumber["invoice_number"];
    curl_close ($curl);
// echo "inv number" . $invoice_number;
return $invoice_number;
}


function setDraftInvoice($ppurl, $accessToken, $draftInvoice) {

$authHeader = 'Authorization: Bearer ' . $accessToken;
$optparams = array("Content-Type: application/json", $authHeader);

// create draft invoice
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $ppurl . "/v2/invoicing/invoices" );
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $draftInvoice);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $optparams);
//    curl_setopt($curl, CURLOPT_HTTPHEADER,


    $draftInvoiceResp = json_decode(curl_exec ($curl),true);
var_dump($draftInvoiceResp);

    curl_close ($curl);
    return $draftInvoiceResp["href"];

}

function getppAccess($ppurl, $accessstr) {
$optparams = array("Content-Type: application/json");

    $ppuri = $ppurl . "/v1/oauth2/token";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $ppuri);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($curl, CURLOPT_HTTPHEADER, $optparams);
    curl_setopt($curl, CURLOPT_USERPWD, $accessstr);

    $responseAr = json_decode(curl_exec($curl),true);
    curl_close ($curl);
    return $responseAr["access_token"];
//        echo "Access token: " . $responseAr["access_token"];



}



?>


