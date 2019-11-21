<?php
require_once 'include/db_connection.php';
require_once 'login_scripts.php';


/* Function to get the values from the form */
function getUserValues() {

	if (isset($_POST['FirstName'])){
		$fname = $_POST['FirstName'];
	}
	if (isset($_POST['LastName'])){
		$lname = $_POST['LastName'];
	}
	if (isset($_POST['email'])){
		$email = $_POST['email'];
	}
	if (isset($_POST['phonenum'])){
		$phonenum = $_POST['phonenum'];
	}
	if (isset($_POST['address'])){
		$address = $_POST['address'];
	}
	if (isset($_POST['city'])){
		$city = $_POST['city'];
	}
	if (isset($_POST['state'])){
		$state = $_POST['state'];
	}
	if (isset($_POST['postalCode'])){
		$postalCode = $_POST['postalCode'];
	}
	if (isset($_POST['country'])){
		$country = $_POST['country'];
	}
	if (isset($_POST['userid'])){
		$userid = $_POST['userid'];
	}
	if (isset($_POST['password'])){
		$password = $_POST['password'];
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
	
	/* Creates a hashed password */
	$password = createHashedPassword($password);

	/* Prepares the function so we can pass in the values from the user */
	$query = $connection->prepare("INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	/* Passes the values into the query */
	$query->bind_param("sssssssssss", $fname, $lname, $email, $phonenum, $address, $city, $state, $postalCode, $country, $userid, $password);
	
	$result = $query->execute();

	/* Returns TRUE if successful, and FALSE if failed */
	return array($result, $userid);
}

/* Main function for this page, prints out all orders and all products */
function mainCreateFunction() {
		/* Creates the connection to the database */
		$connection = createConnection();
		list($success, $userid) = createAccount($connection);
		// If the account is successfully created, log the account in
		if ($success)
			createToken($connection, $userid);

	}

mainCreateFunction();

?>