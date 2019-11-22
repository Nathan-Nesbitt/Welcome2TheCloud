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

    $query = $connection->prepare("SELECT productImageURL FROM product WHERE productId = ?");
    $query->bind_param("i", $s);
    $query->execute();
    $result = $query->get_result();
    $check = true;
    return $result;

}

function alt_getImage($connection){

    $s = getId();

    $query = $connection->prepare("SELECT productImage FROM product WHERE productId = ?");
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
        $connection->close();
        echo "no image found ";
        $connection = createConnection();
        $result = alt_getImage($connection);
        $row = $result->fetch_assoc();
        echo "working on it....";
        //insert code to apend data retrieved from query to jpeg extension?
        $connection->close();
        }
    
        else{
        echo "<img src=" .$row["productImageURL"] . ">";
        $connection->close();
        }
     
}



// TODO: If there is a productImageURL, display using IMG tag

// TODO: Retrieve any image stored directly in database. Note: Call displayImage.php with product id as parameter.

// TODO: Add links to Add to Cart and Continue Shopping
displayImage();
displayDetail();
echo "<a href= 'listprod.php?'> continue searching</a>";

?>
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