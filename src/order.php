<?php

include 'include/db_connection.php';
include 'login_scripts.php';

/* Function to check to see if customer exists */

function getOrderData($connection) {

	/** 
	 * 
	 * Get userID, password, and products from the posted page 
	 * Returns list(userID, password, list of products) if successful, FALSE for any if failed
	 * 
	 * **/

	$userid = null;
	$password = null;
	$productList = null;
	
	if(isset($_POST['Username'])){
		$userid = trim(htmlspecialchars($_POST['Username']));
	}
	if(isset($_POST['password'])){
		$password = trim(htmlspecialchars($_POST['password']));
	}
	session_start();
	if (isset($_SESSION['productList'])){
		$productList = $_SESSION['productList'];
	}
	
	return array($userid, $password, $productList);
	
}

function checkUserInput($connection, $userid, $password, $productList) {
	
	/**
	 * Function that checks the user input to see if userID exists,
	 * password matches for that user, and that the product list exists
	 * 
	 * Returns: FALSE if any of these fail and prints to screen. TRUE if no error.
	 * 
	 * **/

	$rightPassword = login($connection, $userid, $password);
	if ($rightPassword == FALSE) {
		echo "<h3>Wrong Password or Username</h3>";
		return FALSE;
	}

	if(is_null($productList)){
		echo "<h3>No Ordered Data</h3>";
		return FALSE;
	}
	
	return TRUE;
}

function getCustomerInfo($connection, $userid) {
	/**
	 * Gets all customer info for some user based on their userID
	 * 
	 * Returns: Result from query.
	 */

	$getCustomerInfo = $connection->prepare("SELECT customerId, firstName, lastName, email, phonenum, address, city, state, postalCode, country FROM customer WHERE userid=?");
	$getCustomerInfo->bind_param("s", $userid);
	$getCustomerInfo->execute();
	$result = $getCustomerInfo->get_result()->fetch_assoc();

	return $result;
}

function createOrderSummary($connection, $userid) {
	/**
	 * Creates a new order for a certain user based on their username
	 * 
	 * Returns: orderId
	 */
	$customerInfo = getCustomerInfo($connection, $userid);
	
	$orderDate = date('Y-m-d H:i:s');

	$orderSummaryQuery = $connection->prepare("INSERT INTO ordersummary (customerId, totalAmount, orderDate) VALUES (?, 0, ?)");
	$orderSummaryQuery->bind_param("is", $customerInfo["customerId"], $orderDate);
	$orderSummaryQuery->execute();

	/* Gets the newly created order ID from the ordersummary table */
	$orderId = $orderSummaryQuery->insert_id;

	return $orderId;
}

function addProductsToCart($connection, $productList, $orderId) {
	/**
	 * Adds all products to a cart using the productlist and orderId
	 * 
	 * Returns: None
	 */
	foreach ($productList as $product) {
		$price = doubleval($product['price']);
		$totalPrice = doubleval($price) * doubleval($product['quantity']);
		$productID = intval($product['id']);
		$OrderProductInsert = $connection->prepare("INSERT INTO orderproduct VALUES (?, ?, ?, ?)");
		$OrderProductInsert->bind_param("iiid", $orderId, $productID, $product['quantity'], $product['price']);
		$OrderProductInsert->execute();
	}

	/* Updating the total for the ordersummary */
	$updateOrderTotalQuery = $connection->prepare("UPDATE ordersummary SET totalAmount=? WHERE orderId=?");
	$updateOrderTotalQuery->bind_param("ii", $totalPrice, $orderId);
	$updateOrderTotalQuery->execute();
}

/** Save order information to database**/
function saveOrderData($connection){

	/**
	 * Calls all required functions to load the data into the database
	 */
	
	list($userid, $password, $productList) = getOrderData($connection);

	$success = checkUserInput($connection, $userid, $password, $productList);
	if(!$success)
		return FALSE;

	$orderId = createOrderSummary($connection, $userid);

	addProductsToCart($connection, $productList, $orderId);
	
	return $orderId;
}

function printOrder() {
	$connection = createConnection();
	$orderId = saveOrderData($connection);

	if(!$orderId)
		return FALSE;

	$getInfo = $connection->prepare("SELECT * FROM ordersummary O, customer C WHERE O.customerId = C.customerId AND O.orderId=?");
	$getInfo->bind_param("i", $orderId);
	$getInfo->execute();
	$result = $getInfo->get_result()->fetch_assoc(); 

	$getOrderInfo = $connection->prepare("SELECT * FROM orderproduct, product WHERE orderId = ? AND orderproduct.productId = product.productId");
	$getOrderInfo->bind_param("i", $orderId);
	$getOrderInfo->execute();
	$orderInfo = $getOrderInfo->get_result();

	$custId = $result["customerId"];
	$getPaymentInfo = $connection->prepare("SELECT paymentType, paymentNumber FROM paymentmethod WHERE customerId=?");
	$getPaymentInfo->bind_param("i", $custId);
	$getPaymentInfo->execute();
	$paymentInfo = $getPaymentInfo->get_result()->fetch_assoc();

	echo "<div style='float: left; text-align:left'>
		<h2>Your total is $".$result["totalAmount"]."</h2>";
	echo "<h2>Your order reference number is: " . $result["orderId"] . "</h2>";
	echo "<h2>Customer ID: " . $result["customerId"] ."</h2>";
	echo "<h2>Customer Name: " . $result["firstName"] . " " . $result["lastName"] . "</h2>";
	echo "<h2>Shipping to: " . $result["address"] . ", " . $result["city"] . ", " . $result["state"] . ", " . $result["country"] . "</h2>";
	if ($paymentInfo["paymentType"] !== '') {
		echo "<h2>Payment Type: " . $paymentInfo["paymentType"] . "</h2>";
	}
	if (strlen($paymentInfo["paymentNumber"]) > 12) {
		echo "<h2>Payment Number: " . "************" . substr($paymentInfo["paymentNumber"], 12) . "</h2>";
	}
	echo "</div>";

	echo '
				<table class="table"">
				<tr>
					<th scope="col">Product Id</th>
                    <th scope="col">Quantity</th>
					<th scope="col">Price</th>
					<th scope="col">Product Name</th>
				</tr>';

	while ($row = $orderInfo->fetch_assoc()) {
		echo '<tr>
						<td>'.$row["productId"].'</td>
						<td>'.$row["quantity"].'</td>
						<td>$'.$row["price"].'</td>
						<td>'.$row["productName"].'</td>
					</tr>';
	}
	echo "</table>";


	$_SESSION['productList'] = null;

	$connection->close();

}


?>
<!DOCTYPE html>
<html>

<head>
	<meta charset='UTF-8' />
	<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0' />
	<title>Orders - Welcome2TheCloud</title>
	<link rel="icon" type="image/png" href="images/Welcome2TheCloud.png" type="image/x-icon">
	<link rel="stylesheet" href="shop.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
		integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
		integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
		integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
	</script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
		integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
	</script>
</head>

<body>
	<nav class="navbar sticky-top navbar-expand-lg navbar-light">
		<img alt="Brand" src="images/Welcome2TheCloud.png" style="width: 50px">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
			aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul id="navbar-ul" class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" href="/">Homepage<span class="sr-only"></span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="listprod.php">Products</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="listorder.php">Orders</a>
				</li>
				<li class="nav-item">
					<a id="login-nav" class="nav-link" href="login.html">Login</a>
				</li>
			</ul>
		</div>
	</nav>
	<div class="container-fluid">
		<div class="row" id="Homepage">
			<div class="col-lg-12 col-md-12 col-sm-12" align="center">
				<div class="slide-content">
					<?php printOrder(); ?>
				</div>
			</div>
		</div>
	</div>
	<footer class="container mt-12">
		<div class="row">
			<div class="col">
				<p class="text-center">View the code at <a href="https://github.com/Nathan-Nesbitt/Welcome2TheCloud">Welcome2TheCloud</a></p>
			</div>
		</div>
	</footer>
</body>

<script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
<script>
	// Function to show the current user if they are logged in and change navbar //

	function checkUser() {
		var cookieExists = Cookies.get("loggedIn");
		if (cookieExists) {

			// Changes out the login for the Customer Page Navbar Button //
			cookieExists = cookieExists.split(':');
			// Gets the login element //
			var loginElement = document.getElementById("login-nav");
			// Changes the href and the name so it says the logged in users name
			loginElement.href = 'customer.php';
			loginElement.innerHTML = cookieExists[0];

			// Add Admin Navbar Button //
			newLi = '<li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>';
			$("#navbar-ul").append(newLi);

			// Add the logout navbar button //
			newLi = '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
			$("#navbar-ul").append(newLi);
		}
	}
	checkUser();
</script>

<html>