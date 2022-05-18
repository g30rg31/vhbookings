<?php
require ('/home/web/scredp.php');
$ppurl = "https://api.paypal.com";

$accessstr = $paypal_sid . ":" . $paypal_token;

$listquery = '/v2/invoicing/invoices?total_required=true&page=' . $_REQUEST["page"] . '&page_size=50';
//$listquery = '/v2/invoicing/invoices?total_required=true&page=1&page_size=50';

$accessToken = getppAccess($ppurl, $accessstr);
$authHeader = 'Authorization: Bearer ' . $accessToken;
$optparams = array("Content-Type: application/json", $authHeader);

// get list of invoices
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $ppurl . $listquery );
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $optparams);
    echo curl_exec($curl);
    curl_close ($curl);

function getppAccess($ppurl, $accessstr) {

    $optparams = array("Content-Type: application/json");
    $ppuri = $ppurl . "/v1/oauth2/token";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $ppuri);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($curl, CURLOPT_HTTPHEADER, $optparams);
    curl_setopt($curl, CURLOPT_USERPWD, $accessstr );
    $responseAr = json_decode(curl_exec($curl),true);
    curl_close ($curl);
return $responseAr["access_token"];
}
?>
