[![Build Status](https://travis-ci.com/Nathan-Nesbitt/Welcome2TheCloud.svg?token=D4VK1pxxdxMWgNqgGdYi&branch=master)](https://travis-ci.com/Nathan-Nesbitt/Welcome2TheCloud)

Where you can come to buy clouds. A simple webserver and store POC using PHP, MYSQL, HTML, Javascript, GIT and Travis CI. 

## How to use this?
You can import it, and run it using apache and MYSQL pretty simply. You just need to create a database and a server, move the src files into the server. You need to include a src/include/db_credentials file with the following code and fill out the information for your database.
```{php}
<?php
	$username = "";
	$password = "";
	$database = "";
	$server = "localhost";
	$connectionInfo = array( "Database"=>$database, "UID"=>$username, "PWD"=>$password, "CharacterSet" => "UTF-8");
?> 
```
I tried to get git to not push the template code to the server but it breaks as it wants to overwrite the data that is already there. 
