<?php
header("Content-Type: image/jpeg");
include 'include/db_connection.php';

function getId(){
    if(isset($_GET['id']))
    $selected = $_GET['id'];
    return $selected;
}

function alt_getImage($connection){
    $s = getId();

    $query = $connection->prepare("SELECT productImage FROM product WHERE productId = ?");
    $query->bind_param("i", $s);
    $query->execute();
    $result = $query->get_result();
    return $result;
}

function createImage($connection, $id){
    if ($id != null){
        $result = alt_getImage($connection);
        if (mysqli_num_rows($result) == 0)
            return FALSE;
        $row = $result->fetch_assoc();
        echo $row["productImage"]; 
        $connection->close();
    }
}

$id = $_GET['id'];

$connection = createConnection();

$success = createImage($connection, $id);
if(!$success)
    echo "FAILED TO LOAD IMAGE";

?>
