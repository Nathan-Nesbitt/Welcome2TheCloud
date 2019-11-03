[![Build Status](https://travis-ci.com/Nathan-Nesbitt/Welcome2TheCloud.svg?token=D4VK1pxxdxMWgNqgGdYi&branch=master)](https://travis-ci.com/Nathan-Nesbitt/Welcome2TheCloud)

Where you can come to buy clouds. A simple webserver and store POC using PHP, MYSQL, HTML, Javascript, GIT and Travis CI. 

# How to use this?

## If you are just using this for testing and are pushing to my git repo:
### Modifying the files
#### src/include/db_credentials.php
You need to create a src/include/db_credentials.php file with the following code and fill out the information for your database.
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
### Creating the environment

First you need to make it so you can run the tests, I used composer which gets the dependencies for you using: `composer update` 

If you want to run the tests you can use the following commands, it's kinda hacky for now. Once everything is running well I can update the structure to ensure that they run by just running phpunit.

```{php}
vendor/bin/phpunit --bootstrap src/addcart.php tests/AddCartTest.php
vendor/bin/phpunit --bootstrap src/checkout.php tests/CheckoutTest.php
vendor/bin/phpunit --bootstrap src/listorder.php tests/ListOrderTest.php
vendor/bin/phpunit --bootstrap src/listprod.php tests/ListProdTest.php
vendor/bin/phpunit --bootstrap src/loaddata.php tests/LoadDataTest.php
vendor/bin/phpunit --bootstrap src/order.php tests/OrderTest.php
vendor/bin/phpunit --bootstrap src/showcart.php tests/ShowCartTest.php
```

## If you are using it on your own server:
Since I went through the trouble of figuring all of this out I may as well share the love. This is how to autoconfig Travis CI pushes to a VPS.

### Modifying the files
#### .travis/deploy.sh
This file is here to run the git push script at the end of the successful testing. You simply need to change this so the pushes go to your own server, and that the ssh-keyscan line is updated with the information about your server, so you don't get errored out when Travis CI doesn't recognize your server identity.

#### deploy_rsa.enc
This is produced before hand using the `ssh-keygen -t rsa -b 4096 -C 'build@travis-ci.org' -f deploy_rsa` command, which create a keypair for your server. You can then use `travis encrypt-file deploy_rsa --add` command to encrypt this file so you can push it to git safely.   

#### src/include/db_credentials.php
You need to create a src/include/db_credentials.php file with the following code and fill out the information for your database.
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

### On your server
We also need to set up the server. You need MySQL and Apache2 installed, along with all of the required PHP modules. There is a lot of config that I am not including, assuming that you know how to set up a webserver.

1. Create a new user, add the unencrypted SSH key to their account so they can remote in without a password.
2. Create a folder in that users directory called Welcome2TheCloud
3. Soft link that folder to the /var/www/http/... directory
4. Configure apache to serve up that link and have it set the root of the website to be the src folder.
5. Run `git init` in the original project folder to create a new git project
6. Run `git config receive.denyCurrentBranch ignore` in the folder as well to avoid additional errors
7. Change the branch to be HEAD not master
