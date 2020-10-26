<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0' />
	<title>Order List - Welcome2TheCloud</title>
	<link rel="stylesheet" href="stylesheets/shop.css?<?php echo time(); ?>">
	<link rel="stylesheet" href="stylesheets/listOrder.css?<?php echo time(); ?>">
	<link rel="icon" type="image/png" href="images/Welcome2TheCloud.png" type="image/x-icon">
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

<?php
require_once 'include/db_connection.php';
include 'objects/Login.php';

/* Function to get all orders from the database */
function getOrders($connection, $userId) {
	$query = $connection->prepare("SELECT * FROM ordersummary O, customer C WHERE O.customerId = C.customerId and C.userId = ? ORDER BY O.orderId");
    $query->bind_param("s", $userId);
    $query->execute();
    $result =  $query->get_result();
	return $result;
}

/* Function to get all products in a given order */
function getOrderProducts($connection, $orderId) {
	/* Creates a prepared statement */
	$query = $connection->prepare("SELECT * FROM orderproduct WHERE orderId=?");
	/* Binds the prepared statement with the orderID (i is to specify integer) */
	$query->bind_param("i", $orderId);
	$query->execute();
	$result = $query->get_result();
	return $result;
}

/* Main function for this page, prints out all orders and all products */
function printTable($connection) {

		// Checks to see if the user is logged in //
		$loggedIn = Login::checkToken($connection);
		if (!$loggedIn){
			return FALSE;
        }

        // Gets the userId from the cookie //
        $cookie = $_COOKIE["loggedIn"];
        $userId = explode(":", $cookie)[0];

		$result = getOrders($connection, $userId);
		
		while($row = $result->fetch_assoc()) {
			/* Prints out the 'head values' or the order values */
			echo '<button type="button" class="order-dropdown">Order Number: '.$row["orderId"].'</button>
			<div class="order-dropdown-inner">
			<div class="order-info">
				<H5><b>Order Date: </b>'.$row["orderDate"].'</H5>
				<H5><b>Customer Id: </b>'.$row["customerId"].'</H5>
				<H5><b>Name: </b>'.$row["firstName"]." ".$row["lastName"].'</H5>
				<H5><b>Total Cost: </b>$'.number_format($row["totalAmount"], 2).'</H5>
			</div>';
			/* Creates a table for the parts of the order */
			echo '<tr>
				<table class="table"">
				<tr>
					<th scope="col">Product Id</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
				</tr>';
			
			$innerResult = getOrderProducts($connection, $row["orderId"]);
			if ($innerResult->num_rows != 0) {
				while($innerRow = $innerResult->fetch_assoc()) {
					echo '<tr>
						<td>'.$innerRow["productId"].'</td>
						<td>'.$innerRow["quantity"].'</td>
						<td>$'.$innerRow["price"].'</td>
					</tr>';
				}
			}
			echo "</tr></table></table></div>";
		}
		return TRUE;
	}
?>

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
                                <li class="nav-item">
                                        <a id="login-nav" class="nav-link" href="login.html">Login</a>
                                </li>
                        </ul>
                </div>
        </nav>
	<div class="container-fluid">
		<div class="row" id="Homepage">
			<div class="col-lg-16 col-md-12 col-sm-12" align="center">
				<div class="slide-content">
					<?php
									/* Creates and checks the connection */
									$connection = createConnection();
									/* Main Script for creating the tables */
									$result = printTable($connection);
									if (!$result)
                            			header('Location:/'); 
									/* Close the connection */
									$connection->close();
								?>
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
<script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
<script>
    // Function to show the current user if they are logged in and change navbar //

	function checkUser() {
			var cookieExists = Cookies.get("loggedIn");
			if(cookieExists){

					// Changes out the login for the Customer Page Navbar Button //
					cookieExists = cookieExists.split('%3A');
					// Gets the login element //
					var loginElement = document.getElementById("login-nav");
					loginElement.remove();
					
					// Add Admin Navbar Dropdown //
					
					newLi = '<li class="nav-item dropdown">';
					newLi += '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>';
					newLi += '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
					newLi += '<a class="dropdown-item" href="/admin.php">Admin Overview</a>';
					newLi += '<a class="dropdown-item" href="/addProduct.html">Add Product</a>';
					newLi += '<a class="dropdown-item" href="/listorder.php">All Orders</a>';
					newLi += '</li>';
					$("#navbar-ul").append(newLi);

					// Adds User Navbar Dropdown //
					newLi = '<li class="nav-item dropdown">';
					newLi += '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+cookieExists[0]+'</a>';
					newLi += '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
					newLi += '<a class="dropdown-item" href="/customer.php">User Summary</a>';
					newLi += '<a class="dropdown-item" href="/showcart.php">View Cart</a>';
                    newLi += '<a class="dropdown-item" href="/listUserOrder.php">My Orders</a>';
					newLi += '</li>';
					$("#navbar-ul").append(newLi);

					// Add the logout navbar button //
					newLi = '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
					$("#navbar-ul").append(newLi);
			}
	}
checkUser();

var dropdowns = document.getElementsByClassName("order-dropdown");
	var i;

	for (i = 0; i < dropdowns.length; i++) {
		dropdowns[i].addEventListener("click", function () {
			this.classList.toggle("active");
			var content = this.nextElementSibling;
			if (content.style.display === "block") {
				content.style.display = "none";
			} else {
				content.style.display = "block";
			}
		});
	}
</script>
</html>
