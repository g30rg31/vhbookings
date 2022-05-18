<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

if(isset($_POST['password-reset']) && $_POST['email'])
{

//Load Composer's autoloader
        require '/home/emvhmgr/vendor/autoload.php';

//Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

    require('/home/web/scred.php');

// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    } // if conn

// set status to confirmed
//        $conf_event = $_REQUEST["q"];
//      $sql = 'SELECT bookingname, customer FROM bookings WHERE id=' . $conf_event;
//      $result = mysqli_query($conn, $sql);
//      $detail = mysqli_fetch_assoc($result);

        $sql = 'SELECT id, name, rsttoken, email FROM customers WHERE email = "' . $_POST["email"] . '" LIMIT 1';
        $result = mysqli_query($conn, $sql);


        $row = mysqli_fetch_assoc($result);

	if($row)
	{
		$passCode = "";                 //make sure pincode is empty
                do {
                $passCode = genpinCode();
                $sql = 'SELECT rsttoken FROM customers WHERE rsttoken ="' . $passCode . '"';
                $result = mysqli_query($conn,$sql);
                $notdone = mysqli_num_rows($result);    //keeping generating pincodes until a unique one is found
                } while ($notdone); //do

		$expFormat = mktime(
		date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y") );
		$expDate = date("Y-m-d H:i:s",$expFormat);
        	$sqlc ='UPDATE customers SET rsttoken="' . $passCode . '", expires="' .  $expDate . '", vprof=1 WHERE id = ' . $row["id"];
	        $result = mysqli_query($conn, $sqlc);

	//Mail Server settings
	        $credfile = fopen("/home/web/email.conf","r");
	        $mailAccount=str_replace("\n","",fgets($credfile));
	        $mailPassword=str_replace("\n","",fgets($credfile));
	        fclose($credfile);

	// read in email body htmlt
		$filecontent='/var/www/public_html/mail/pwdreset.html';
		$filecon=fopen($filecontent,"r");
		$body = fread($filecon, filesize($filecontent));
		fclose($filecon);

	// Substitute variables - a bit like mail merge

		$body = str_replace("custname",$row["name"],$body);
		/*
		$body = str_replace("bookingname",$bookingname,$body);
		$body = str_replace("bookdate",$date,$body);
		$body = str_replace("timestart",$timestart,$body);
		$body = str_replace("timeend",$timeend,$body);
		$body = str_replace("numrec",$numrec,$body);
		$body = str_replace("pubdets",$pubdets,$body);
		$body = str_replace("pubcons",$pubcons,$body);
		*/
		$body = str_replace("respass",$passCode,$body);
		$body = str_replace("sendtoaddress",$_POST["email"],$body);

		try {
		    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      		//Enable verbose debug output
		    $mail->isSMTP();                                            	//Send using SMTP
		    $mail->Host       = 'poseidon.krystal.co.uk';                     	//Set the SMTP server to send through
		    $mail->SMTPAuth   = true;                                   	//Enable SMTP authentication
		    $mail->Username   = $mailAccount;                     		//SMTP username
		    $mail->Password   = $mailPassword;                               	//SMTP password
		    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         	//Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		    $mail->Port       = 587;                                    	//TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

		    //Recipients
		    $mail->setFrom('info@eastmeonvillagehall.co.uk', 'East Meon Village Hall Bookings');
		    $mail->addAddress($_POST["email"]);				 	//Add a recipient
		    $mail->addReplyTo('info@eastmeonvillagehall.co.uk', 'East Meon Village Hall');
		    $mail->addCC('info@eastmeonvillagehall.co.uk');

		    //Content
		    $mail->isHTML(true);                                  		//Set email format to HTML
		    $mail->Subject = 'Password Reset for East Meon Village Hall bookings';
		    $mail->Body    = $body;
		    $mail->AltBody = 'Copy and Paste this link in a browser to reset your password: https://emvh.co.uk/pwdreset.php?email=' . $_POST["email"] . '&reskey=' . $passcode;
		    $mail->send();

		    $sqls = 'INSERT INTO logs (type, logentry) VALUES ("USer", "Password reset email sent to ' . $_POST["email"] . '")';
		    mysqli_query($conn, $sqls);

		} catch (Exception $e) {
		    $sqle = 'INSERT INTO logs (type, logentry) VALUES ("USer", "Password reset failed for ' . $_POST["email"] . '")';
		    mysqli_query($conn, $sqle);
		}
	}
	mysqli_close($conn);

} 
header("Location:login.php");

function genpinCode() {

        $pinCode = "";
        for ($i=0; $i<12; $i++) {        // 6 digit pins
                $pinChar = rand(0,9);
                $pinCode = $pinCode . $pinChar;
                }
        return($pinCode);
}

