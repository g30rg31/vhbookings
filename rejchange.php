<?php
require('/home/web/scred.php');

// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    } // if conn

        $sql ='UPDATE chgbookings SET status="Rejected" WHERE id=' . $_REQUEST["q"];
        $result = mysqli_query($conn, $sql);
//        $returnto = "Location: getdate.php?d=$params[d]&m=$params[m]&y=$params[y]&D=$params[id]";
//	header($returnto);
?>
