<?php

function getCurrentItems() {
	// Get the current list of products
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
	$productList = null;
	if (isset($_SESSION['productList'])){
		$productList = $_SESSION['productList'];
	} else{ 	// No products currently in list.  Create a list.
		$productList = array();
	}
	
	return $productList;
};

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

removeItemFromCart();

?>