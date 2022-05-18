
<?php
session_start();

/*********************************************************************************************************************
 ***  register.php                                                                                                 ***
 ***  part of VH Management system                                                                                 ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***  George Thompson                                                                                              ***
 *********************************************************************************************************************/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Village Hall Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
        .bs-example {
            margin: 20px;
        }
    </style>
<?php

	    require('/home/web/scred.php');

	// Create connection
	    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
	//	echo $conn;
	// Check connection
	    if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
	    } // if conn

$url =  "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//echo $url. "<br>";
//echo $query;
parse_str(parse_url($url)['query'], $params);

$errmessage="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

// check passwords match and are strong
	if (!($_POST["Password1"] == $_POST["Password2"])) {
		$errmessage = "Passwords do not match";
	} else {
		$pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,15}$/';
//		$pattern = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';
		if(!preg_match($pattern, $_POST["Password1"])){
			$errmessage = "Password is not strong enough";
		} else {

//check its a valid email address
			if (!filter_var($_POST["inputEmail"], FILTER_VALIDATE_EMAIL)) {
				  $errmessage = "Invalid email format";
			
			} else  {
// build customer name
			$custname =$_POST["firstName"] . " " . $_POST["lastName"];
//set role type
			if (!$_SESSION["role"] == "admin") {
				 {$role = "user";
					}
				}
			}
		}
// check T&Cs are agreed

	if (!($_POST["TCs"] == "agree")) $errmessage ="Please accept the Terms and Conditions.";

	}

//update customers table


	$sql = 'SELECT id, email FROM customers WHERE email = "' . $_POST["inputEmail"] . '"';
//echo $sql;
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	if (mysqli_num_rows($result) != 1) {
		$errmessage = "Email error, please contact the administrator.";
//		header("Location:edituser.php");
		mysqli_close($conn);
		exit();	
		}
//echo $_POST["Password1"];

	if (!empty($errmessage)) {
		$first = $_POST["firstName"];
		$surname = $_POST["lastName"];
		$email = $_POST["inputEmail"];
		$organisation = $_POST["organisation"];
		$phone = $_POST["phoneNumber"];
		$address1 = $_POST["address1"];
		$address2 = $_POST["address2"];
		$address3 = $_POST["address3"];
		$address4 = $_POST["address4"];
		$postCode = $_POST["postCode"];
	} else {
		$sql =   'UPDATE customers SET
				name="' . $custname . '",
				phone="' . $_POST["phoneNumber"] . '" , 
				member="' . $_POST["organisation"] . '" , 
				email="' . $_POST["inputEmail"] . '" ,
				address1="' . $_POST["address1"] . '" ,
				address2="' . $_POST["address2"] . '" ,
				address3="' . $_POST["address3"] . '" ,
				address4="' . $_POST["address4"] . '" ,
				password="' . password_hash($_POST["Password1"],PASSWORD_DEFAULT) . '" ,
				role="user" , 
				postcode="' . $_POST["postCode"] . '"
				WHERE id=' . $row["id"] ;
//echo $sql;
		$result = mysqli_query($conn, $sql);
		if (!$result ) {
			$errmessage = "Database error, please try later";
			}
// assume after successful registration the user is logged on
		else {	
			$_SESSION["username"] = $custname;
			$_SESSION["role"] = $role;
			$_SESSION["loggedon"] = "yes";

//send an email to customer if here as a result of add user





// and go back to the calendar
		header("Location: calendar.php");
		}

	} // if message empty
} else {// if post
	$email = $_REQUEST["email"];
	$reskey = $_REQUEST["reskey"];
//	echo "email: " . $email . " key: " . $reskey; 

/*	if ($_REQUEST["id"] == "" )
	{
                $sql = 'SELECT * FROM customers WHERE email = "' . $_REQUEST["email"] . '"';
//echo $sql;
                $result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
	} else {
*/
		$sql = 'SELECT * FROM customers WHERE email = "' . $_REQUEST["email"] . '"';
echo $sql;
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) != 1) {
			$errmessage = "Email error, please contact the administrator.";
			mysqli_close($conn);
//			header("location:register.php?err=onboardinge");
//echo "email not found";
			exit();	
		}
//echo "email found";
		$row = mysqli_fetch_assoc($result);
		if ($row["rsttoken"] != $reskey)
		{
echo $row["rsttoken"] . " " . $reskey;
//			header("location:register.php?err=onboardingp");
	//		echo "Invalid input, please contact the administrator";
			exit();
		}
		$names = explode(" ",$row["name"]);
		$first = $names[0];
// echo $first;
		$surname = $names[1];
//		$email = $_REQUEST["email"];
		$organisation = $row["member"];
		$phone = $row["phone"];
		$address1 = $row["address1"];
		$address2 = $row["address2"];
		$address3 = $row["address3"];
		$address4 = $row["address4"];
		$postCode = $row["postcode"];
//	}
//echo "pin found";
//	$nameAr = explode(" ", $row["name"]);

// echo $nameAr[0];
	//send an email to customer if here as a result of add user





	// and go back to the calendar
//			header("Location: calendar.php");
//			
	mysqli_close($conn);

} // else if post
?>


    <script>
// Self-executing function
(function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
    </script>
</head>
<body>
<?php require("head.php"); ?>


<input type="hidden" name="cid" value= <?php echo $params["id"]; ?> >

    <div class="bs-example">
        <h1 class="border-bottom pb-3 mb-4">Registration Form</h1>
        <form class="needs-validation" action="onboard.php?id=" <?php $params["id"] ?> " method="post" novalidate>
	<input type="text" hidden name="cid" value= <?php echo $params["id"]; ?> >
	
            <div class="form-group row">
		<div class="col-sm-3">
		        <p>Your login will be your email address.</p>
		</div>
		<div class="col-sm-9">
			<?php echo '<h4 class="text-danger">' .  $errmessage . '</h4>'; ?>
		</div>
	    </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="firstName">First Name:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="firstName" value= <?php echo '"' .  $first . '"';?>   required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="lastName">Last Name:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="lastName" <?php  echo empty($surname) ?  'placeholder="Last Name"' : 'value="' . $surname . '"';  ?>  required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="inputEmail">Email Address:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="inputEmail" <?php  echo empty($email) ?  'placeholder="Email Address"' : 'value="' . $email . '"';  ?>  required>
                </div>
            </div>

	    <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="organisation">Organisation:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="organisation" <?php  echo empty($organisation) ?  'placeholder="Organisation Name (Optional)"' : 'value="' . $organisation . '"';  ?>  >
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="phoneNumber">Phone Number:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="phoneNumber" <?php  echo empty($phone) ?  'placeholder="Mobile Number"' : 'value="' . $phone . '"';  ?>  required>
                </div>
            </div>
          
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="postalAddress">Postal Address:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="address1" <?php  echo empty($address1) ?  'placeholder="Address Line 1"' : 'value="' . $address1 . '"';  ?>  required>
                    <input type="text" class="form-control" name="address2" <?php  echo empty($address2) ?  'placeholder="Address Line 2"' : 'value="' . $address2 . '"';  ?>  required>
                    <input type="text" class="form-control" name="address3" <?php  echo empty($address3) ?  'placeholder="Address Line 3 (Optional)"' : 'value="' . $address3 . '"';  ?> >
                    <input type="text" class="form-control" name="address4" <?php  echo empty($address4) ?  'placeholder="Address Line 4 (Optional)"' : 'value="' . $address4 . '"';  ?>  >
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="postCode">Post Code:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="postCode" <?php  echo empty($postCode) ?  'placeholder="Post Code"' : 'value="' . $postCode . '"';  ?>  required>
                </div>
            </div>

   	    <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="Password1">Password:</label>
                <div class="col-sm-9">
		    <input type="password" class="form-control" name="Password1" placeholder="Password"  required>
                </div>
            </div>

	    <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="Password2">Confirm Password:</label>
                <div class="col-sm-9">
		    <input type="password" class="form-control" name="Password2" placeholder="Confirm Password"  required>
		    <p>Passwords must be at least 8 characters long and contain at least one uppercase, one lowercase and one number.</p>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-9 offset-sm-3">
                    <label class="checkbox-inline">
                        <input type="checkbox" class="mr-1" name ="TCs" value="agree" > I agree to the <a href="https://www.eastmeonvillagehall.co.uk/booking/terms-and-conditions/">Terms and Conditions.</a>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-9 offset-sm-3">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <input type="reset" class="btn btn-secondary" value="Reset">
                </div>
            </div>
        </form>
    </div>
<?php require("foot.html"); ?>
</body>
</html> 

