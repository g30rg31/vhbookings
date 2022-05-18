<?php
session_start();



	header("Content-Type: application/json; charset=UTF-8");
	$postdata = json_decode($_POST["postdata"], false);

                      // find user and check password
                        require '/home/web/scred.php';
                        $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
                        if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                        } // if conn

			$cleanname = strtolower($postdata->name);
			$cleanname = ucwords($cleanname);

                        $sql = 'SELECT id,name FROM customers WHERE email="' . $postdata->email . '"';
                        $resultem = $conn->query($sql);
			$currow = mysqli_fetch_assoc($resultem);

			if (!mysqli_num_rows($resultem) && $postdata->action =="new" ) {
			// we can process a new request
				$sql = "SELECT MAX(id) AS maxid from customers";
				$resultmax = mysqli_query($conn, $sql);
				$nextid = mysqli_fetch_assoc($resultmax);
				$nextid["maxid"]++;

				if ( $_SESSION["role"] == "admin") {
					$status="Confirmed";
					$respmsg="Profile for " . $postdata->name . " added.";
				} else {
					$status="Requested";
					$respmsge="Your registration request has been submitted, you can now request bookings.";
				}

				$sql = 'INSERT INTO customers VALUES (' . $nextid["maxid"] . ',"' .
									$cleanname  . '","' .
                                                                        $postdata->phone . '","' .
                                                                        $postdata->organisation . '","' .
                                                                        $postdata->email . '","' .
                                                                        $postdata->addr1 . '","' .
                                                                        $postdata->addr2 . '","' .
                                                                        $postdata->addr3 . '","' .
                                                                        $postdata->addr4 . '","' .
                                                                        password_hash($postdata->password, PASSWORD_DEFAULT) . '","' .
                                                                        $postdata->role . '","' .
                                                                        $postdata->postcode . '","' . $status . '", "","","","","")';
				$ret = mysqli_query($conn,$sql);


			} elseif (mysqli_num_rows($resultem) == 1 && $postdata->action == "update") {
			// we can process an update
				$sql = 'UPDATE customers SET  name="' . $cleanname  . '",
	                                                     phone="' . $postdata->phone . '",
                                                             member="' .$postdata->organisation . '",
                                                             email="' .  $postdata->email . '",
                                                             address1="' . $postdata->addr1 . '",
                                                             address2="' . $postdata->addr2 . '",
                                                             address3="' . $postdata->addr3 . '",
                                                             address4="' . $postdata->addr4 . '",
                                                             role="' .     $postdata->role . '",
                                                             postcode="' . $postdata->postcode . '"  WHERE id = ' . $currow["id"];
				$ret = mysqli_query($conn,$sql);
				echo "Profile for " . $postdata->name . " updated.";
			} elseif (!mysqli_num_rows($resultem) && $postdata->action =="update" ) {
				echo 'Profile does not exist, check "add new Customer" box to add as new customer';
			} else {
				echo 'Customer records already exists for ' . $postdata->email . '. Updates can be made using My Profile.'; 
			}
?>
