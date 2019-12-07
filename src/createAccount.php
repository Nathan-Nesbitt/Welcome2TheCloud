<?php
require_once 'include/db_connection.php';
require_once 'objects/Login.php';
require_once 'objects/Account.php';

function getUserValues() {
	/**
	 * Function to get the values from the form.
	 * Returns: List of form values, if failed/not recieved the values are null
	 */

	$fname = null; 
	$lname= null; 
	$email= null; 
	$phonenum= null;
	$address= null; 
	$city= null; 
	$state= null; 
	$postalCode= null;
	$country= null; 
	$paymentType= null;
	$paymentNumber= null;
	$paymentExpiryDate= null; 
	$userid= null; 
	$password= null;

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

	if (isset($_POST['paymentType'])){
		$paymentType = trim(htmlspecialchars($_POST['paymentType']));
	}
	
	if (isset($_POST['paymentNumber'])){
		$paymentNumber = trim(htmlspecialchars($_POST['paymentNumber']));
	}
	
	if (isset($_POST['paymentExpiryDate'])){
		$paymentExpiryDate = trim(htmlspecialchars($_POST['paymentExpiryDate']));
	}

	if (isset($_POST['userid'])){
		$userid = trim(htmlspecialchars($_POST['userid']));
	}
	
	if (isset($_POST['password'])){
		$password = trim(htmlspecialchars($_POST['password']));
	}
	
	return array($fname, $lname, $email, $phonenum, 
		$address, $city, $state, $postalCode,
		 $country, $paymentType, $paymentNumber,
		  $paymentExpiryDate, $userid, $password);
}

function mainCreateFunction() {
	/**
	 * Main function for this page, prints out all orders and all products
	 * Returns: Nothing
	 * 
	 */

	$connection = createConnection();

	list($success, $userid) = Account->createAccount($connection);

	// If the account is successfully created, log the account in //
	if ($success) {
		Login->createToken($connection, $userid);
		// These allow for the AJAX redirection on the other page //
		header("HTTP/1.1 200 OK");
		echo '{ "success": "TRUE" }';
	}
	else {
		// These allow for the AJAX redirection on the other page //
		header('Error-Message: Incorrect Field', true, 500);
		echo '{ "success": "FALSE", "Issue":"Wrong Field Value." }';
	}
	$connection->close();	
	}

header('Content-type: application/json');
mainCreateFunction();

?>