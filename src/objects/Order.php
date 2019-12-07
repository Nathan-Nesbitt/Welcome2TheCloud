<?php

class Order {
    function checkUserInput($connection, $userid, $password, $productList) {
	
        /**
         * Function that checks the user input to see if userID exists,
         * password matches for that user, and that the product list exists
         * 
         * Returns: FALSE if any of these fail and prints to screen. TRUE if no error.
         * 
         * **/
        $rightPassword = Login::loginUser($connection, $userid, $password);
        if ($rightPassword == FALSE) {
            echo "<h3>Wrong Password or Username</h3>";
            return FALSE;
        }
    
        if(is_null($productList)){
            echo "<h3>No Ordered Data</h3>";
            return FALSE;
        }
        
        return TRUE;
    }
    
    function addProductsToOrder($connection, $productList, $orderId) {
        /**
         * Adds all products to a cart using the productlist and orderId
         * 
         * Returns: None
         */
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
    }

    function getCustomerAndOrderInfo($connection, $orderId) {
        $getInfo = $connection->prepare("SELECT * FROM ordersummary O, customer C WHERE O.customerId = C.customerId AND O.orderId=?");
        $getInfo->bind_param("i", $orderId);
        $getInfo->execute();
        $result = $getInfo->get_result()->fetch_assoc(); 
        return $result;
    }

    function getProductsInOrder($connection, $orderId){
        $getOrderInfo = $connection->prepare("SELECT * FROM orderproduct, product WHERE orderId = ? AND orderproduct.productId = product.productId");
	    $getOrderInfo->bind_param("i", $orderId);
	    $getOrderInfo->execute();
        $orderInfo = $getOrderInfo->get_result();
        return $orderInfo;
    }

    function createOrderSummary($connection, $userid) {
        /**
         * Creates a new order for a certain user based on their username
         * 
         * Returns: orderId
         */
        $customerInfo = Admin::getUserById($connection, $userid);
        
        $orderDate = date('Y-m-d H:i:s');
    
        $orderSummaryQuery = $connection->prepare("INSERT INTO ordersummary (customerId, totalAmount, orderDate) VALUES (?, 0, ?)");
        $orderSummaryQuery->bind_param("is", $customerInfo["customerId"], $orderDate);
        $orderSummaryQuery->execute();
    
        /* Gets the newly created order ID from the ordersummary table */
        $orderId = $orderSummaryQuery->insert_id;
    
        return $orderId;
    }

    /** Save order information to database**/
    function saveOrderData($connection){

        /**
         * Calls all required functions to load the data into the database
         */
        
        list($userid, $password, $productList) = getOrderData($connection);

        $success = self::checkUserInput($connection, $userid, $password, $productList);
        if(!$success)
            return FALSE;

        $orderId = self::createOrderSummary($connection, $userid);

        self::addProductsToOrder($connection, $productList, $orderId);
        
        return $orderId;
    }
}
?>