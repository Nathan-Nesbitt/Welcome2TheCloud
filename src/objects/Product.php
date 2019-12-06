<?php
class Product {

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
    
    function createProduct($connection, $productName, $productPrice, $productDesc, $categoryId, $productImageURLImage) {
    /* Function to create the product in the database */
        
        $productImageURL = self::addImageToSystem($productImageURLImage);
    
        $result = self::addProductToDatabase($connection,  $productName, $productPrice, $productImageURL, $productDesc, $categoryId);
        
        return $result;
    }
    
    function addImageToSystem($productImageURLImage) {
        // Directory for the images
        $photoDir = "images/";
            
        // Since we will run into issues with file conflicts, 
        // the image url becomes the current unix time + image name
        // The support for embeded images was removed. 
        if($productImageURLImage['name'] != ""){
            $productImageURL =  $photoDir . time() . $productImageURLImage['name'];
            move_uploaded_file($productImageURLImage["tmp_name"] , $productImageURL);
        }
    
        return $productImageURL;
    }
    
    function addProductToDatabase($connection, $productName, $productPrice, $productImageURL, $productDesc, $categoryId){
        /* 
        *    Takes in product details and creates the product in the database 
        *    Returns TRUE if successful, and FALSE if failed 
        * 
        * */
    
        /* Prepares the function so we can pass in the values from the user */
        $query = $connection->prepare("INSERT INTO product (productName, productPrice, productImageURL, productImage, productDesc, categoryId) VALUES (?, ?, ?, ?, ?, ?)");
        /* Passes the values into the query */
        $query->bind_param("sisssi", $productName, $productPrice, $productImageURL, $productDesc, $categoryId);
    
        $result = $query->execute();
    
        return $result;
    }
    
    function getImage($connection, $productId){
    
        $query = $connection->prepare("SELECT productImageURL, productId FROM product WHERE productId = ?");
        $query->bind_param("i", $productId);
        $query->execute();
        $result = $query->get_result();
        return $result;
    
    }

    function getDetails($connection, $productId) {
        $query = $connection->prepare("SELECT productDesc FROM product WHERE productId = ?");
        $query->bind_param("i", $productId);
        $query->execute();
        $result = $query->get_result();
        return $result;      
    
    }

    function getInfoForCart($connection, $productId){
        $query = $connection->prepare("SELECT * FROM product WHERE productId = ?");
        $query->bind_param("i", $productId);
        $query->execute();
        $result = $query->get_result();
        return $result; 
    }    
}


?>