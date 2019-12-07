<?php

require_once "Account.php";

class Admin extends Account {
    function getOrderAmountAndTotalPrice($connection) {
        $query = $connection->prepare("SELECT COUNT(*) AS numberOrders, SUM(totalAmount) as totAmount
                                        FROM ordersummary");
        $query->execute();
        return $query->get_result()->fetch_assoc();
    }
    
    function getUsers($connection) {
        $query = $connection->prepare("SELECT customerId, firstName, lastName, userid from customer");
        $query->execute();
        return $query->get_result();
    }
    
    function getUserById($connection, $userid) {
        /**
         * Gets all customer info for some user based on their userID
         * 
         * Returns: Result from query.
         */
    
        $getCustomerInfo = $connection->prepare("SELECT customerId, firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid FROM customer WHERE userid=?");
        $getCustomerInfo->bind_param("s", $userid);
        $getCustomerInfo->execute();
        $result = $getCustomerInfo->get_result()->fetch_assoc();
    
        return $result;
    }
    
    function getOrderAmountByDay($connection) {
        $sql = "SELECT DATE(orderDate) as orderDay, SUM(totalAmount) as totAmount
                    FROM ordersummary
                    GROUP BY orderDay";
        $result = $connection->query($sql);
        return $result;
    }
}


?>