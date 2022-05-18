<?php
session_start();

/*********************************************************************************************************************
 ***  signout.php                                                                                                 ***
 ***  part of VH Management system                                                                                 ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***  George Thompson                                                                                              ***
 *********************************************************************************************************************/

?>
<head>

<!DOCTYPE html>
<html lang="en">

<?php
if (isset($_COOKIE['PHPSESSID'])) {
    unset($_COOKIE['PHPSESSID']);
    setcookie('PHPSESSID', '', time() - 3600, '/'); // empty value and old timestamp
}

setcookie("Loggedon", "", time() - 3600);
setcookie("Role", "", time() - 3600);
unset($_SESSION["loggedon"]);
unset($_SESSION["username"]);
unset($_SESSION["role"]);
unset($_SESSION["id"]);
session_destroy();
header("Location:calendar.php");
?>

</head>
<body>
  
</body>
</html>

