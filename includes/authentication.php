<?php 
// INCLUDED IN header.php

include_once('database.php');
include_once('communication.php');

if (!$_SESSION['auth']) {
	// Not authenticated
	$url = $_SERVER["REQUEST_URI"];
	// substr($url, -12) != 'register.php' && substr($url, -9) != 'login.php' && substr($url, -47, 10) != 'verify.php' 
	if (!contains($url, ['register.php', 'login.php', 'verify.php'])) {
		// Not authenticated and not on a public page so redirect to login.php page
		//TODO: Make public pages an array
		header("Location: login.php");
		die("Redirecting");
	} else {
		// Not authenticated and on login.php or register.php
		if (isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])) {
			// Not authenticated, login details sent from login.php
			$uUsername = $_POST['username'];
			$uPassword = $_POST['password'];
			login($uUsername, $uPassword);
		} else if (isset($_POST['register']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
			// Not authenticated,  register details sent from register.php
			$uUsername = $_POST['username'];
			$uEmail = $_POST['email'];
			$uPassword = $_POST['password'];
			checkRegstierDetails($uUsername, $uEmail, $uPassword);
		}
	}
} else {
	// Authenticated
	if (!contains($url, ['register.php', 'login.php', 'verify.php']) ) {
		// Not authenticated and not on login.php or register.php so redirect to login.php page
		header("Location: index.php");
		die("Redirecting");
	}
}

function checkLoginDetails($uUsername, $uPassword) {
	// RETURN CODES
	// -1: incorrect password
	// 0: password correct but not email verified
	// 1: password correct and email verified
	
	$passwordAndVerified = db('checkPasswordAndVerified',['username' => $uUsername]); // Get password hash from db
	$hashedPassword = $passwordAndVerified[0];
	if (password_verify($uPassword, $hashedPassword)) {
		// Passwords Match
		if($passwordAndVerified[1] == 1) {
			// Email verified
			return 1;
		} else {
			// email not verified
			return 0;
		}
	} else {
		return -1;
	}
}

function checkRegstierDetails($uUsername, $uEmail, $uPassword) {
	$user = db('checkUserDoesNotExist',['username' => $uUsername, 'email' => $uEmail]);
	if (!empty($user)) {
		// User with provided details exists
		$GLOBALS['errors'] .= "<script>UIkit.notification({message: 'A user already exists with the provided login credentials', status: 'warning'})</script>";
	} else {
		// No user exists already, create user
		createUser($uUsername, $uEmail, $uPassword);
	}
}

function createUser($uUsername, $uEmail, $uPassword) {
	$password = password_hash($uPassword, PASSWORD_DEFAULT);
	$created = date('Y-m-d');
	db('createUser',['username' => $uUsername, 'email' => $uEmail, 'password' => $password, 'created' => $created, 'emailVerified' => 0]);
	$user = db('getUserID',['username' => $uUsername]);
	$vID = md5(rand());
	$userID = $user[0];
	echo $userID;
	echo $uEmail;
	echo $vID;
	db('verificationEmail',['user_id' => $userID, 'email' => $uEmail, 'vid' => $vID]);
	//sendEmail($emailToAddress, $emailToName, $Subject, $Body)
	$verifyMessage = "Please verify your email: https://projectbin.co.uk/p/rentalSystem/www/verify.php?vid=$vID";
	sendEmail($uEmail, $uUsername, 'Email Verification', $verifyMessage);
	$GLOBALS['errors'] .= "<script>UIkit.notification({message: 'Please verifiy your email address. A link has been sent.', status: 'success'})</script>";
	/* // Authenticated, redirect to home
	$_SESSION['auth'] = True;
	$_SESSION['username'] = $uUsername;
	header("Location: index.php");
	die(); */	
}

function login($uUsername, $uPassword) {
	$status = checkLoginDetails($uUsername, $uPassword);
	if ($status == -1) {
		// Incorrect Details
		$GLOBALS['errors'] = "<script>UIkit.notification({message: 'The provided credentials are incorrect', status: 'warning'})</script>";
	} else if ($status == 0){
		// correct but not verified
		$GLOBALS['errors'] .= "<script>UIkit.notification({message: 'Please verifiy your email address. A link has already sent.', status: 'success'})</script>";
	} else if ($status == 1){
		// Correct details and verified, redirect to home
		$_SESSION['auth'] = true;
		$_SESSION['username'] = $uUsername;
		header("Location: index.php");
		die("Redirecting");
	}
}


//
// CODE FROM zombat@STACKOVERFLOW
// https://stackoverflow.com/a/2124557
//
function contains($str, array $arr)
{
    foreach($arr as $a) {
        if (stripos($str,$a) !== false) return true;
    }
    return false;
}

?>