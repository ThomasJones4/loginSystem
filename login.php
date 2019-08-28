<?php 

include_once('includes/header.php');


displayLogin();


function displayLogin() {
	
	$registerBlock = "";
	
	if (enable_register) {
		$registerBlock = "<h1 class='uk-heading-line uk-text-center'><span>Register</span></h1>
	<a class='uk-button uk-button-default' href='register.php'>Register</a>";
	}
	
	$template = new Template;
	$template->toScreen('login', ['registerBlock' => $registerBlock]);

}


include_once('includes/footer.php');

?>