<?php

//echo empty($GLOBALS['errors'])? "<!-- No Errors -->" : $GLOBALS['errors'];

if (isset($_SESSION['notify'])) {
	$message = $_SESSION['notify'][0];
	$status = $_SESSION['notify'][1];
	echo "<script>UIkit.notification({message: '$message', status: '$status'})</script>";
	unset($_SESSION['notify']);
} else {
	echo "<!-- No Errors -->";
}
?>

</body>
</html>