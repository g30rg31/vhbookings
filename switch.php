<?php
error_reporting(E_ALL);
$_REQUEST["q"] == '' ? $q = "4:0" : $q = $_REQUEST["q"];
//echo $q . PHP_EOL;
//echo "<h2>TCP/IP Connection</h2>\n";
//echo $q;
/* Get the port for the WWW service. */
$service_port = '5000';

/* Get the IP address for the target host. */
$address = '127.0.0.1';

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
//    echo "Socket OK.\n";
}

//echo "Attempting to connect to '$address' on port '$service_port'...";
$result = socket_connect($socket, $address, $service_port);
if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
//    echo "Connect OK.\n";

/*
$in = "HEAD / HTTP/1.1\r\n";
$in .= "Host: www.example.com\r\n";
$in .= "Connection: Close\r\n\r\n";
*/
//$in = $_REQUEST["q"];
$out = '';

//echo "Sending HTTP request...";
socket_write($socket, $q, strlen($q));
//echo "OK.\n";

//echo "Reading response:\n\n";
while ( $out = trim(socket_read($socket, 1024)) ) {
    echo $out;
}
}
//echo "Closing socket...";
socket_close($socket);
//echo "OK.\n\n";

//echo $out;
?>

