<?php

function getCurrentItems() {
	/**
	 * Get the current list of products. 
	 * 
	 * Returns: Product List Array  
	 **/

	if(!isset($_SESSION)) { 
        session_start(); 
	}
	
	$productList = null;
	if (isset($_SESSION['productList'])) {
		$productList = $_SESSION['productList'];
	}
	else{
		// No products currently in list. Create a list.
		$productList = array();
	}
	
	return $productList;
};

function addItemToCart() {
	/**
	 * Adds the items in the product list to the cart. 
	 * 
	 * Returns: 
	 **/

	$productList = getCurrentItems();

	// Add new product selected
	// Get product information
	if(isset($_GET['id']) && isset($_GET['name']) && isset($_GET['price'])){
		$id = $_GET['id'];
		$name = $_GET['name'];
		$price = $_GET['price'];
	} else {
		header('Location: listprod.php');
	}

	// Update quantity if add same item to order again
	if (isset($productList[$id])){
		$productList[$id]['quantity'] = $productList[$id]['quantity'] + 1;
	} else {
		$productList[$id] = array( "id"=>$id, "name"=>$name, "price"=>$price, "quantity"=>1 );
	}

	$_SESSION['productList'] = $productList;
	header('Location: showcart.php');
}

function changeQuantity() {
    /**
	 * Changes the quantity of items in the order list. 
	 * 
	 * Returns: 
	 **/

    $productList = getCurrentItems();

    echo var_dump($productList);
    $new_quantity = (int) $_POST["new_quantity"];
    $id = " " . $_GET["id"];

    if ($new_quantity > 0) {
        $productList[$id]["quantity"] = $new_quantity;
    }

    $_SESSION["productList"] = $productList;

    header("Location: showcart.php");
}

function removeItemFromCart() {
    session_start();
    $productList = getCurrentItems();
    foreach ($productList as $key => $value) {
        echo "<br/>";
        echo "Key: " . $key;
    }
    echo "<br/>";
    var_dump($productList[" 6"]);
    echo "<br/>";
    echo $_GET['id'];
    echo "<br/>";

    if (isset($_GET['id'])) {
        $id = " ".$_GET['id'];
    } else {
        header('Location: listprod.php');
    }

    unset($productList[$id]);

    $_SESSION['productList'] = $productList;

	header('Location: showcart.php');

};

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