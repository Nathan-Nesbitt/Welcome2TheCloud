<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Order Processing - Welcome2TheCloud</title>
</head>
<body>

<?php

include 'include/db_connection.php';


/* Function to check to see if customer exists */

function checkIfCustomer($connection, $custId) {

	$query = $connection->prepare("SELECT * FROM customer WHERE customerId = ?");
	/* Passes the values into the query */
	$query->bind_param("i", $custId);
	
	$query->execute();
	/* Returns TRUE if successful, and FALSE if failed */
	$result = $query->get_result();

	if ($result->fetch_assoc()) {
		return TRUE;
	}
	echo "Failed to get customer";
	return FALSE;
}

/** Get customer ID and Products **/
function getGustomerID($connection) {
	$custId = null;
	if(isset($_GET['customerId'])){
		$custId = $_GET['customerId'];
	}
	session_start();
	$productList = null;
	if (isset($_SESSION['productList'])){
		$productList = $_SESSION['productList'];
	}
	/**
		Determine if valid customer id was entered
		Determine if there are products in the shopping cart
		If either are not true, display an error message
	**/
	/* Checks to see if the customer exists */
	$customerValid = checkIfCustomer($connection, $custId);

	if($customerValid && !is_null($productList)){
		return array($custId, $productList);
	}
	else {
		echo 'ERROR: Customer ID not valid or Shopping Cart Empty';
		return array(FALSE, FALSE);
	}
	
}

/** Save order information to database**/
function saveOrderData($connection){

	list($custId, $productList) = getGustomerID($connection);
	if($custId == FALSE || $productList == FALSE)
		return FALSE;
	$orderDate = date('Y-m-d H:i:s');

	$orderSummaryQuery = $connection->prepare("INSERT INTO ordersummary (customerId, totalAmount, orderDate) VALUES (?, 0, ?)");
	/* Passes the values into the query */
	$orderSummaryQuery->bind_param("is", $custId, $orderDate);
	$orderSummaryQuery->execute();

	/* Gets the newly created order ID from the ordersummary table */
	$orderId = $orderSummaryQuery->insert_id;
	
	/* For all of the elements in the cart, we add them to the database */
	foreach ($productList as $product) {
		$price = doubleval($product['price']);
		$totalPrice = doubleval($price) * doubleval($product['quantity']);
		$productID = intval($product['id']);
		$OrderProductInsert = $connection->prepare("INSERT INTO orderproduct VALUES (?, ?, ?, ?)");
		$OrderProductInsert->bind_param("iiid", $orderId, $productID, $product['quantity'], $product['price']);
		$OrderProductInsert->execute();
	}

	$updateOrderTotalQuery = $connection->prepare("UPDATE ordersummary SET totalAmount=? WHERE orderId=?");
	$updateOrderTotalQuery->bind_param("ii", $totalPrice, $orderId);
	
	$_SESSION['productList'] = null;

	$connection->close();
}

function printOrder() {
	$connection = createConnection();
	saveOrderData($connection);
}

printOrder();
	/**
	// Use retrieval of auto-generated keys.
	$sql = "INSERT INTO <TABLE> OUTPUT INSERTED.orderId VALUES( ... )";
	$pstmt = sqlsrv_query( ... );
	if(!sqlsrv_fetch($pstmt)){
		//Use sqlsrv_errors();
	}
	$orderId = sqlsrv_get_field($pstmt,0);
	**/

/** Insert each item into OrderedProduct table using OrderId from previous INSERT **/

/** Update total amount for order record **/

/** For each entry in the productList is an array with key values: id, name, quantity, price **/

/**
	foreach ($productList as $id => $prod) {
		\\$prod['id'], $prod['name'], $prod['quantity'], $prod['price']
		...
	}
**/

/** Print out order summary **/

/** Clear session/cart **/
?>
</body>
</html>

