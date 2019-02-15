<?php 

include_once('includes/header.php');

?>

<div class="uk-container uk-align-center uk-position-center">
<h1 class="uk-heading-line uk-text-center"><span>Login</span></h1>
	<form action="login.php" method="post">
		<div class="uk-margin">
			<div class="uk-inline">
				<span class="uk-form-icon" uk-icon="icon: user"></span>
				<input class="uk-input" type="text" name="username">
			</div>
		</div>

		<div class="uk-margin">
			<div class="uk-inline">
				<span class="uk-form-icon" uk-icon="icon: lock"></span>
				<input class="uk-input" type="password" name="password">
			</div>
		</div>
		<input hidden type="text" name="login" value="true">
		<input class="uk-button uk-button-default" type="submit" value="Login">
	</form>
	<h1 class="uk-heading-line uk-text-center"><span>Register</span></h1>
	<button class="uk-button uk-button-default" href="register.php">Register</button>
</div>
<?php 

include_once('includes/footer.php');

?>