<!DOCTYPE html>
<html>

<head>
    <title>Product Information - Welcome2TheCloud</title>
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

function getImage($connection){

    $s = getId();

    $query = $connection->prepare("SELECT productImageURL, productId FROM product WHERE productId = ?");
    $query->bind_param("i", $s);
    $query->execute();
    $result = $query->get_result();
    return $result;

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


function displayImage(){

    $connection = createConnection();
    $result = getImage($connection);
    $row = $result->fetch_assoc();
    

        if($row["productImageURL"] == null){

            // Makes a call to the display image php file, which creates an image //
            
            echo "<img class=resize src='displayImage.php?id=".$row["productId"] . "'>";
            $connection->close();
            
        }
    
        else{
            echo "<img class=resize src=" .$row["productImageURL"] . ">";
            $connection->close();
        }

 function getInfoForCart($connection){
    
    $s = getId();
        
    $query = $connection->prepare("SELECT * FROM product WHERE productId = ?");
    $query->bind_param("i", $s);
    $query->execute();
    $result = $query->get_result();
    return $result; 
 }
     
}



// TODO: If there is a productImageURL, display using IMG tag

// TODO: Retrieve any image stored directly in database. Note: Call displayImage.php with product id as parameter.

// TODO: Add links to Add to Cart and Continue Shopping


?>

<!DOCTYPE html>
<html>
<head>
<title>w2tdotc - Product Information</title>
        <link rel="stylesheet" href="shop.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
</head>
<body>
        <nav class="navbar sticky-top navbar-expand-lg navbar-light">
                <img alt="Brand" src="images/Welcome2TheCloud.png" style="width: 50px">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">        
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                                <li class="nav-item">
                                        <a class="nav-link" href="/">Homepage<span class="sr-only"></span></a>
                                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="listprod.php">Products</a>
                                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="listorder.php">Orders</a>
                                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="createAccount.html">Create Account</a>
                                </li>
                        </ul>
                </div>
        </nav>               
</body>
</html>
</html>
<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="image.css">
    <title> Product Display </title>
    </head>
    <body>
        <div>
        <?php
        displayImage();
        ?>
        </div>
        <div>
            <?php
            displayDetail();
            ?>
        </div>
        <div>
            <?php
            $connection = createConnection();
            $result = getInfoForCart($connection);
            $row = $result->fetch_assoc();
            
            echo "<a href='addcart.php?id= ".$row["productId"] . "&name=" . 
					urlencode($row["productName"]) . "&price=".number_format($row["productPrice"],2). 
                    "'><button class='btn btn-success mb-1'>add to cart</button></a></td></tr>"
                    ?>
        <a href="listprod.php"><button class='btn btn-primary mb-1'>Continue Shopping</button></a>
        </div>
    </body>
    <footer class="container mt-12">
    <div class="row">
        <div class="col">
            <p class="text-center">View the code at <a
                    href="https://github.com/Nathan-Nesbitt/Welcome2TheCloud">Welcome2TheCloud</a></p>
        </div>
    </div>
</footer>
    </html>

