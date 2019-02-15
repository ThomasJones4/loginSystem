<?php 
// INCLUDED IN header.php

include_once('database.php');

if (!$_SESSION['auth']) {
	// Not authenticated
	$url = $_SERVER["REQUEST_URI"];
	if (substr($url, -12) != 'register.php' && substr($url, -9) != 'login.php' ) {
		// Not authenticated and not on login.php or register.php so redirect to login.php page
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
	if (substr($url, -12) == 'register.php' && substr($url, -9) == 'login.php' ) {
		// Not authenticated and not on login.php or register.php so redirect to login.php page
		header("Location: index.php");
		die("Redirecting");
	}
}

function checkLoginDetails($uUsername, $uPassword) {
	$hashedPassword = db('checkPassword',['username' => $uUsername]); // Get password hash from db
	if (password_verify($uPassword, $hashedPassword[0])) {
		// Passwords Match
		return true;
	} else {
		return false;
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
	db('createUser',['username' => $uUsername, 'email' => $uEmail, 'password' => $password, 'created' => $created]);
	// Authenticated, redirect to home
	$_SESSION['auth'] = True;
	$_SESSION['username'] = $uUsername;
	header("Location: index.php");	
}

function login($uUsername, $uPassword) {
	if (!checkLoginDetails($uUsername, $uPassword)) {
		// Incorrect Details
		$GLOBALS['errors'] = "<script>UIkit.notification({message: 'The provided credentials are incorrect', status: 'warning'})</script>";
	} else {
		// Correct details, redirect to home
		$_SESSION['auth'] = true;
		$_SESSION['username'] = $uUsername;
		header("Location: index.php");
		die("Redirecting");
	}
}

?>