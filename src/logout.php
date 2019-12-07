<?php
    require_once 'include/db_connection.php';
    require_once 'objects/Login.php';
    
    $connection = createConnection();
    Login::removeSessionToken($connection);
    $connection->close();
    
    header('Location:/');
?>