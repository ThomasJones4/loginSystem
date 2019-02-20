<?php// INCLUDED IN authentication.phpfunction initPDO(){		include('config.php');		$dbh = null;	try {	  $dbh = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);	} catch (PDOException $e) {	  echo "Error!: " . $e->getMessage() . "<br/>";	  die();	}	return $dbh;}	function db($task, $variables = NULL) {		$dbh = initPDO();		switch($task) {		case "checkUser":			$statement = $dbh->prepare("SELECT password, emailVerified, isAdmin FROM users WHERE username = :username OR email = :username LIMIT 1");			$statement->execute($variables);			$user = $statement->fetch();			return $user;			break;		case "checkUserDoesNotExist":			$statement = $dbh->prepare("SELECT user_id FROM users WHERE username = :username OR email = :email");			$statement->execute($variables);			$user = $statement->fetch();			return $user;			break;		case "createUser":			$statement = $dbh->prepare("INSERT INTO users VALUES (NULL, :username, :email, :password, :created, :emailVerified); SELECT user_id FROM users WHERE username = :username");			$statement->execute($variables);			break;		case "getUserID":			$statement = $dbh->prepare("SELECT user_id FROM users WHERE username = :username");			$statement->execute($variables);			$user = $statement->fetch();			return $user;			break;		case "verificationEmail":			$statement = $dbh->prepare("INSERT INTO emailVerification VALUES (:user_id, :email, :vid)");			$statement->execute($variables);			break;		case "verifyEmail":			$statement = $dbh->prepare("UPDATE users SET emailVerified = 1 WHERE email = :email");			$statement->execute($variables);			break;		case "getEmailFromVid":			$statement = $dbh->prepare("SELECT email FROM emailVerification WHERE vid = :vid");			$statement->execute($variables);			$user = $statement->fetch();			return $user;			break;		case "getAllUsers":			$statement = $dbh->prepare("SELECT * FROM users");			$statement->execute($variables);			$user = $statement->fetchAll();			return $user;			break;		default:			echo "Task '$task' not found";	}}?>