<?php
// Get the current list of products
function showCart() {
	session_start();
	$productList = null;
	if (isset($_SESSION['productList'])){
		$productList = $_SESSION['productList'];
		echo("<div align='center'><h1>Shopping Cart</h1></div>");
		echo("<div class='row justify-content-md-center'><div class='col-sm-8'><table class='table'><tr><th class='d-none d-sm-block'>Id</th><th>Product Name</th><th>Quantity</th>");
		echo("<th class='d-none d-sm-block'>Price</th><th>Subtotal</th><th>Remove</th><th>Update</th></tr>");

		$total =0;
		foreach ($productList as $id => $prod) {
			echo("<tr><td class='d-none d-sm-block'>". $prod['id'] . "</td>");
			echo("<td>" . $prod['name'] . "</td>");

			echo('<form method="post" action="updateQuantity.php?id=' . substr($id, 1) . '">');
			echo('<td align="center"><input class = "form-control" name="new_quantity" type="number" min="1" value="' .$prod['quantity'] . '">');
			//echo("<td align=\"center\">". $prod['quantity'] . "</td>");
			$price = $prod['price'];

			echo("<td class='d-none d-sm-block' align=\"right\">$" . number_format($price ,2) ."</td>");
			echo("<td align=\"right\">$" . number_format($prod['quantity']*$price, 2) . "</td>");
			echo('<td><a href="removeCart.php?id=' . substr($id, 1) . '">&#10060;</a></td>');
			echo('<td><input type="submit" value="Update"></form></td>');
			echo("</tr></tr>");
			$total = $total +$prod['quantity']*$price;
		}
		echo("<tr><td colspan=\"4\" align=\"right\"><b>Order Total</b></td><td align=\"right\">$" . number_format($total,2) ."</td></tr>");
		echo("</table></div>");

		echo("</div><div align='center'><a href=\"checkout.php\"><button class='btn btn-primary mb-1'>Check Out</button></a></button>");
	} else{
		echo("<div align='center'><H1>Your shopping cart is empty!</H1>");
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset='UTF-8' />
	<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0' />
	<title>Cart - Welcome2TheCloud</title>
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
                                <li class="nav-item">
                                        <a id="login-nav" class="nav-link" href="login.html">Login</a>
                                </li>
                        </ul>
                </div>
        </nav>
	<div class="row">
		<div class="col-lg-12">

			<?php showCart(); ?>
				<a href="listprod.php"><button class='btn btn-primary mb-1'>Continue Shopping</button></a>
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
</html>

<script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
<script>
	// Function to show the current user if they are logged in and change navbar //

	function checkUser() {
			var cookieExists = Cookies.get("loggedIn");
			if(cookieExists){

					// Changes out the login for the Customer Page Navbar Button //
					cookieExists = cookieExists.split(':');
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
					newLi += '</li>';
					$("#navbar-ul").append(newLi);

					// Add the logout navbar button //
					newLi = '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
					$("#navbar-ul").append(newLi);
			}
	}
	checkUser();
</script>