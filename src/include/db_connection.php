<?php
    include 'db_credentials.php';

    /* 
    Function that creates a connection to a database and check to be sure
    that it has succeeded.
    */
    function createConnection() {

        /* We set the variables to be the global defined variables in this function. */
        global $server, $username, $password, $database;
        
        // Create Connection (because OOP is better)
        $connection = new mysqli($server, $username, $password, $database);
        
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
        
        return $connection;

    };

?>