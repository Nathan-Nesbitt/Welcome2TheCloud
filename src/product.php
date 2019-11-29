<!DOCTYPE html>
<html>

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
    
    echo "<p class='text-center'>" . $row["productDesc"] . "</p>";

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
        <title>Product Information - Welcome2TheCloud</title>
        <meta charset='UTF-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0' />
        <link rel="stylesheet" href="image.css">
        <link rel="icon" type="image/png" href="images/Welcome2TheCloud.png" type="image/x-icon">
        <link rel="stylesheet" href="shop.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
            integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="icon" type="image/png" href="images/Welcome2TheCloud.png" type="image/x-icon">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
        </script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
        </script>
    </head>

    <body>
        <nav class="navbar sticky-top navbar-expand-lg navbar-light">
            <img alt="Brand" src="images/Welcome2TheCloud.png" style="width: 50px">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul id="navbar-ul" class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Homepage<span class="sr-only"></span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="listprod.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listorder.php">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a id="login-nav" class="nav-link" href="login.html">Login</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="row" id="Homepage">
                <div class="col-lg-12 col-md-12 col-sm-12 text-center" style="padding-top:20px;">
                    <div class="column">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <?php							                              
                                displayImage();
                            ?>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <?php                                                          
                                displayDetail();                                                                                         
                            ?>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <?php                               
                                    $connection = createConnection();
                                    $result = getInfoForCart($connection);
                                    $row = $result->fetch_assoc();
                                    
                                    echo "<a href='addcart.php?id= ".$row["productId"] . "&name=" . 
                                            urlencode($row["productName"]) . "&price=".number_format($row["productPrice"],2). 
                                            "'><button class='btn btn-success mb-1'>add to cart</button></a></td></tr>"
                                            ?>
                            <a href="listprod.php"><button class='btn btn-primary mb-1'>Continue
                                    Shopping</button></a>
                        </div>
                    </div>
                </div>
            </div>
    </body>

    </html>
    <footer class="container mt-12">
        <div class="row">
            <div class="col">
                <p class="text-center">View the code at <a
                        href="https://github.com/Nathan-Nesbitt/Welcome2TheCloud">Welcome2TheCloud</a></p>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
    <script>
        function checkUser() {
            var cookieExists = Cookies.get("loggedIn");
            if (cookieExists) {

                // Changes out the login for the Customer Page Navbar Button //
                cookieExists = cookieExists.split(':');
                // Gets the login element //
                var loginElement = document.getElementById("login-nav");
                // Changes the href and the name so it says the logged in users name
                loginElement.href = 'customer.php';
                loginElement.innerHTML = cookieExists[0];

                newLi = '<li class="nav-item dropdown">';
                newLi += '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>';
                newLi += '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
                newLi += '<a class="dropdown-item" href="/admin.php">Admin Overview</a>';
                newLi += '<a class="dropdown-item" href="/addProduct.html">Add Product</a>';
                newLi += '</li>';
                $("#navbar-ul").append(newLi);

                // Add the logout navbar button //
                newLi = '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
                $("#navbar-ul").append(newLi);
            }
        }
        checkUser();
    </script>

</html>