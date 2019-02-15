<?php 

include_once('includes/header.php');

?>
<div class="uk-container uk-align-center uk-position-center">
	<h1 class="uk-heading-line uk-text-center"><span>Register</span></h1>
	<form action="register.php" method="post">
		<div class="uk-margin">
			<div class="uk-inline">
				<span class="uk-form-icon" uk-icon="icon: user"></span>
				<input class="uk-input" type="text" name="username">
			</div>
		</div>    
		
		<div class="uk-margin">
			<div class="uk-inline">
				<span class="uk-form-icon" uk-icon="icon: mail"></span>
				<input class="uk-input" type="text" name="email">
			</div>
		</div>

		<div class="uk-margin">
			<div class="uk-inline">
				<span class="uk-form-icon" uk-icon="icon: lock"></span>
				<input class="uk-input" type="password" name="password">
			</div>
		</div>
		<input hidden type="text" name="register" value="true">
		<input class="uk-button uk-button-default" type="submit" value="Register">
	</form>
</div>

<?php 

include_once('includes/footer.php');

?>
