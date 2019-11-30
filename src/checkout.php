<!DOCTYPE html>
<html>

<head>
        <meta charset='UTF-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0' />
        <title>Checkout - Welcome2TheCloud</title>
        <link rel="icon" type="image/png" href="images/Welcome2TheCloud.png" type="image/x-icon">
        <link rel="stylesheet" href="shop.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
                integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
                crossorigin="anonymous">
        <link rel="stylesheet"
                href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
                integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
                integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
                integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
                crossorigin="anonymous"></script>
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
                                <li class="nav-item">
                                        <a class="nav-link" href="listprod.php">Products</a>
                                </li>
                                <li class="nav-item">
                                        <a id="login-nav" class="nav-link" href="login.html">Login</a>
                                </li>
                        </ul>
                </div>
        </nav>
        <div class="row" id="Side-Slideshow">
                <div class="col-lg-12 col-md-12 col-sm-12" align="center">
                        <div class="slide-content">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                        <h3>Enter your username and password to complete the transaction</h3>
                                        <form method="post" action="order.php">
                                                <div class="form-group">
                                                        <input type="text" class="form-control" name="Username"
                                                                placeholder="Username" required>
                                                </div>
                                                <div class="form-group">
                                                        <input type="password" class="form-control" name="password"
                                                                placeholder="password" required>
                                                </div>
                                                <input type="submit" class="btn btn-success mb-2" value="Submit">
                                                <input type="reset" class="btn btn-danger mb-2" value="Reset">
                                        </form>
                                </div>
                        </div>
                </div>
        </div>
        </div>
        <footer class="container mt-12">
                <div class="row">
                        <div class="col">
                                <p class="text-center">View the code at <a
                                                href="https://github.com/Nathan-Nesbitt/Welcome2TheCloud">Welcome2TheCloud</a>
                                </p>
                        </div>
                </div>
        </footer>
</body>

<script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
<script>
        // Function to show the current user if they are logged in and change navbar //

        function checkUser() {
                var cookieExists = Cookies.get("loggedIn");
                if(cookieExists){

                        // Changes out the login for the Customer Page Navbar Button //
                        cookieExists = cookieExists.split(':');
                        // Gets the login element //
                        var loginElement = document.getElementById("login-nav");
                        loginElement.remove();
                        
                        // Add Admin Navbar Dropdown //
                        
                        newLi = '<li class="nav-item dropdown">';
                        newLi += '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>';
                        newLi += '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
                        newLi += '<a class="dropdown-item" href="/admin.php">Admin Overview</a>';
                        newLi += '<a class="dropdown-item" href="/addProduct.html">Add Product</a>';
                        newLi += '<a class="dropdown-item" href="/listorder.php">All Orders</a>';
                        newLi += '</li>';
                        $("#navbar-ul").append(newLi);

                        // Adds User Navbar Dropdown //
                        newLi = '<li class="nav-item dropdown">';
                        newLi += '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+cookieExists[0]+'</a>';
                        newLi += '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
                        newLi += '<a class="dropdown-item" href="/customer.php">User Summary</a>';
                        newLi += '<a class="dropdown-item" href="/showcart.php">View Cart</a>';
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