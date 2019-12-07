<?php
	include 'include/db_connection.php';
	require_once 'objects/Login.php';

	function loadData() {
		# Let's make a connection to the database
		$connection = createConnection();

		$loggedIn = Login::checkToken($connection);
		if (!$loggedIn){
			return FALSE;
		}
		
		# Set any cookies to null to prevent the 'stuck logged in' bug
		Login::removeSessionToken($connection);

		echo("<h1>Connecting to database.</h1>");
		# If there is no connection made we can quit, as there is an error
		if (!$connection) {
			die("Connection failed: " . mysqli_connect_error());
		}
		echo "Connected successfully";
		
		# Let's import our database file
		$fileName = "./data/orderdb_sql.ddl";
		# Import the data from the file
		$file = file_get_contents($fileName, true);
		# Create an array of all of the file contents
		$lines = explode(";", $file);
		echo("<p>"+$lines[0]+"</p>");

		echo("<ol>");
		# For each line in the file
		foreach ($lines as $line){
			# Trime the line
			$line = trim($line);
			# As long as the line isn't blank
			if($line != ""){
				# Print it out in a list
				echo("<li>".$line . ";</li><br/>");
				# Run a query on the line
				$result = $connection->query($line);
				if($result == FALSE){
					printf("error: %s\n", $connection->error);
				}
			}
		}

		# Finally let's close this connection
		$connection->close();
		echo("</p><h2>Database loading complete!</h2>");
		return TRUE;
	}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset='UTF-8' />
	<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0' />
	<title>Orders - Welcome2TheCloud</title>
	<link rel="icon" type="image/png" href="images/Welcome2TheCloud.png" type="image/x-icon">
	<link rel="stylesheet" href="stylesheets/shop.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
		integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
		integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
		integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
	</script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
		integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
	</script>
</head>

<body>
	<nav class="navbar sticky-top navbar-expand-lg navbar-light">
		<img alt="Brand" src="images/Welcome2TheCloud.png" style="width: 50px">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
			aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul id="navbar-ul" class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" href="/">Homepage<span class="sr-only"></span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="listprod.php">Products</a>
				</li>
				<li class="nav-item">
					<a id="login-nav" class="nav-link" href="login.html">Login</a>
				</li>
			</ul>
		</div>
	</nav>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="slide-content">
					<?php
						$success = loadData();
						if(!$success){
							echo '<h2 align="center">Failed to reload the DB, it is likely that you are not logged in.</h2>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<footer class="container mt-12">
		<div class="row">
			<div class="col">
				<p class="text-center">View the code at <a href="https://github.com/Nathan-Nesbitt/Welcome2TheCloud">Welcome2TheCloud</a></p>
			</div>
		</div>
	</footer>
</body>
</html>