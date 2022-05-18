<?php

$fp = fopen ("/home/web/ppaudittrail.log", "a");
$fpidem = fopen("/home/web/ppidem.txt", "r");

fwrite($fp, 'parameters received: ' . $_REQUEST["action"] . ' ' . $_REQUEST["invoice"] . '\n');

$ppurl = "https://api.paypal.com";

if ($_REQUEST["action"] == "") {
	$invcmd = "record-payment";
	$invuri = "https://api.sandbox.paypal.com/v2/invoicing/invoices/INV2-GWPW-T6S6-U7WZ-QYSD";
} else {
	$invcmd = $_REQUEST["action"];
	$invuri = $_REQUEST["invoice"];
	$invref = substr($_REQUEST["invoice"],-24);
}

fwrite ($fp, 'request to ' . $invcmd . ' on ' . $invref . "\n");

$accessToken = getppAccess($ppurl);
$authHeader = 'Authorization: Bearer ' . $accessToken;
$idemnumber = 1000;
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $invuri );

if ( $invcmd == "send" ) {
	$optparams = array("Content-Type: application/json", $authHeader);
	$postdata ='{"send_to_recipent":true}';
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt( $curl, CURLOPT_POST, 1);
	$responsetype = "JSON links object";
	fwrite($fp, 'SEND: setting opts and data:  ' . $postdata . ' for ' . $invuri . "\n");
	if (!curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata)) fwrite($fp, "curl-setopt CURLOPT_POSTFIELDS");
}
elseif ( $invcmd == "self" ) {
	$optparams = array("Content-Type: application/json", $authHeader);
	if (!curl_setopt( $curl, CURLOPT_HTTPGET, 1)) fwrite($fp, "curl_setopt CURLOPT_HTTPGET failure");
	$responsetype = "JSON invoice object";
	fwrite($fp, 'SELF: setting opts for ' . $invuri . "\n");
}
elseif ( $invcmd == "replace" ) {
        $optparams = array("Content-Type: application/json", $authHeader);
	curl_setopt( $curl,CURLOPT_PUT,1);
	$postdata = "link to invoice data";
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
	$responsetype = "JSON invoice object";
	fwrite($fp, 'REPLACE: setting opts and data:  ' . $postdata . ' for ' . $invuri);
}
elseif  ( $invcmd == "delete" ) {
        $optparams = array("Content-Type: application/json", $authHeader);
	if (!curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE")) fwrite($fp, "curl__setopt CURLOPT_CUSTOMREQUEST failure");
	$responsetype = "NONE";
	fwrite($fp, 'DELETE: setting opts for ' . $invuri);
}
elseif ( $invcmd == "record-payment" ) {
	$idemnumber = fread($fpidem,filesize("/home/web/ppidem.txt"));
	fclose($fpidem);
	$fpidem =fopen("/home/web/ppidem.txt", "w");
	fwrite($fpidem, $idemnumber+1);
	fclose($fpidem);
	$optparams = array("Content-Type: application/json", $authHeader, "PayPal-Request-Id: " . $idemnumber );
        $postdata ='{"method":"' .  $_REQUEST["pmethod"] . '","payment_date":"' .  $_REQUEST["pdate"] . '","amount": {"currency_code": "GBP", "value":"' .  $_REQUEST["pamount"] . '" } }';
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt( $curl, CURLOPT_POST, 1);
	$responsetype = "JSON links object";
	fwrite($fp, 'RECORD PAYMENT: setting opts and data:  ' . $postdata . ' to ' . $invuri);
}
elseif ( $invcmd == "record-refund" ) {
	$idemnumber = fread($fpidem,filesize("/home/web/ppidem.txt"));
	fclose($fpidem);
        $fpidem =fopen("/home/web/ppidem.txt", "w");
	fwrite($fpidem, $idemnumber+1);
	fclose($fpidem);
	$optparams = array("Content-Type: application/json", $authHeader, "PayPal-Request-Id: " . $idemnumber);
        $postdata ='{"method":"' .  $_REQUEST["pmethod"] . '","payment_date":"' .  $_REQUEST["pdate"] . '","amount": {"currency_code": "GBP", "value":"' .  $_REQUEST["pamount"] . '" } }';
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt( $curl, CURLOPT_POST, 1);
	$responsetype = "JSON id of refund";
	fwrite($fp, 'RECORD REFUND: setting opts and data:  ' . $postdata . ' to ' . $invref);
}
elseif ( $invcmd == "qr-code" ) {
	$optparams = array("Content-Type: application/json", $authHeader);
        $postdata ='{"width":400,"height":400}';
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt( $curl, CURLOPT_POST, 1);
	$responsetype = "PNG image of qr code";
	fwrite($fp, 'QR CODE: setting opts and data:  ' . $postdata . ' to ' . $invuri);
}
elseif ( $invcmd == "cancel" ) {
        $optparams = array("Content-Type: application/json", $authHeader);
        $postdata ='{"subject":"Invoice cancelled","note":"This invoice from East Meon Village Hall has been cancelled", "send_to_invoicer":true }';
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt( $curl, CURLOPT_POST, 1);
        $responsetype = "Cancel confirm";
	fwrite($fp, 'CANCEL: setting opts and data:  ' . $postdata . ' to ' . $invuri);
}
elseif ( $invcmd == "remind" ) {
        $optparams = array("Content-Type: application/json", $authHeader);
        $postdata ='{"subject":"Invoice Reminder","note":"Your invoice from East Meon Village Hall is overdue for payment. Please pay this invoice to secure your booking", "send_to_invoicer":true }';
	if (!curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata)) fwrite($fp, "curl-setopt CURLOPT_POSTFIELDS");;
        curl_setopt( $curl, CURLOPT_POST, 1);
        $responsetype = "204 no content";
	fwrite($fp, 'REMIND: setting opts and data:  ' . $postdata . ' to ' . $invuri);
}
else {
	fwrite($fp, 'action not defined: ' . $invcmd);
	exit;
}

    if (!curl_setopt($curl, CURLOPT_RETURNTRANSFER, true)) fwrite($fp, "curl_setopt CURLOPT_RETURNTRANSFER failure");
    if (!curl_setopt($curl, CURLOPT_HTTPHEADER, $optparams)) fwrite($fp, "curl_setopt CURLOPT_HTTPHEADER failure");
    $responseAr = curl_exec($curl);
    $ppResponseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    fwrite($fp, "HTTP return code is: " . $ppResponseCode);
    curl_close ($curl);
      if ($ppResponseCode == 202 && $invcmd == "send") echo "Send Invoice accepted by PayPal";
      if ($ppResponseCode == 204 && $invcmd == "delete") 
	{
		echo "Delete Invoice accepted by PayPal";
		delinvfromdb($invref);
	}
      if ($ppResponseCode == 202 && $invcmd == "replace") echo "Invoice Update accepted by PayPal";
      if ($ppResponseCode == 200 && $invcmd == "record-payment") echo "Payment accepted by PayPal";
      if ($ppResponseCode == 200 && $invcmd == "refund-payment") echo "Refund accepted by PayPal";
      if ($ppResponseCode == 204 && $invcmd == "cancel") 
	{
		echo "Invoice cancellation accepted by PayPal";
		delinvfromdb($invref);
	}
      if ($ppResponseCode == 204 && $invcmd == "remind") echo "Invoice reminder sent by PayPal";
      if ($ppResponseCode == 200 && $invcmd == "self")
	{
		$headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		// extract body
		echo substr($responseAr, $headerSize);
	}

fclose($fp);

function getppAccess($ppurl) {
$optparams = array("Content-Type: application/json");

    require ('/home/web/scredp.php');
    $accessstr = $paypal_sid . ":" . $paypal_token;
    $ppuri = $ppurl . "/v1/oauth2/token";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $ppuri);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($curl, CURLOPT_HTTPHEADER, $optparams);
    curl_setopt($curl, CURLOPT_USERPWD, $accessstr);
    $responseAr = json_decode(curl_exec($curl),true);
//var_dump($responseAr);
    curl_close ($curl);
    return $responseAr["access_token"];
//        echo "Access token: " . $responseAr["access_token"];
}

function delinvfromdb($ppinvnum)
{
//	return;

require("/home/web/scred.php");

// need connection statements
$mysql = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
                                if ($mysql->connect_error) {
                                        die("Connection failed: " . $mysql->connect_error);
                                        } // if conn
$sql = 'update bookings set invoiced="" WHERE invoiced="' . $ppinvnum. '"';
$result = mysqli_query($mysql, $sql);
mysqli_close($mysql);
return;
}

?>
