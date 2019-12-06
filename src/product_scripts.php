<?php

function getProducts($connection, $name=null){
    if($name == null) {
        $query =  "SELECT * FROM product";
        $result = $connection-> query($query);   
    }
    else {
        //prepared stmt functionality
        $query = $connection->prepare("SELECT * FROM product WHERE productName LIKE ?");
        $query->bind_param("s", $name);
        $query->execute();
        $result = $query->get_result();
    }
    return $result;
}

// function to return product lists by category
function getProductsByCategory($connection, $cat) {
    $query = $connection->prepare("SELECT * 
                                   FROM product, category 
                                   WHERE product.categoryId = category.categoryId 
                                   AND categoryName = ?");
    $query->bind_param("s", $cat);
    $query->execute();
    $result = $query->get_result();
    return $result;
}

?>