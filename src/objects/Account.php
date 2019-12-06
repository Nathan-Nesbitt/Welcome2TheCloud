<?php

class Account {
	
	function createHashedPassword($password){
		$options = ['cost'=>11];
		return password_hash($password, PASSWORD_DEFAULT, $options);
	}

	function createPayment($connection, $paymentType, $paymentNumber, $paymentExpiryDate, $customerId){
		$query = $connection->prepare("INSERT INTO paymentmethod (paymentType, paymentNumber, paymentExpiryDate, customerId) VALUES (?, ?, ?, ?)");
		/* Passes the values into the query */
		$query->bind_param("sssi", $paymentType, $paymentNumber, $paymentExpiryDate, $customerId);
		$query->execute();
		
	}

	function createAccount($connection) {
		/* Gets the list of values from the function */
		list ($fname, $lname, $email, $phonenum, 
		$address, $city, $state, $postalCode,
		 $country, $paymentType, $paymentNumber,
		  $paymentExpiryDate, $userid, $password) = getUserValues();
		
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
		/* Creates a credit card entry */
		self::createPayment($connection, $paymentType, $paymentNumber, $paymentExpiryDate, $query->insert_id);
	
		/* Returns TRUE if successful, and FALSE if failed */
		return array(TRUE, $userid);
	}
}

?>