<?php
require_once 'include/db_connection.php';
require_once 'login_scripts.php';


/* Function to get the values from the form */
function getUserValues() {

	if (isset($_POST['FirstName'])){
		$fname = trim(htmlspecialchars($_POST['FirstName']));
	}
	if (isset($_POST['LastName'])){
		$lname = trim(htmlspecialchars($_POST['LastName']));
	}
	if (isset($_POST['email'])){
		$email = trim(htmlspecialchars($_POST['email']));
		$email = filter_var($email, FILTER_VALIDATE_EMAIL);
		if($email == FALSE)
			return FALSE;
	}
	if (isset($_POST['phonenum'])){
		$phonenum = trim(htmlspecialchars($_POST['phonenum']));
	}

	if (isset($_POST['address'])){
		$address = trim(htmlspecialchars($_POST['address']));
	}

	if (isset($_POST['city'])){
		$city = trim(htmlspecialchars($_POST['city']));
	}

	if (isset($_POST['state'])){
		$state = trim(htmlspecialchars($_POST['state']));
	}

	if (isset($_POST['postalCode'])){
		$postalCode = trim(htmlspecialchars($_POST['postalCode']));
	}

	if (isset($_POST['country'])){
		$country = trim(htmlspecialchars($_POST['country']));
	}

	if (isset($_POST['userid'])){
		$userid = trim(htmlspecialchars($_POST['userid']));
	}
	
	if (isset($_POST['password'])){
		$password = trim(htmlspecialchars($_POST['password']));
	}
	
	return array($fname, $lname, $email, $phonenum, $address, $city, $state, $postalCode, $country, $userid, $password);
}

function createHashedPassword($password){
	$options = ['cost'=>11];
	return password_hash($password, PASSWORD_DEFAULT, $options);
}

/* Function to create the user in the database */
function createAccount($connection) {
	/* Gets the list of values from the function */
	list ($fname, $lname, $email, $phonenum, $address, $city, $state, $postalCode, $country, $userid, $password) = getUserValues();
	
	// If the user failed to validate //
	if ($fname == FALSE)
		return FALSE;

	/* Creates a hashed password */
	$password = createHashedPassword($password);

	/* Prepares the function so we can pass in the values from the user */
	$query = $connection->prepare("INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	/* Passes the values into the query */
	$query->bind_param("sssssssssss", $fname, $lname, $email, $phonenum, $address, $city, $state, $postalCode, $country, $userid, $password);
	
	$query->execute();

	/* Returns TRUE if successful, and FALSE if failed */
	return array(TRUE, $userid);
}

/* Main function for this page, prints out all orders and all products */
function mainCreateFunction() {
		/* Creates the connection to the database */
		$connection = createConnection();
		list($success, $userid) = createAccount($connection);
		// If the account is successfully created, log the account in
		if ($success) {
			createToken($connection, $userid);
			header("HTTP/1.1 200 OK");
			echo '{ "success": "TRUE" }';
		}
		else {
			header('Error-Message: Incorrect Field', true, 500);
			echo '{ "success": "FALSE", "Issue":"Wrong Field Value." }';
		}
		$connection->close();	
	}

header('Content-type: application/json');
mainCreateFunction();

?>