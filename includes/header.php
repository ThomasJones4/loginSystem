<?php
// included on login.php, index.php
ob_start();
session_start();
$GLOBALS['errors'] = "";
include_once('authentication.php');
include_once('snippets.php');


?>
<html>
<head>
<title>Rental System</title>
<!-- UIkit CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/css/uikit.min.css" />

<!-- UIkit JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/js/uikit-icons.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php 
if ($_SESSION['auth'] && isset($_SESSION['username'])) {
	$authUsername = $_SESSION['username'];
	echo str_replace("{{username}}",$authUsername,$authNavbar); //user nav bar
} else {
	echo $navbar;
}
?>