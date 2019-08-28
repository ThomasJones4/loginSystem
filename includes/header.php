<?php

ob_start();
session_start();
// load authentication module
include_once('config.php');
include_once('authentication.php');
include_once('Template.php');
// load site snippets
//TODO: Create alternative to using site snippets. Possibly templates
include_once('snippets.php');


$template = new Template;
$template->toScreen('header', ['base_url' => base_url, 'site_name' => site_name]);

displayNavBar();

function displayNavBar() {
	
	include_once('snippets.php');
	
	if ($_SESSION['auth'] && isset($_SESSION['username']) && isset($_SESSION['admin'])) {
		//logged in nav bar (Admin user)
		$authUsername = $_SESSION['username'];
		//echo str_replace("{{username}}",$authUsername,$authAdminNavBar); //user nav bar
		echo generateNavBar("admin");
	} else if ($_SESSION['auth'] && isset($_SESSION['username'])) {
		//logged in nav bar (Normal user)
		$authUsername = $_SESSION['username'];
		//echo str_replace("{{username}}",$authUsername,$authNavBar); //user nav bar
		echo generateNavBar("user");
	} else {
		//Defualt nav bar (Not logged in)
		//echo $navBar;
		echo generateNavBar("notAuthed");
	}
}

function generateNavBar($type) {
	
	if (isset($_SESSION['username'])) {
		$adminLinks = [
			//["l", "Users","admin/users.php"]
		];
		
		$userLinks = [
			["r", "Logged in as: ".$_SESSION['username'],"logout.php"],
			["l", "Home","index.php"]
		];
		
		}
	$notAuthedLinks = [
		["r", "login","login.php"],
	];
	
	$html = "";
	
	$linksToBeDisplayed = [];
	
	
	
	if($type == "admin"){
		$linksToBeDisplayed = array_merge($adminLinks, $userLinks);
	} else if ($type == "user") {
		$linksToBeDisplayed = array_merge($userLinks);
	} else {
		$linksToBeDisplayed = array_merge($notAuthedLinks);
	}
	
	
	$displayLeft = "";
	$displayRight = "";
	
	foreach ($linksToBeDisplayed as $navLink) {
		$link = $navLink[1];
		//$currentURL = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		$url = base_url.$navLink[2];
		switch($navLink[0]) {
			case "l":
			//display left
				$displayLeft .= "<li><a href='$url'>$link</a></li>";
			break;
			case "r":
			//display right
				$displayRight .= "<li><a href='$url'>$link</a></li>";
			break;
		}
	}
	
	return "<nav class='uk-navbar-container' uk-navbar><div class='uk-navbar-left'>
    <ul class='uk-navbar-nav'>" . $displayLeft . "</ul></div><div class='uk-navbar-right'>
    <ul class='uk-navbar-nav'>" . $displayRight . "</ul></div></nav>";
	
	
	
}

?>