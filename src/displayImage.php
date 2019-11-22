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
    $s = 11;

    $query = $connection->prepare("SELECT productImage FROM product WHERE productId = ?");
    $query->bind_param("i", $s);
    $query->execute();
    $result = $query->get_result();
    return $result;

    $result = alt_getImage($connection);
            
            $row = $result->fetch_assoc();
            echo "".base64_encode($row["productImage"]); 
            $connection->close();

}

function createImage($connection, $id){
    if ($id != null)
        alt_getImage($connection);
}

$id = $_GET['id'];

$connection = createConnection();

createImage($connection, $id);

?>
