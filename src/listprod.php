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

	
	include 'include/db_credentials.php';
	
	/** Get product name to search for **/
	if (isset($_GET['productName'])){
		$name = $_GET['productName'];
	}
	
		
	/** $name now contains the search string the user entered
	 Use it to build a query and print out the results. **/
	 /** Create and validate connection **/
	 //connecting to database
	 $mysqli = new mysqli($localhost, $username, $password, $database);
	 if ($mysqli->connect_errno) {
		 echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	 }
	 //echo $mysqli->host_info . "\n";
	 if($name == "all"){
		 $sql = "SELECT * FROM product ";
	 }
	 else{
	 $sql = "SELECT * FROM product WHERE productname LIKE "  . "'%$name%'" ;
	 }
	 $result = mysqli_query($mysqli, $sql);
	 
	 if ($result->num_rows > 0) {
	/** Print out the ResultSet **/
	// output data of each row
	/** 
	For each product create a link of the form
	addcart.php?id=<productId>&name=<productName>&price=<productPrice>
	Note: As some product names contain special characters, you may need to encode URL parameter for product name like this: urlencode($productName)
	**/
    	while($row = $result->fetch_assoc()) {
			echo "<tr>
			productId: " . $row["productId"]. " " .
			 "productName: " . $row["productName"]. " " .
			  "Price: " . $row["productPrice"] . " " .
			   "Description: " . $row["productDesc"] . "</td>
			    <td><a href='addcart.php?id= ".$row["productId"]."&name=".urlencode($row["productName"]) . " &price=".number_format($row["productPrice"],2)."</a></td><br>";
			   	 
    	}
	} else {
		echo "0 results";
		

	


}
/** Close connection **/
$conn->close();





	
   
	
	
	

	

	
	
	

	/**
        Useful code for formatting currency:
	       number_format(yourCurrencyVariableHere,2)
     **/
?>

</body>
</html>