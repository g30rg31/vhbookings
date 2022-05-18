
<?php
session_start();

/*********************************************************************************************************************
 ***  newpwd.php                                                                                                 ***
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
    <title>Password Reset</title>
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
	if (!($_POST["password"] == $_POST["cpassword"])) {
		$errmessage = "Passwords do not match";
	}
	$pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,15}$/';

	if(!preg_match($pattern, $_POST["password"])){
		$errmessage = "Password is not strong enough";
	}

	if($errmessage == "")
	{
		//update customers table
			$sql = 'SELECT id, email, name, role FROM customers WHERE email = "' . $_POST["email"] . '"';
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_assoc($result);
			if (mysqli_num_rows($result) != 1) {
				$errmessage = "Email error, please contact the administrator.";
				mysqli_close($conn);
				}
			$sql =  'UPDATE customers SET
				password="' . password_hash($_POST["password"],PASSWORD_DEFAULT) . '" ,
				role="user", rsttoken="", expires="1970-01-01 00:00:00" 
				WHERE email="' . $_POST["email"] . '"';
				$result = mysqli_query($conn, $sql);
				if (!$result ) {
					$errmessage = "Database error, please try later";
				}
		$sql = 'INSERT INTO logs (type, logentry) VALUES ("user", "Password reset for user ' . $_POST["email"] . '")';
		mysqli_query($conn, $sql);
                $_SESSION["loggedon"] = "yes";
                $_SESSION["username"] = $row["name"];
                $_SESSION["role"] = $row["role"];
                $_SESSION["id"] = $row["id"];
                $_SESSION["tries"] = 0;
                if ($row["role"] == "admin" ) {
                        $cooktime= 14400;
                } else {
                        $cooktime = 7200;
                }
                setcookie("Loggedon", $row["name"], time() + $cooktime, "/");
                setcookie("Role", $row["role"], time() + $cooktime, "/");


		// and go back to login
                echo '<script>';
                echo 'alert("Password successfully updated. Please check your profile.");';
                echo 'location.href="register.php";';
                echo '</script>';

//		header("Location: login.php");
	}

} else {// if post
	$email = $_REQUEST["email"];
	$reskey = $_REQUEST["reskey"];

		$sql = 'SELECT expires FROM customers WHERE email = "' . $_REQUEST["email"] . '" AND rsttoken="' . $_REQUEST["reskey"] . '"';
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) != 1) {
			$errmessage = "Reset error, please contact the administrator.";
			mysqli_close($conn);
			header("location:register.php?err=onboardinge");
			exit();	
		}
		$row = mysqli_fetch_assoc($result);
		if ( $row["expires"] <= date("Y-m-d H:i:s"))
		{
			header("location:register.php?err=onboardingp");
			exit();
		}
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




    <div class="bs-example">
        <h1 class="border-bottom pb-3 mb-4">Password Reset</h1>
        <form class="needs-validation" action="newpwd.php" <?php $params["id"] ?> " method="post" novalidate>
	<input type="hidden" name="email" value= <?php echo $_REQUEST["email"]; ?> >	
            <div class="form-group row">
		<div class="col-sm-3">
		        <p>Your login is your registered email address.</p>
		</div>
		<div class="col-sm-9">
			<?php echo '<h4 class="text-danger">' .  $errmessage . '</h4>'; ?>
		</div>
	    </div>


   	    <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="password">Password:</label>
                <div class="col-sm-9">
		    <input type="password" class="form-control" name="password" placeholder="Password"  required>
                </div>
            </div>

	    <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="cpassword">Confirm Password:</label>
                <div class="col-sm-9">
		    <input type="password" class="form-control" name="cpassword" placeholder="Confirm Password"  required>
		    <p>Passwords must be at least 8 characters long and contain at least one uppercase, one lowercase and one number.</p>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-sm-9 offset-sm-3">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </div>
        </form>
    </div>
<?php require("foot.html"); ?>
</body>
</html> 

