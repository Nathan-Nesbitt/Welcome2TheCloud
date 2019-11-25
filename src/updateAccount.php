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
		$newUserid = $_POST['userid'];
	}
	if (isset($_POST['password'])){
		$password = $_POST['password'];
    }
    if (isset($_POST['newPassword'])){
		$newPassword = $_POST['newPassword'];
	}
	
	return array($fname, $lname, $email, $phonenum, $address, $city, $state, $postalCode, $country, $newUserid, $password, $newPassword);
}

function createHashedPassword($password){
	$options = ['cost'=>11];
	return password_hash($password, PASSWORD_DEFAULT, $options);
}

function updatePassword($connection, $password, $newPassword, $userId) {
    
    // If the old password doesn't match DB, quit //
    if(!login($connection, $userId, $password))
        return FALSE;
    
    $newPassword = createHashedPassword($newPassword);
    $query = $connection->prepare("UPDATE customer SET password=? WHERE userid =?");
    $query->bind_param("ss", $newPassword, $userId);
    $result = $query->execute();
    return $result;
}

/* Function to create the user in the database */
function updateUserInfo($connection, $fname, $lname, $email, $phonenum, $address, $city, $state, $postalCode, $country, $newUserid, $oldUserid) {
	/* Gets the list of values from the function */
	$query = $connection->prepare("UPDATE customer SET firstName=?, lastName=?, email=?, phonenum=?, address=?, city=?, state=?, postalCode=?, country=?, userid=? WHERE userid =?");
    $query->bind_param("sssssssssss", $fname, $lname, $email, $phonenum, $address, $city, $state, $postalCode, $country, $newUserid, $oldUserid);
    $result = $query->execute();
    return $result;

}

/* Main function for this page, prints out all orders and all products */
function mainCreateFunction() {
		/* Creates the connection to the database */
		$connection = createConnection();
        
        list ($fname, $lname, $email, $phonenum, $address, $city, $state, $postalCode, $country, $newUserid, $password, $newPassword) = getUserValues();
    
        // Checks to see if the user is logged in as a valid user, quits if they aren't // 
        if(!checkToken($connection))
            return FALSE;

        // Gets the userID from the cookie
        $cookie = $_COOKIE["loggedIn"];
        $userId = explode(":", $cookie)[0];

        // Checks to see if the password variable was set (updating password not customer info)
        if (!$password){
            $updateUserInfoSuccess = updateUserInfo($connection, $fname, $lname, $email, $phonenum, $address, $city, $state, $postalCode, $country, $newUserid, $userId);
        }
        else {
            $updatePasswordSuccess = updatePassword($connection, $password, $newPassword, $userId);
        }
        
		// If the account userID is successfully changed, reload the account with the new token
        if ($updateUserInfoSuccess && $userId != $newUserid){
            removeSessionToken($connection);
            createToken($connection, $newUserid);
        }

        /* Closes the connection to the database */
		$connection->close();

	}

mainCreateFunction();

?>