<?php

    require_once 'include/db_connection.php';

    function getUserValues() {
        if (isset($_GET['userid'])){
            $userid = $_GET['userid'];
        }
        if (isset($_GET['password'])){
            $password = $_GET['password'];
        }
        return array($userid, $password);
    }

    function login($connection, $userid, $password) {
        $query = $connection->prepare("SELECT password FROM customer WHERE userid = ?");
        $query->bind_param("s", $userid); 
        $query->execute();
        $serverPassword = $query->get_result()->fetch_assoc();

        /*  
            ONCE THE HASH IS SET UP THIS IS WHERE THE 
            HASH FUNCTION WOULD GO, CONVERTING PASSWORD FROM
            USER TO HASH FOR SERVER USING SALT IN TABLE. 
        */

        if ($password == $serverPassword["password"]) {
            return TRUE;
        }
        else {
            return FALSE;
        }

    }

    /* Creates a connection */
    $connection = createConnection();
    /* Gets the userid and password from the login page*/
    list($userid, $password) = getUserValues();
    /* Checks to see if the user and pass are correct */
    $loginResult = login($connection, $userid, $password);
    
    if($loginResult)
        echo "SUCCESS LOGGED IN";
    else
        echo "FAILED, WRONG PASSWORD";
    

?>