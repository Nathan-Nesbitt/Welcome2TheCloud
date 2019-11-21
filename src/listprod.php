<?php

	/* 
	Include the custom OOP file for connecting to the DB means
	we don't have to do all of the checks again. It's also better 
	because we don't carry around the username and password variables
	in every PHP script we write. 
	*/
	include 'include/db_connection.php';
	
	function getProducts($connection, $name) {
		
		if($name == "") {
			$query =  "SELECT * FROM product";
			$result = $connection-> query($query);
			return $result;

		}
		else {
			//prepared stmt functionality
			$query = $connection->prepare("SELECT * FROM product WHERE productName LIKE ?");
			$query->bind_param("s", $name);
			$query->execute();
			$result = $query->get_result();
			return $result;
			
		}
		
	} 

	function printTableProd(){

		/** Get product name to search for **/
		if (isset($_GET['productName'])){
			$name = "%".$_GET['productName']."%";
		}
		else {
			$name = "";
		};
		/* Creates and checks the connection (from db_connection) */
		$connection = createConnection();

		/* Gets the results */
		$result = getProducts($connection, $name);
	 
		if ($result->num_rows > 0) {
    		while($row = $result->fetch_assoc()) {
				
				echo "<tr><td>" . $row["productId"] . "</td><td>" . $row["productName"] . "</td><td>" . $row["productDesc"] . "</td><td>" . $row["productPrice"] . "</td><td>" .
					 "<a href='addcart.php?id= ".$row["productId"]."&name=".urlencode($row["productName"]) . "&price=".number_format($row["productPrice"],2). "'>add to cart</a>"  . 
					 "<a href='product.php?id= ".$row["productId"] . "'>PPPPPRRRRODUCTS</a>" . "</td></tr>";
						//remember to change product above back to add to cart and delete this :)
				}
			echo "</table>";	
				
		}
		else {
			echo  "<tr><td>" . "0 results" .  "</td></tr>" ;
		}
		/** Close connection **/
		$connection->close();
	}
?>

<!DOCTYPE html>
<html>

<head>
<meta charset='UTF-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0' />
        <title>Products - Welcome2TheCloud</title>
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
			<ul id="navbar-ul" class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" href="/">Homepage<span class="sr-only"></span></a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="listprod.php">Products</a>
				</li>
				<li class="nav-item">
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
			<div class="col-lg-16 col-md-12 col-sm-12" align="center">
				<div class="slide-content">
					<h1>Search for the products you want to buy:</h1>
					<form method="get" action="listprod.php">
						<input type="text" name="productName" size="50">
						<input type="submit" value="Submit"><input type="reset" value="Reset"> (Leave blank for
						all products)
					</form>
					<table>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						<tr>
					<?php
						/* Runs the main function to print the tables */
						printTableProd();
					?>
				</div>
			</div>
		</div>
	</div>
</body>

<<<<<<< HEAD
<!DOCTYPE html>
<html>
<head>
 <body>
	<h1>Search for the products you want to buy:</h1>
	<form method="get" action="listprod.php"> 
		<input type="text" name="productName" size="50">
		<input type="submit" value="Submit"><input type="reset" value="Reset"> (Leave blank for all products)
=======
<script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
<script>
	// Function to show the current user if they are logged in and change navbar //
>>>>>>> 4a58fdcc23bf8e85e439bdc73b68f76a5dc62c07

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