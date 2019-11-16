<?php
require_once 'include/db_connection.php';

/* Function to get the values from the form */
function getUserValues() {

	if (isset($_GET['FirstName'])){
		$fname = $_GET['FirstName'];
	}
	if (isset($_GET['LastName'])){
		$lname = $_GET['LastName'];
	}
	if (isset($_GET['email'])){
		$email = $_GET['email'];
	}
	if (isset($_GET['phonenum'])){
		$phonenum = $_GET['phonenum'];
	}
	if (isset($_GET['address'])){
		$address = $_GET['address'];
	}
	if (isset($_GET['city'])){
		$city = $_GET['city'];
	}
	if (isset($_GET['state'])){
		$state = $_GET['state'];
	}
	if (isset($_GET['postalCode'])){
		$postalCode = $_GET['postalCode'];
	}
	if (isset($_GET['country'])){
		$country = $_GET['country'];
	}
	if (isset($_GET['userid'])){
		$userid = $_GET['userid'];
	}
	if (isset($_GET['password'])){
		$password = $_GET['password'];
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
	
	$query->execute();

	/* Returns TRUE if successful, and FALSE if failed */
	$result = $query->get_result();
	return $result;
}

/* Main function for this page, prints out all orders and all products */
function mainCreateFunction() {
		/* Creates the connection to the database */
		$connection = createConnection();
		$result = createAccount($connection);
	}

mainCreateFunction();
?>