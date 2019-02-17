<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
include('vendor/autoload.php');

function sendEmail($emailToAddress, $emailToName, $Subject, $Body) {
	
	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
	try {
		//Server settings
		$mail->SMTPDebug = 2;                                 // Enable verbose debug output
		//$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = $smtpHost_name;  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = $smtpUser_name;                 // SMTP username
		$mail->Password = $smtpPassword;                           // SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = $smtpPort;                                    // TCP port to connect to

		//Recipients
		$mail->setFrom('verification@projectbin.co.uk', 'ProjectBin Site Verification');
		$mail->addAddress('Joe.User@zm7.uk', 'Joe User');     

		//Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = $Subject;
		$mail->Body    = $Body;

		$mail->send();
		//echo 'Message has been sent';
	} catch (Exception $e) {
		echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
	}
}

?>