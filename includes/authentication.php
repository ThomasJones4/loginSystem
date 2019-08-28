<?php 
// INCLUDED IN header.php

include_once('database.php');
include_once('communication.php');

$url = basename($_SERVER["REQUEST_URI"]);

if (!isset($_SESSION['auth'])) {
	$_SESSION['auth'] = false;
}

if (!$_SESSION['auth']) {
	// Not authenticated
	if (!contains($url, public_pages)) {
		// Not authenticated and not on a public page so redirect to login.php page
		$_SESSION['notify'] = Array('Please login','warning');
		header("Location: ".base_url."/login.php");
		die("Redirecting");
	} else {
		// Not authenticated and on login.php, register.php or verify.php
		if (isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])) {
			// Not authenticated, login details sent from login.php
			$uUsername = $_POST['username'];
			$uPassword = $_POST['password'];
			login($uUsername, $uPassword);
		} else if (isset($_POST['register']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
			// Not authenticated,  register details sent from register.php
			if (enable_register) {
				$uUsername = $_POST['username'];
				$uEmail = $_POST['email'];
				$uPassword = $_POST['password'];
				checkRegstierDetails($uUsername, $uEmail, $uPassword);
			} else {
				$_SESSION['notify'] = Array('Registration is not currently enabled','warning');
				header("Location: login.php");
				die("Redirecting");
			}
		} else if (isset($_GET['vid'])) {
			try {
				$vid = $_GET['vid'];
				$email = db('getEmailFromVid', ['vid' => $vid]);
				db('verifyEmail',['email' => $email[0]]);
				$_SESSION['notify'] = Array('Your email address was verified','success');
			} catch (Exception $e) {
				$_SESSION['notify'] = Array('$e','danger');
			}
		}
	}
} else {
	// Authenticated
	if (contains($url, public_pages) ) {
		// Authenticated and on login.php, register.php or verify.php so redirect to index.php
		header("Location: ".base_url."index.php");
		die("Redirecting");
	}
}

function checkLoginDetails($uUsername, $uPassword) {
	// RETURN CODES
	// -1: incorrect password
	// 0: password correct but not email verified
	// 1: password correct and email verified
	// 2: password correct and isAdmin
	
	$user = db('checkUser',['username' => $uUsername]); // Get user info from db [0]password [1]emailVerified [2]isAdmin
	$hashedPassword = $user['password']; // was [0]
	if (password_verify($uPassword, $hashedPassword)) {
		// Passwords Match
		if ($user['isAdmin']) {
			// Is admin
			return 2;
		} else if($enable_email_verification) { // was [1]
			if ($user['emailVerified'] == 1){
				// Email verified
				return 1;
			} else {
				// email not verified
				return 0;
			}
		} else {
			return 1;
		}
	} else {
		return -1;
	}
}

function checkRegstierDetails($uUsername, $uEmail, $uPassword) {
	$user = db('checkUserDoesNotExist',['username' => $uUsername, 'email' => $uEmail],1);
	if (!empty($user)) {
		// User with provided details exists
		$_SESSION['notify'] = Array('A user already exists with the provided login credentials','warning');
	} else {
		// No user exists already, create user
		createUser($uUsername, $uEmail, $uPassword);
	}
}

function createUser($uUsername, $uEmail, $uPassword) {
	$password = password_hash($uPassword, PASSWORD_DEFAULT);
	$created = date('Y-m-d');
	db('createUser',['username' => $uUsername, 'email' => $uEmail, 'password' => $password, 'created' => $created, 'emailVerified' => 0]);
	if(enable_email_verification) {
		$user = db('getUserID',['username' => $uUsername]);
		$vID = md5(rand());
		$userID = $user[0];
		db('verificationEmail',['user_id' => $userID, 'email' => $uEmail, 'vid' => $vID]);
		//sendEmail($emailToAddress, $emailToName, $Subject, $Body)
		$base_url = base_url;
		$verifyMessage = "Please verify your email: $base_url/verify.php?vid=$vID";
		sendEmail($uEmail, $uUsername, 'Email Verification', $verifyMessage);
		$_SESSION['notify'] = Array('Please verifiy your email address. A link has been sent.','success');
	} else {
		$_SESSION['notify'] = Array('Account Created. You can now login','success');
	}
	header("Location: login.php");
}

function login($uUsername, $uPassword) {
	$status = checkLoginDetails($uUsername, $uPassword);
	if ($status == -1) {
		// Incorrect Details
		$_SESSION['notify'] = Array('The provided credentials are incorrect','warning');
	} else if ($status == 0){
			// correct but not verified
			$_SESSION['notify'] = Array('Please verifiy your email address. A link has already sent.','warning');
	} else if ($status == 1){
		// Correct details and verified, redirect to home
		$_SESSION['auth'] = true;
		$_SESSION['username'] = $uUsername;
		$_SESSION['userID'] = db('getUserID', ['username' => $uUsername])[0];
		header("Location: ".base_url."index.php");
		die("Redirecting");
		$_SESSION['notify'] = Array('Successfully Logged in','success');
	} else if ($status == 2) {
		// Admin
		$_SESSION['auth'] = true;
		$_SESSION['admin'] = true;
		$_SESSION['userID'] = db('getUserID', ['username' => $uUsername])[0];
		$_SESSION['username'] = $uUsername;
		
		header("Location: ".base_url.loginSuccessPage);
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