<?php
class ShoppingCart{
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
	}
	
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
	
	}
}

?>