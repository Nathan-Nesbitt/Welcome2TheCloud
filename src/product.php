<!DOCTYPE html>
<html>
<head>
<title>w2tdotc - Product Information</title>
</head>
<body>



<?php

// Get product name to search for
// TODO: Retrieve and display info for the product
// $id = $_GET['id'];

include 'include/db_connection.php';

function getId(){
    if(isset($_GET['id']))
    $selected = $_GET['id'];
    return $selected;
}


function getDetails($connection) {
    
    $s = getId();
        
        $query = $connection->prepare("SELECT productDesc FROM product WHERE productId = ?");
        $query->bind_param("i", $s);
        $query->execute();
        $result = $query->get_result();
        return $result;      

}

function displayDetail(){
    $connection = createConnection();
    $result = getDetails($connection);
    $row = $result->fetch_assoc();
    
    echo $row["productDesc"];

    $connection->close();



}



// TODO: If there is a productImageURL, display using IMG tag

// TODO: Retrieve any image stored directly in database. Note: Call displayImage.php with product id as parameter.

// TODO: Add links to Add to Cart and Continue Shopping
displayDetail();

?>
</body>
</html>