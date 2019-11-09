<?php
    include 'db_credentials.php';

    /* 
    Function that creates a connection to a database and check to be sure
    that it has succeeded.
    */
    function createConnection() {
        
        $connection = mysqli_connect($server, $username, $password, $database);
        if (!$connection) {
            return NULL;
        }        
        return $connection;

    };

?>