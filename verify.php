<?php

include_once('includes/header.php');

if (isset($_GET['vid'])) {
	$vid = $_GET['vid'];
	$email = db('getEmailFromVid', ['vid' => $vid]);
	db('verifyEmail',['email' => $email[0]]);
	$GLOBALS['errors'] .= "<script>UIkit.notification({message: 'Your email address was verified', status: 'success'})</script>";
}

?>