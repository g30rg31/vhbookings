<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
/*
require('/home/web/scred.php');

// Create connection
    $conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
///	echo "no sql connect";
    } // if conn
*/
	$sql = 'SELECT bookings.id, bookings.customer AS name, DATE_FORMAT(bookings.startdate, "%d %M %Y") AS date, DATE_FORMAT(bookings.startdate, "%H:%i") AS timestart,
		DATE_FORMAT( bookings.enddate, "%H:%i") AS timeend, bookings.bookingname,
		bookings.numrecs, customers.email, bookings.pincode, customers.token, bookings.public AS pubdets, bookings.publiccontact AS pubcons 
                FROM bookings INNER JOIN customers ON bookings.customer=customers.name
		where bookings.id = "' . $_REQUEST["q"] . '"';
//echo $sql . PHP_EOL;
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

//var_dump($row);
//exit;
/*
$name='George Thompson';
$bookingname = 'Test Booking';
$date='26/03/21';
$timestart="08:30";
$timeend="11:30";
$numrec="1";
*/
$row["pubdets"] == 1 ? $pubdets = "" : $pubdets = "not";
$row["pubcons"] == 1 ? $pubcons = "" : $pubcons = "not";

//Load Composer's autoloader
require '/home/emvhmgr/vendor/autoload.php';

//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);
$filename = "mail/bookingconfirm.html";
$file=fopen($filename,"r");
$body = fread($file, filesize($filename));
fclose($file);

$body = str_replace("custname",$row["name"],$body);
$body = str_replace("bookingname",$row["bookingname"],$body);
$body = str_replace("bookdate",$row["date"],$body);
$body = str_replace("timestart",$row["timestart"],$body);
$body = str_replace("timeend",$row["timeend"],$body);
$body = str_replace("numrec",$row["numrecs"],$body);
$body = str_replace("entercode",$row["pincode"]."#",$body);
$body = str_replace("pubdets",$pubdets,$body);
$body = str_replace("pubcons",$pubcons,$body);

//echo $body;

try {
    //Server settings
//require '/home/emvhmgr/mail/emailconf.php';
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'poseidon.krystal.co.uk';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'info@eastmeonvillagehall.co.uk';                     //SMTP username
    $mail->Password   = 'EMVH2304bookings';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('info@eastmeonvillagehall.co.uk', 'East Meon Village Hall Bookings');
    $mail->addAddress($row["email"], $row["name"]);     //Add a recipient
//    $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('info@eastmeonvillagehall.co.uk', 'East Meon Village Hall');
    $mail->addCC('info@eastmeonvillagehall.co.uk');
//    $mail->addBCC('bcc@example.com');

    //Attachments
//    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Booking Confirmation';
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);
//echo "sending...";
    $mail->send();
//echo $mail->Body;
//    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>
