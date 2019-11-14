<?php
// Get the current list of products
function showCart() {
	session_start();
	$productList = null;
	if (isset($_SESSION['productList'])){
		$productList = $_SESSION['productList'];
		echo("<h1>Your Shopping Cart</h1>");
		echo("<table><tr><th>Product Id</th><th>Product Name</th><th>Quantity</th>");
		echo("<th>Price</th><th>Subtotal</th></tr>");

		$total =0;
		foreach ($productList as $id => $prod) {
			echo("<tr><td>". $prod['id'] . "</td>");
			echo("<td>" . $prod['name'] . "</td>");

			echo("<td align=\"center\">". $prod['quantity'] . "</td>");
			$price = $prod['price'];

			echo("<td align=\"right\">$" . number_format($price ,2) ."</td>");
			echo("<td align=\"right\">$" . number_format($prod['quantity']*$price, 2) . "</td></tr>");
			echo("</tr>");
			$total = $total +$prod['quantity']*$price;
		}
		echo("<tr><td colspan=\"4\" align=\"right\"><b>Order Total</b></td><td align=\"right\">$" . number_format($total,2) ."</td></tr>");
		echo("</table>");

		echo("<button><a href=\"checkout.php\">Check Out</a></button>");
	} else{
		echo("<H1>Your shopping cart is empty!</H1>");
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
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" href="/">Homepage<span class="sr-only"></span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="listprod.php">Products</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="listorder.php">Orders</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="login.html">Login</a>
				</li>
			</ul>
		</div>
	</nav>
	<div class="row" id="Side-Slideshow">
		<div class="col-lg-16 col-md-12 col-sm-12" align="center">
			<?php showCart(); ?>
			<button><a href="listprod.php">Continue Shopping</a></button>
		</div>
	</div>
	</div>
	<footer class="container mt-12">
		<div class="row">
			<div class="col">
				<p class="text-center"><a href="https://github.com/Nathan-Nesbitt/Welcome2TheCloud">Nathan
						Nesbitt</a>, Copyright © 2019</p>
			</div>
		</div>
	</footer>
</body>

</html>