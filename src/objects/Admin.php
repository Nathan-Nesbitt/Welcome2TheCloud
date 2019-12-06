<?php

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
    
    function getUserById($connection, $userId){
        $getUserInfoQuery = $connection->prepare("SELECT * FROM customer WHERE userid = ?");
        $getUserInfoQuery->bind_param("s", $userId);
        $getUserInfoQuery->execute();
        $userInfo = $getUserInfoQuery->get_result()->fetch_assoc();
    
        return $userInfo;
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