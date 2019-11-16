<?php

include 'include/db_connection.php';
include 'login_scripts.php';

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
	return FALSE;
}

/** Get customer ID and Products **/
function getOrderData($connection) {
	$custId = null;
	if(isset($_GET['customerId'])){
		$custId = $_GET['customerId'];
	}
	$password = null;
	if(isset($_GET['password'])){
		$password = $_GET['password'];
	}
	session_start();
	$productList = null;
	if (isset($_SESSION['productList'])){
		$productList = $_SESSION['productList'];
	}
	

	/* Checks to see if the customer exists */
	$customerValid = checkIfCustomer($connection, $custId);

	/* Check to be sure that the password is right */
	$rightPassword = idLogin($connection, $custId, $_GET['password']);
	
	if($customerValid == FALSE){
		echo "<h3>No Customer</h3>";
		return FALSE;
	}
	else if ($rightPassword == FALSE) {
		echo "<h3>Wrong Password</h3>";
		return FALSE;
	}
	else if(is_null($productList)){
		echo "<h3>No Ordered Data</h3>";
		return FALSE;
	}
	else {
		return array($custId, $password, $productList);
	}
	
}

/** Save order information to database**/
function saveOrderData($connection){

	list($custId, $password, $productList) = getOrderData($connection);
	
	if ($custId == FALSE)
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

	/* Updating the total for the ordersummary */
	$updateOrderTotalQuery = $connection->prepare("UPDATE ordersummary SET totalAmount=? WHERE orderId=?");
	$updateOrderTotalQuery->bind_param("ii", $totalPrice, $orderId);
	$updateOrderTotalQuery->execute();
	
	
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
	echo "<div style='float: left; text-align:left'>
		<h2>Your total is $".$result["totalAmount"]."</h2>";
	echo "<h2>Your order reference number is: " . $result["orderId"] . "</h2>";
	echo "<h2>Customer ID: " . $result["customerId"] ."</h2>";
	echo "<h2>Customer Name: " . $result["firstName"] . " " . $result["lastName"] . "</h2>
	</div>";

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
        <title>Homepage - Welcome2TheCloud</title>
        <link rel="icon" type="image/png" href="images/Welcome2TheCloud.png" type="image/x-icon">
        <link rel="stylesheet" href="shop.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
                integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
                crossorigin="anonymous">
        <link rel="stylesheet"
                href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
                integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
                integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
                integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
                crossorigin="anonymous"></script>
</head>

<body>
        <nav class="navbar sticky-top navbar-expand-lg navbar-light">
                <img alt="Brand" src="images/Welcome2TheCloud.png" style="width: 50px">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                                <li class="nav-item active">
                                        <a class="nav-link" href="#Homepage">Homepage<span class="sr-only"></span></a>
                                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="listprod.php">Products</a>
                                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="listorder.php">Orders</a>
                                </li>
                        </ul>
                </div>
        </nav>
        <div class="container-fluid">
                <div class="row" id="Homepage">
                        <div class="col-lg-12 col-md-12 col-sm-12" align="center">
                                <div class="slide-content">
									<?php 
										printOrder();
									?>				
                                </div>
                        </div>
                </div>
        </div>
        <footer class="container mt-12">
                <div class="row">
                        <div class="col">
                                <p class="text-center"><a
                                                href="https://github.com/Nathan-Nesbitt/Welcome2TheCloud">Nathan
                                                Nesbitt</a>, Copyright © 2019</p>
                        </div>
                </div>
        </footer>
</body>

<!DOCTYPE html>
<html>

