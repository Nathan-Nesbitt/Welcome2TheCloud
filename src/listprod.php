<?php

	include 'include/db_connection.php';
	include 'objects/Product.php';
	include 'objects/Category.php';

	//function for displaying products by category via drop downs
	function displayProductByCategory(){

		$connection = createConnection();

		$result = Category::getCategories($connection);

		while($row = $result->fetch_assoc()){
			$cat = $row["categoryName"];
			$resultTwo = Product::getProductsByCategory($connection, $cat);
			echo '<div class="dropdown">
				<button class="dropbtn">' . $cat . '</button>
				<div class="dropdown-content">';
			while($row = $resultTwo->fetch_assoc()){
				echo "<a href='product.php?id= " .
				$row["productId"] . "'>" .
				$row["productName"] . "</a>";
			}
			echo'</div></div>';

		}
		$connection->close();
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
		$result = Product::getProducts($connection, $name);
	 
		if ($result->num_rows > 0) {
    		while($row = $result->fetch_assoc()) {
				
				echo "<tr><td class='d-none d-sm-block'>" . $row["productId"] . "</td><td><a href='product.php?id= "
					. $row["productId"] . "'>" . $row["productName"] . 
					"</a></td><td class='d-none d-sm-block'>" . $row["productDesc"] . 
					"</td><td>" . $row["productPrice"] . "</td><td>" .
					"<a href='addcart.php?id= ".$row["productId"] . "&name=" . 
					urlencode($row["productName"]) . "&price=".number_format($row["productPrice"],2). 
					"'><button class='btn btn-success mb-1'>add to cart</button></a></td></tr>";
						
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
	<link rel="stylesheet" href="stylesheets/shop.css">
	<link rel="stylesheet" href="stylesheets/listprod.css">
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
				<li class="nav-item active">
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
			<div class="col-lg-16 col-md-16 col-sm-16 center">
				<div class="slide-content">
					<div class="col-lg-6 col-md-6 col-sm-16 col-xs-16 form-group mx-auto text-center justify-content-center">
						<form method="get" action="listprod.php">
						<h2> Search for a product</h2>
							<div class="form-group">
								<input type="text"
									placeholder="Enter the product you want here...(Leave blank for all products)"
									class="form-control" name="productName">
							</div>
							<div class="form-group">
								<input type="submit" class="btn btn-primary mb-2" value="Submit">
								<input type="reset" class="btn btn-primary mb-2" value="Reset">
							</div>
						</form>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-16">
						<table class="table">
							<tr>
								<th class="d-none d-sm-block">ID</th>
								<th>Name</th>
								<th class="d-none d-sm-block">Description</th>
								<th>Price</th>
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
	</div>
	</div>
	<div>


		<footer class="container mt-12">
			<div class="row">
				<div class="col">
					<p class="text-center">View the code at <a
							href="https://github.com/Nathan-Nesbitt/Welcome2TheCloud">Welcome2TheCloud</a></p>
				</div>
			</div>
		</footer>
</body>

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
</script>