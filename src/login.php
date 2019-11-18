<?php

    require_once 'include/db_connection.php';
    require_once 'login_scripts.php';

    function getUserValues() {
        if (isset($_POST['userid'])){
            $userid = $_POST['userid'];
        }
        if (isset($_POST['password'])){
            $password = $_POST['password'];
        }
        return array($userid, $password);
    }

    /* Creates a connection */
    $connection = createConnection();
    /* Gets the userid and password from the login page*/
    list($userid, $password) = getUserValues();
    /* Checks to see if the user and pass are correct */
    $loginResult = login($connection, $userid, $password);
    
    if($loginResult) {
        echo "SUCCESS LOGGED IN";
        /* Creates a random token to auth the user for the session */
        createToken($connection, $userid);

    }
    else
        echo "FAILED, WRONG PASSWORD";
    

?>