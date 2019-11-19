<?php

    require_once 'include/db_connection.php';
    require_once 'login_scripts.php';


    function mainAdmin() {
        $connection = createConnection();
        $loggedIn = checkToken($connection);
        if (!$loggedIn){
            echo 'You are not logged in!';
            return FALSE;
        }

        // Get the userID
        $cookie = $_COOKIE["loggedIn"];
        $userId = explode(":", $cookie)[0];
        
        // Collect user information
        $getUserInfoQuery = $connection->prepare("SELECT * FROM customer WHERE userid = ?");
        $getUserInfoQuery->bind_param("s", $userId);
        $getUserInfoQuery->execute();
        $userInfo = $getUserInfoQuery->get_result()->fetch_assoc();

        // Print out table
        echo "<tr><th>Id</th><td>". $userInfo["customerId"] ."</td></tr>";
        echo "<tr><th>First Name</th><td>" . $userInfo["firstName"] . "</td></tr>";
        echo "<tr><th>Last Name</th><td>" . $userInfo["lastName"] . "</td></tr>";
        echo "<tr><th>Email</th><td>" . $userInfo["email"] . "</td></tr>";
        echo "<tr><th>Phone</th><td>" . $userInfo["phonenum"] . "</td></tr>";
        echo "<tr><th>Address</th><td>" . $userInfo["address"] . "</td></tr>";
        echo "<tr><th>City</th><td>" . $userInfo["city"] . "</td></tr>";
        echo "<tr><th>State</th><td>" . $userInfo["state"] . "</td></tr>";
        echo "<tr><th>Postal Code</th><td>" . $userInfo["postalCode"] . "</td></tr>";
        echo "<tr><th>Country</th><td>" . $userInfo["country"] . "</td></tr>";
        echo "<tr><th>User id</th><td>" . $userInfo["userid"] . "</td></tr>";

        $connection->close();

    }
?>


<!DOCTYPE html>
<html>
<head>
<title>Customer Page</title>
</head>
<body>


<h3>Customer Profile</h3>
<table class="table" border="1">
<?php mainAdmin(); ?>
</table>


</body>
</html>