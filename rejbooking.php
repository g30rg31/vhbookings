<?php
require('/home/web/scred.php');

// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    } // if conn


//$url =  "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//echo $url. "<br>";
//parse_str(parse_url($url)['query'], $params);
//echo $params['d'] . "<br>";

//        $del_event = $params['id'];
        $sql ='UPDATE bookings SET status="Rejected" WHERE id=' . $_REQUEST["q"] . ' OR recparent=' . $_REQUEST["q"];
echo $sql;
        $result = mysqli_query($conn, $sql);
//        $returnto = "Location: getdate.php?d=$params[d]&m=$params[m]&y=$params[y]&D=$params[id]";
//	header($returnto);
    mysqli_close($conn);
?>
