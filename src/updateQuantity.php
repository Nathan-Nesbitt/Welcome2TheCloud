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

    function changeQuantity() {
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

    changeQuantity();
?>