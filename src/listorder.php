<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0' />
<title>Order List - Welcome2TheCloud</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
                integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
                crossorigin="anonymous">
        <link rel="stylesheet"
                href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
                integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
                integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
                integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
                crossorigin="anonymous"></script>
</head>

</head>
<body>

<h1>Order List</h1>

<?php
include 'include/db_connection.php';

/* Function to get all orders from the database */
function getOrders($connection) {
	$query = "SELECT * FROM ordersummary O, customer C WHERE O.customerId = C.customerId";
	$result = $connection-> query($query);
	return $result;
}

/* Function to get all products in a given order */
function getOrderProducts($connection, $orderId) {
	/* Creates a prepared statement */
	$query = $connection->prepare($connection, "SELECT * FROM orderproduct WHERE orderId=?");
	/* Binds the prepared statement with the orderID (i is to specify integer) */
	$query->bind_param("i", $orderId);
	$query->execute();
	return $query;
}

/* Main function for this page, prints out all orders and all products */
function printTable($connection) {
	
		$result = getOrders($connection);

		echo '<table class="table">';
		echo '<tr>
				<th scope="col">Order Id</th>
				<th scope="col">Order Date</th>
				<th scope="col">Customer Id</th>
				<th scope="col">Customer Name</th>
				<th scope="col">Total Amount</th>
			</tr>';
		
		while($row = $result->fetch_assoc()) {
			/* Prints out the 'head values' or the order values */
			echo '<tr>
				<td>'.$row["orderId"].'</td>
				<td>'.$row["orderDate"].'</td>
				<td>'.$row["customerId"].'</td>
				<td>'.$row["firstName"]." ".$row["lastName"].'</td>
				<td>'.number_format($row["totalAmount"], 2).'</td>
			</tr>';
			
			echo '<tr align="right"> 
					<th scope="col">Product Id</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
				</tr>';
			
			$innerResult = getOrderProducts($connection, $row["customerId"]);
			if ($innerResult->num_rows != 0) {	
				while($innerRow = $innerResult->fetch_assoc()) {
					echo '<tr>
						<td>'.$innerRow["productId"].'</td>
						<td>'.$innerRow["quantity"].'</td>
						<td>'.$innerRow["price"].'</td>
					</tr>';
				}
			}
			echo "</table>";
		}
		echo "</table>";
	}

/* Creates and checks the connection */
$connection = createConnection();
/*  */
printTable($connection);
$connection->close();

/**
Useful code for formatting currency:
	number_format(yourCurrencyVariableHere,2)
**/


/** Write query to retrieve all order headers **/

/** For each order in the results
		Print out the order header information
		Write a query to retrieve the products in the order
			- Use sqlsrv_prepare($connection, $sql, array( &$variable ) 
				and sqlsrv_execute($preparedStatement) 
				so you can reuse the query multiple times (just change the value of $variable)
		For each product in the order
			Write out product information 
**/


/** Close connection **/
?>

</body>
</html>

