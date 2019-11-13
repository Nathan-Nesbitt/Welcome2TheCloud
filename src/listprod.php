<?php

	/* 
	Include the custom OOP file for connecting to the DB means
	we don't have to do all of the checks again. It's also better 
	because we don't carry around the username and password variables
	in every PHP script we write. 
	*/
	include 'include/db_connection.php';
	
	function getProducts($connection, $name) {
		
		if($name == "") {
			$query = "SELECT * FROM product ";
		}
		else {
			$query = "SELECT * FROM product WHERE productname LIKE "  . "'%$name%'";
		}
		$result = $connection-> query($query);
		return $result;
	} 

	function printTableProd(){

		/** Get product name to search for **/
		if (isset($_GET['productName'])){
			$name = $_GET['productName'];
		}
		else {
			$name = "";
		};
		/* Creates and checks the connection (from db_connection) */
		$connection = createConnection();

		/* Gets the results */
		$result = getProducts($connection, $name);
	 
		if ($result->num_rows > 0) {
    		while($row = $result->fetch_assoc()) {
				echo "<tr>
					productId: " . $row["productId"]. " " .
			 		"productName: " . $row["productName"]. " " .
			  		"Price: " . $row["productPrice"] . " " .
			   		"Description: " . $row["productDesc"] . "<br>" . $row["productName"] . 
			    	"<a href='addcart.php?id= ".$row["productId"]."&name=".urlencode($row["productName"]) . "&price=".number_format($row["productPrice"],2). "'>Link</a>" . "<br>";
    			}
		}
		else {
			echo "0 results";
		}
		/** Close connection **/
		$connection->close();
	}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Nothing But Clouds</title>
</head>
<body>

<h1>Search for the products you want to buy:</h1>

<form method="get" action="listprod.php">
<input type="text" name="productName" size="50">
<input type="submit" value="Submit"><input type="reset" value="Reset"> (Leave blank for all products)
</form>
<?php
	/* Runs the main function to print the tables */
	printTableProd();
?>

</body>
</html>