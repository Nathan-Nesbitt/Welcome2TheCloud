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

function createProduct($connection) {
/* Function to create the product in the database */

	/* Gets the list of values from the function */
    list ($productName, $productPrice, $productDesc, $categoryId, $productImageURLImage, $productImageImage) = getUserValues();
    
    $productImageURL = addImageToSystem($productImageURLImage, $productImageImage);

    $result = addProductToDatabase($connection,  $productName, $productPrice, $productImageURL, $productImageImage, $productDesc, $categoryId);
    
    return $result;
}

function addImageToSystem($productImageURLImage, $productImageImage) {
    // Directory for the images
    $photoDir = "images/";
        
    // Since we will run into issues with file conflicts, 
    // the image url becomes the current unix time + image name 
    if($productImageURLImage['name'] != ""){
        $productImageURL =  $photoDir . time() . $productImageURLImage['name'];
        move_uploaded_file($productImageURLImage["tmp_name"] , $productImageURL);
    }

    if($productImageImage['name'] != ""){
        // Since we will run into issues with file conflicts, 
        // the image url becomes the current unix time + image name
        $productImage = $photoDir . time() . $productImageImage["name"];
        move_uploaded_file($productImageImage["tmp_name"] , $productImage);
        $productImageImage = file_get_contents($productImage);
    }

    return $productImageURL;
}

function addProductToDatabase($connection, $productName, $productPrice, $productImageURL, $productImageImage, $productDesc, $categoryId){
    /* 
    *    Takes in product details and creates the product in the database 
    *    Returns TRUE if successful, and FALSE if failed 
    * 
    * */

    /* Prepares the function so we can pass in the values from the user */
    $query = $connection->prepare("INSERT INTO product (productName, productPrice, productImageURL, productImage, productDesc, categoryId) VALUES (?, ?, ?, ?, ?, ?)");
    /* Passes the values into the query */
    $query->bind_param("sisssi", $productName, $productPrice, $productImageURL, $productImageImage, $productDesc, $categoryId);

    $result = $query->execute();

    return $result;
}

?>