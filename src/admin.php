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
        else {
            echo 'Congrats!';
        }

        // Write the rest of the code for the admin page //

    }

    mainAdmin();
?>