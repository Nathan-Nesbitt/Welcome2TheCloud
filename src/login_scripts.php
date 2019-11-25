<?php 

    function login($connection, $userid, $password) {
        /* Function that checks to see if a user exists and the passwords match */

        /* Gets the hashed password from the entered user */
        $query = $connection->prepare("SELECT password FROM customer WHERE userid = ?");
        $query->bind_param("s", $userid); 
        $query->execute();
        $serverPassword = $query->get_result()->fetch_assoc()["password"];
        
        /* Checks to see if the hashed passwords match */
        if (password_verify($password, $serverPassword)) {
            return TRUE;
        }
        else 
            return FALSE;

    }

    /* Function to check to see if it is right password */
    function idLogin($connection, $custId, $password) {

        $query = $connection->prepare("SELECT password FROM customer WHERE customerId = ?");

        $query->bind_param("i", $custId);
        $query->execute();

        $resultingPassword = $query->get_result()->fetch_assoc()["password"];

        if (password_verify($password, $resultingPassword)) {
            return TRUE;
        }

        return FALSE;
}

    function storeToken($connection, $user, $token) {
        /* Function to store the value for the token for a user in the database */
        $query = $connection->prepare("UPDATE customer SET token=? WHERE userid=?");
        $query->bind_param("ss", $token, $user);
        $query->execute();
    }

    function fetchToken($connection, $user){
        /* Function to get the token for some user from the database */

        $query = $connection->prepare("SELECT token FROM customer WHERE userid=?");
        $query->bind_param("s", $user);
        $query->execute();
        $token = $query->get_result()->fetch_assoc();
        return $token["token"];
    }

    function removeToken($connection, $userid) {
        /* Function to remove the token for some user */
        
        $query = $connection->prepare("UPDATE customer SET token=NULL WHERE userid=?");
        $query->bind_param("s", $userid);
        $query->execute();
    }

    function createToken($connection, $user) {
        /* 
            This should be moved to a better spot, it's only here for testing and should be changed ASAP
            DO NOT DEPLOY WITH THIS! IT IS NOT SECURE!
        */
        $SECRET_KEY = "987aw3btyrc9879y8wa37rct87atvw3r6520987an98b987fg9861mnlmdsac0";

        /* Creates a random 255 length token */
        $token = bin2hex(random_bytes(255));
        /* Stores the token in the database */
        storeToken($connection, $user, $token);

        $cookie = $user . ':' . $token;
        $hash = hash_hmac('sha256', $cookie, $SECRET_KEY);
        $cookie .= ':' . $hash;
        setcookie('loggedIn', $cookie);
    }

    function checkToken($connection) {
        /* Function that checks to see if the current user is logged in based on their cookie */

        $cookie = isset($_COOKIE['loggedIn']) ? $_COOKIE['loggedIn'] : '';
        /* 
            This should be moved to a better spot, it's only here for testing and should be changed ASAP
            DO NOT DEPLOY WITH THIS! IT IS NOT SECURE!
        */
        $SECRET_KEY = "987aw3btyrc9879y8wa37rct87atvw3r6520987an98b987fg9861mnlmdsac0";

        /* This checks to see if the cookie exists */
        if ($cookie) {
            list ($user, $token, $hash) = explode(':', $cookie);
            
            /* This checks to see if the hash matches */
            if (!hash_equals(hash_hmac('sha256', $user . ':' . $token, $SECRET_KEY), $hash)) {
                return false;
            }
            /* Gets the token and checks if the user's token matches that of the user */
            $usertoken = fetchToken($connection, $user);
            if (hash_equals($usertoken, $token)) {
                return true;
            }
        }
    }

    function removeSessionToken($connection){
        $cookie = $_COOKIE["loggedIn"];
        $userId = explode(":", $cookie)[0];
        setcookie('loggedIn', NULL);
        removeToken($connection, $userId);
    }
?>