<?php// INCLUDED IN authentication.phpfunction initPDO(){		$dbh = null;	try {		$host_name = host_name;		$user_name = user_name;		$password = password;		$database = database;	  $dbh = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);	} catch (PDOException $e) {	  echo "Error!: " . $e->getMessage() . "<br/>";	  die();	}	return $dbh;}	function db($task, $variables = NULL, $allowEmpty = 0) {	$results = getResults($task, $variables);	if(empty($results) && $allowEmpty = 0){		$_SESSION['notify'] = Array("No results where found",'warning');	} else {		return $results;	}}function getResults($task, $variables = NULL) {		$dbh = initPDO();	$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );		switch($task) {		case "checkUser":			$statement = $dbh->prepare("SELECT password, emailVerified, isAdmin FROM users WHERE username = :username OR email = :username LIMIT 1");			$statement->execute($variables);			$user = $statement->fetch();			return $user;			break;		case "checkUserDoesNotExist":			$statement = $dbh->prepare("SELECT user_id FROM users WHERE username = :username OR email = :email");			$statement->execute($variables);			$user = $statement->fetch();			return $user;			break;		case "createUser":			$statement = $dbh->prepare("INSERT INTO users VALUES (NULL, :username, :email, :password, :created, :emailVerified, 0);");			$statement->execute($variables);			break;		case "getUserID":			$statement = $dbh->prepare("SELECT user_id FROM users WHERE username = :username");			$statement->execute($variables);			$user = $statement->fetch();			return $user;			break;		case "verificationEmail":			$statement = $dbh->prepare("INSERT INTO emailVerification VALUES (:user_id, :email, :vid)");			$statement->execute($variables);			break;		case "verifyEmail":			$statement = $dbh->prepare("UPDATE users SET emailVerified = 1 WHERE email = :email");			$statement->execute($variables);			break;		case "getEmailFromVid":			$statement = $dbh->prepare("SELECT email FROM emailVerification WHERE vid = :vid");			$statement->execute($variables);			$user = $statement->fetch();			return $user;			break;		case "getAllUsers":			$statement = $dbh->prepare("SELECT * FROM users");			$statement->execute($variables);			$user = $statement->fetchAll();			return $user;			break;		case "makeUserAdmin":			$statement = $dbh->prepare("UPDATE users SET isAdmin = 1 WHERE user_id = :user_id");			$statement->execute($variables);			break;		case "deleteUser":			break;					// BELOW ARE APP SPECIFIC COMMANDS						case "addHouse":			$statement = $dbh->prepare("INSERT INTO `houses` (`houseID`, `houseName`, `houseAddress`, `houseCapacity`, `houseAddedByUserID`) VALUES (NULL, :houseName, :houseAddress, :houseCapacity, :houseAddedByUserID);");			$statement->execute($variables);			return $dbh->lastInsertId();			break;				case "updateHouse":			$statement = $dbh->prepare("UPDATE `houses` SET `houseName` = :houseName, `houseAddress` = :houseAddress, `houseCapacity` = :houseCapacity WHERE `houses`.`houseID` = :houseID;");			$statement->execute($variables);			break;					case "addUserHouse":			$statement = $dbh->prepare("INSERT INTO `userhouse` (`userHouseID`, `userID`, `houseID`, `movedInDate`) VALUES (NULL, :userID, :houseID, :movedInDate);");			$statement->execute($variables);			return $dbh->lastInsertId();			break;					case "getUserHouses":			$statement = $dbh->prepare("SELECT `houses`.`houseID`, `houseName`, `houseAddress` FROM `userhouse` , `houses` WHERE `userhouse`.`houseID` = `houses`.`houseID` and `userhouse`.`userID` = :userID ");			$statement->execute($variables);			return $statement->fetchAll();			break;				case "getUserHouse":			$statement = $dbh->prepare("SELECT `houseID`, `houseName`, `houseAddress`, `houseCapacity` FROM `houses` WHERE `houses`.`houseID` = :houseID ");			$statement->execute($variables);			return $statement->fetch();			break;				case "checkUserHouseOwnership":			$statement = $dbh->prepare("SELECT count(*) FROM `userhouse` WHERE `userhouse`.`userID` = :userID and `userhouse`.`houseID` = :houseID");			$statement->execute($variables);			return $statement->fetch();			break;					case "getUserHouseCount":			$statement = $dbh->prepare("SELECT count(*) FROM `userhouse` WHERE `userhouse`.`userID` = :userID");			$statement->execute($variables);			return $statement->fetch();			break;						case "getUserBills":			$statement = $dbh->prepare("SELECT * FROM `bills`, `userbill`, `houses` WHERE `bills`.`houseID` = `houses`.`houseID` and  `bills`.`billID` = `userbill`.`billID` and `userbill`.`userID` = :userID");			$statement->execute($variables);			return $statement->fetchAll();			break;							case "getHouseBills":			$statement = $dbh->prepare("SELECT * FROM `bills`, `houses` WHERE `bills`.`houseID` = `houses`.`houseID` and `bills`.`houseID` = :houseID");			$statement->execute($variables);			return $statement->fetchAll();			break;						case "getHousemates":			$statement = $dbh->prepare("SELECT * FROM `users`, `userhouse` WHERE `users`.`user_id` = `userhouse`.`userID` and `userhouse`.`houseID` = :houseID");			$statement->execute($variables);			return $statement->fetchAll();			break;					case "getUserBillsCount":			$statement = $dbh->prepare("SELECT count(*) FROM `bills`, `userbill` WHERE `bills`.`billID` = `userbill`.`billID` and `userbill`.`userID` = :userID");			$statement->execute($variables);			return $statement->fetch();			break;					case "addBill":			$statement = $dbh->prepare("INSERT INTO `bills` (`billID`, `billStart`, `billEnd`, `billRrule`, `billDesc`, `billAmount`, `billFixed`, `billAddedByUserID`, `houseID`) VALUES (NULL, :billStart, :billEnd, :billRrule, :billDesc, :billAmount, :billFixed, :billAddedByUserID, :houseID)");			$statement->execute($variables);			return $dbh->lastInsertId();			break;					case "addUserBill":			$statement = $dbh->prepare("INSERT INTO `userbill` (`userBillID`, `userID`, `billID`, `dateAdded`) VALUES (NULL, :userID, :billID, :dateAdded) ");			$statement->execute($variables);			return $dbh->lastInsertId();			break;					case "getHouseIDFromSearch":			$statement = $dbh->prepare("SELECT `houses`.`houseID` FROM `houses`, `userhouse` WHERE `userhouse`.`houseID` = `houses`.`houseID` and `userhouse`.`userID` = :userID and`houseName` = :houseName and `houseAddress` = :houseAddress");			$statement->execute($variables);			return $statement->fetch();			break;						//TODO: update db case names so getuserbill -> getbill as getuserbill is possibly also a function of table userbill		case "getUserBill":			$statement = $dbh->prepare("SELECT * FROM `bills`, `userbill` WHERE `bills`.`billID` = `userbill`.`billID` and `userbill`.`userID` = :userID and `bills`.`billID` = :billID");			$statement->execute($variables);			return $statement->fetch();			break;					case "updateBill":			$statement = $dbh->prepare("UPDATE `bills` SET `billStart` = :billStart, `billEnd` = :billEnd, `billRrule` = :billRrule, `billDesc` = :billDesc, `billAmount` = :billAmount, `billFixed` = :billFixed WHERE `bills`.`billID` = :billID;");			$statement->execute($variables);			break;										default:			$_SESSION['notify'] = Array("Task $task not found",'success');	}	print_r($dbh->errorInfo());}function stripExtraArgs(&$args, $fields) {		foreach ($args as $passedVar => $value) {			if (!in_array($passedVar, $fields)) {				unset($args[$passedVar]);			}		}}?>