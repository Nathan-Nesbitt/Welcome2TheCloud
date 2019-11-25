<?php
    require_once 'include/db_connection.php';
    require_once 'login_scripts.php';
    
    $connection = createConnection();
    
    removeSessionToken($connection);
    
    $connection->close();
    
    header('Location:/');
?>