<?php
require_once 'include/db_connection.php';
require_once 'login_scripts.php';


/* Function to get the values from the form */
function getUserValues() {
    $productName = NULL;
    $productPrice = NULL;
    $productImageURL = NULL;
    $productImage = NULL;
    $productImageImage = NULL;

	if (isset($_POST['productName'])){
		$productName = $_POST['productName'];
	}
	if (isset($_POST['productPrice'])){
		$productPrice = $_POST['productPrice'];
	}
	if (isset($_POST['productDesc'])){
		$productDesc = $_POST['productDesc'];
	}
	if (isset($_POST['categoryId'])){
		$categoryId = $_POST['categoryId'];
    }
    if (isset($_FILES['productImageURL'])){
		$productImageURLImage = $_FILES['productImageURL'];
    }
    if (isset($_FILES['productImage'])){
        $productImageImage = $_FILES['productImage'];
    }
	
	return array($productName, $productPrice, $productDesc, $categoryId, $productImageURLImage, $productImageImage);
}

/* Function to create the user in the database */
function createAccount($connection) {
	/* Gets the list of values from the function */
	list ($productName, $productPrice, $productDesc, $categoryId, $productImageURLImage, $productImageImage) = getUserValues();

    $photoDir = "images/";
    
    // Since we will run into issues with file conflicts, 
    // the image url becomes an md5 hash with the current time
    if($productImageURLImage){
        $productImageURL =  $photoDir . time() . $productImageURLImage['name'];
        move_uploaded_file($productImageURLImage["tmp_name"] , $productImageURL);
    }
    
    if($productImageImage){
        $productImage = $photoDir . time() . $productImageImage["name"];
        $productImageImage = file_get_contents($productImage);
    }

	/* Prepares the function so we can pass in the values from the user */
	$query = $connection->prepare("INSERT INTO product (productName, productPrice, productImageURL, productImage, productDesc, categoryId) VALUES (?, ?, ?, ?, ?, ?)");
	/* Passes the values into the query */
	$query->bind_param("sisssi", $productName, $productPrice, $productImageURL, $productImageImage, $productDesc, $categoryId);
    

	$result = $query->execute();

	/* Returns TRUE if successful, and FALSE if failed */
	return array($result);
}

/* Main function for this page, prints out all orders and all products */
function mainCreateFunction() {

        /* Creates the connection to the database */
        $connection = createConnection();
        
        // Checks to see if the person is logged in and quits if they're not //
        $loggedIn = checkToken($connection);
        if (!$loggedIn){
            return FALSE;
        }

		list($success) = createAccount($connection);
		// If the account is successfully created, log the account in
		if ($success)
			echo "Success!";

	}

mainCreateFunction();

?>