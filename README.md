
# Welcome2TheCloud
[![Build Status](https://travis-ci.com/Nathan-Nesbitt/Welcome2TheCloud.svg?token=D4VK1pxxdxMWgNqgGdYi&branch=master)](https://travis-ci.com/Nathan-Nesbitt/Welcome2TheCloud) 

## What is this?
A simple store for all of your cloud needs. Built using PHP, HTML, and Javascript with a MYSQL back end. 

It is configured to auto-deploy to a server when you successfully pass all tests on Travis CI. 

If you want to see a live version of the site, it can be seen at Welcome2The.cloud. 

## How to use this?

### If you are just using this for testing and are pushing to my git repo

#### Setting up the environment
1. Install mysql, php7.2, Apache2 on WSL
2. Log into mysql as sudo `sudo mysql`
3. Create a new user for this database using `CREATE USER '<username>'@'localhost' IDENTIFIED BY '<password>';`
4. Create a database for this account `CREATE DATABASE <database>`
5. Allow the new user access to the database `GRANT ALL ON <database>.* TO '<username>'@'localhost';`
6. Flush privileges to ensure that everything has been committed. `flush privileges;`

#### Configuring apache to redirect to your file
1. Download the files to a directory on your computer
2. Soft link that directory to the apache serving folder `ln -s <FullPathToTheFolder> /var/www/html/<SomeFolderName>` 
3. Open the apache conf file `sudo vi /etc/apache2/sites-available/000-default.conf`
4. Change the file so the line `DocumentRoot /var/www/html/` reads `DocumentRoot /var/www/html/<SomeFolderName>/src`
5. Add the line `DirectoryIndex shop.html` below the `DocumentRoot` line
6. Run `sudo service apache2 start` to start the server

**At this point you should be able to connect to the server by typing in localhost in a browser**

#### Modifying the files
##### src/include/db_credentials.php
You need to fill out the src/include/db_credentials.php with the information for your database.
```{php}
<?php
	$username = "<username>";
	$password = "<password>";
	$database = "<database>";
	$server = "localhost";
?> 
```
The push script has been configured to ignore these files, as every push to the master will overwrite the website login information.

#### Creating the environment

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

### If you are using it on your own server:
Since I went through the trouble of figuring all of this out I may as well share the love. This is how to autoconfig Travis CI pushes to a VPS.

#### Modifying the files
##### .travis/deploy.sh
This file is here to run the git push script at the end of the successful testing. You simply need to change this so the pushes go to your own server, and that the ssh-keyscan line is updated with the information about your server, so you don't get errored out when Travis CI doesn't recognize your server identity.

##### .travis/excluded.txt
This file specifies which files to ignore when syncing to the server. You can specify directories or files. Right now I have it set up so it ignores the login data (see Creating the environment above for why). You can specify any files that you do not want deployed to the server on every push.

##### deploy_rsa.enc
This is produced before hand using the `ssh-keygen -t rsa -b 4096 -C 'build@travis-ci.org' -f deploy_rsa` command, which create a keypair for your server. You can then use `travis encrypt-file deploy_rsa --add` command to encrypt this file so you can push it to git safely.   

##### src/include/db_credentials.php
See section above, it is the same.

#### On your server
We also need to set up the server. You need MySQL and Apache/nginx installed, along with all of the required PHP modules. There is a lot of config that I am not including, assuming that you know how to set up a basic webserver.

1. Create a new user, add the unencrypted SSH key to their account so they can remote in without a password.
2. Create a folder in that users directory called Welcome2TheCloud
3. Soft link that folder to the /var/www/http/... directory
4. Configure apache to serve up that link and have it set the root of the website to be the src folder.

When I was using git pushes before you would have needed to git init...ect but with rsync this is no longer nessisary as it is not a clone of the repository, but rather a copy that is pushed.